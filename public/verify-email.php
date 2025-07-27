<?php
$conn = new mysqli("localhost", "sjlvd_motionmaster_user", '?€D90cVpUr12L', "sjlvd_motionmaster");
if ($conn->connect_error) {
    die("DB Connection failed!");
}

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $conn->real_escape_string(htmlspecialchars($_GET['email']));
    $verification_code = $conn->real_escape_string($_GET['code']);


    $sql = "SELECT * FROM users WHERE email='$email' AND email_verification_code='$verification_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $timestamp_unix_now = time();
        $sql = "UPDATE users SET email_verified_at='$timestamp_unix_now', email_verification_code=null WHERE email='$email'";
        if ($conn->query($sql)) {
            $message = "<span style='color:darkgreen;'>E-postadressen: <strong>$email</strong> har verifierats!</span>";
        } else {
            $message = "<span style='color:darkred;'>Misslyckades att verifiera e-post! Försök igen senare.</span>";
        }
    } else {
        $message = "<span style='color:darkred;'>E-postverifiering misslyckades! Försök igen senare.</span>";

        //Check if email is already verified
        $sql = "SELECT * FROM users WHERE email='$email' AND email_verified_at IS NOT NULL";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $message = "<span style='color:darkgreen;'>E-postadressen: <strong>$email</strong> är verifierad!</span>";
        }
    }
} else {
    $message = "<span style='color:darkred;'>E-post och verifieringskod krävs!</span>";
}

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekräfta E-post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000261;
            color: #fff;
            text-align: center;
            padding: 1em;
        }
        main {
            padding: 1em;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        footer a {
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Motionmaster - Bekräfta E-post</h1>
    </header>

    <main>
        <h2><?php echo $message; ?></h2>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> <a href="https://motionmaster.sandarnecreations.com">Motionmaster</a></p>
    </footer>
</body>
</html>