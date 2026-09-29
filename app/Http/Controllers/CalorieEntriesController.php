<?php

namespace App\Http\Controllers;

use App\Models\CalorieEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CalorieEntriesController extends Controller
{

    //Return ALL calorie entries for a user
    public function index(User $user)
    {
        return CalorieEntries::where('user_id', $user->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "foodName" => "required",
            "calories" => "required",
            "date" => "required",
        ]);

        //Läs in data från request
        $data = [
            "foodName" => $request->foodName,
            "calories" => $request->calories,
            "date" => $request->date,
            "user_id" => auth()->id(),
        ];

        //Lägg till extra data om det finns
        if($request->caloriesPer100g) {
            $data["caloriesPer100g"] = $request->caloriesPer100g;
        }
        if($request->weight) {
            $data["weight"] = $request->weight;
        }

        //Skapa en ny post
        return CalorieEntries::create($data);
    }

    //Hämta alla Calorie Entries för den inloggade användaren den senaste veckan, summera antal kalorier dag för dag. Returnera som en lista med datum och totala kalorier för varje dag. samt en lista med dagens calorie entries och en lista med övriga datums calorie entries
    public function getWeeklyCalorieSummary()
    {
        $userId = auth()->id();
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        $entries = CalorieEntries::where('user_id', $userId)
            ->whereBetween('date', [$weekAgo, $today])
            ->orderBy('date', 'asc')
            ->get();

        $dailyTotals = [];
        $todayEntries = [];
        $otherEntries = [];
        $caloriesToday = 0;

        foreach ($entries as $entry) {
            // Summera kalorier per dag
            if (!isset($dailyTotals[$entry->date])) {
                $dailyTotals[$entry->date] = 0;
            }
            $dailyTotals[$entry->date] += $entry->calories;

            // Dela upp dagens entries och övriga
            if ($entry->date === $today) {
                $todayEntries[] = $entry;
                $caloriesToday += $entry->calories;
            } else {
                $otherEntries[] = $entry;
            }
        }

        return response()->json([
            'dailyTotals' => $dailyTotals,
            'todayEntries' => $todayEntries,
            'otherEntries' => $otherEntries,
            'caloriesToday' => $caloriesToday,
        ]);
    }

    //Radera en post utifrån ID
    public function destroy(CalorieEntries $calorieEntry)
    {
        if($calorieEntry->user_id != auth()->id()) {
            return response()->json("Du har inte behörighet att radera denna post", 403);
        }

        $calorieEntry->delete();
        return response()->json("Kaloriinmatning raderad", 200);
    }

    // Ladda upp en bild på en maträtt för att få AI att tolka vilken maträtt det är och uppskatta hur många kalorier den innehåller.
    public function aiInterpretFood(Request $request)
    {
        $request->validate([
            'imageBase64' => 'required|string|max:14000000',
        ]);

        $imageBase64 = trim($request->input('imageBase64'));
        if (str_starts_with($imageBase64, 'data:')) {
            $commaPosition = strpos($imageBase64, ',');
            $imageBase64 = $commaPosition === false ? '' : substr($imageBase64, $commaPosition + 1);
        }

        $imageBytes = base64_decode($imageBase64, true);
        if ($imageBytes === false || $imageBytes === '') {
            return response()->json(['error' => 'Bilden måste vara giltig base64-data.'], 422);
        }

        $imageInfo = @getimagesizefromstring($imageBytes);
        $mimeType = $imageInfo['mime'] ?? null;
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return response()->json(['error' => 'Bilden måste vara JPEG, PNG eller WebP.'], 422);
        }

        $system = 'You analyze food photos. Return ONLY one valid JSON object, without markdown or extra text.';
        $prompt = <<<'PROMPT'
Identify the dish in this image and estimate the calories for the entire visible serving.

Return exactly this JSON structure:
{"foodName":"short Swedish dish name","calories":number}

The calorie estimate must be a positive whole number. If the dish or portion is unclear, make your best reasonable estimate from the image.
PROMPT;

        $client = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);

        try {
            $response = $client->post('https://eu-west-1.api.x.ai/v1/responses', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config('services.xai.key'),
                ],
                'json' => [
                    'model' => 'grok-4.3',
                    'instructions' => $system,
                    'input' => [[
                        'role' => 'user',
                        'content' => [
                            ['type' => 'input_text', 'text' => $prompt],
                            ['type' => 'input_image', 'image_url' => 'data:' . $mimeType . ';base64,' . $imageBase64],
                        ],
                    ]],
                    'store' => false,
                    'max_output_tokens' => 100,
                    'temperature' => 0,
                ],
            ]);

            $responseData = json_decode((string) $response->getBody(), true);
            $llmOutput = $this->getResponseText($responseData);
            $parsedOutput = json_decode($llmOutput, true);

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !is_array($parsedOutput) ||
                !isset($parsedOutput['foodName'], $parsedOutput['calories']) ||
                !is_string($parsedOutput['foodName']) ||
                !is_numeric($parsedOutput['calories']) ||
                (float) $parsedOutput['calories'] <= 0
            ) {
                Log::error('[AI][aiInterpretFood] invalid model response', [
                    'user_id' => auth()->id(),
                    'output_preview' => mb_substr($llmOutput, 0, 800),
                ]);

                return response()->json(['error' => 'Felaktigt AI-svar. Försök igen senare.'], 422);
            }

            return response()->json([
                'foodName' => trim($parsedOutput['foodName']),
                'calories' => (int) round((float) $parsedOutput['calories']),
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            Log::error('[AI][aiInterpretFood] request failed', [
                'user_id' => auth()->id(),
                'error' => $exception->getMessage(),
                'status' => $response?->getStatusCode(),
                'body_preview' => $response ? mb_substr((string) $response->getBody(), 0, 800) : null,
            ]);

            return response()->json(['error' => 'Fel vid anrop till AI-tjänsten. Försök igen senare.'], 422);
        }
    }

    private function getResponseText(?array $responseData): string
    {
        $text = '';

        foreach ($responseData['output'] ?? [] as $output) {
            if (($output['type'] ?? null) !== 'message') {
                continue;
            }

            foreach ($output['content'] ?? [] as $content) {
                if (($content['type'] ?? null) === 'output_text' && isset($content['text'])) {
                    $text .= $content['text'];
                }
            }
        }

        return trim($text);
    }
}
