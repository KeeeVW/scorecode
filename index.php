<?php
// Basic security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Content-Security-Policy: default-src 'self'");

// Check if we're using HTTPS
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSecService - Secure Web Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WebSecService</h1>
        <div class="status <?php echo $isSecure ? 'success' : 'warning'; ?>">
            <?php if ($isSecure): ?>
                <p>✅ Connection is secure (HTTPS)</p>
            <?php else: ?>
                <p>⚠️ Connection is not secure (HTTP)</p>
            <?php endif; ?>
        </div>
        <p>Welcome to WebSecService! This is a secure web application running on <?php echo $_SERVER['SERVER_NAME']; ?></p>
        <p>Current time: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
</body>
</html> 