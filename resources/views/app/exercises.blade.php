<html>
            <head>
                <title>Övning</title><meta name="viewport" content="width=device-width, initial-scale=1" />
                
                <style>
                    body {
                      margin: 0;
                      padding: 0;
                      box-sizing: border-box;
                      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                                  'Helvetica Neue', Arial, sans-serif;
                      font-size: 16px;
                      line-height: 1.5;
                      color: #ffffff;
                      background-color: #000261;
                    }

                    h1, h2, h3, h4, h5, h6 {
                      font-family: inherit; /* samma som body */
                      font-weight: 600;
                      margin: 0.6em 0 0.4em 0;
                      line-height: 1.3;
                    }

                    h1 { font-size: 24px; }
                    h2 { font-size: 20px; }
                    h3 { font-size: 18px; }
                    h4 { font-size: 16px; font-weight: 500; }
                    h5 { font-size: 15px; font-weight: 500; }
                    h6 { font-size: 14px; font-weight: 500; }

                    p {
                      margin: 0 0 1em 0;
                    }

                    iframe {
                      max-width: 100%;
                      width: 100%;
                      height: auto;
                    }
              </style>
            </head>
            <body>
                {!! $exercise->html_content ?? "<h1>Ingen beskrivning tillgänglig</h1>" !!}
            </body>
        </html>