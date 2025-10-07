<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MBSTU</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        
        <!-- Fallback content if JavaScript fails -->
        <noscript>
            <div style="padding: 40px; font-family: Arial, sans-serif; background: #f0f9ff; min-height: 100vh;">
                <div style="max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h1 style="color: #1e40af; font-size: 2.5rem; margin-bottom: 20px; text-align: center;">
                        MBSTU Website - Docker Production
                    </h1>
                    <div style="background: #dcfce7; border: 2px solid #16a34a; padding: 20px; border-radius: 8px;">
                        <h2 style="color: #15803d; margin: 0 0 10px 0;">✅ Success!</h2>
                        <p style="color: #166534; margin: 0;">
                            Your Docker production environment is working! (JavaScript disabled)
                        </p>
                    </div>
                </div>
            </div>
        </noscript>
        
        <!-- JavaScript loading indicator -->
        <script>
            // Show loading message immediately
            document.write('<div id="loading-indicator" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999;"><p style="margin: 0; color: #374151;">Loading MBSTU Website...</p></div>');
            
            // Remove loading indicator after a short delay
            setTimeout(function() {
                var indicator = document.getElementById('loading-indicator');
                if (indicator) {
                    indicator.remove();
                }
            }, 2000);
        </script>
    </body>
</html>
