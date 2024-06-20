<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>404 Page Not Found</title>
    <?php
        $colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
        $random_color = $colors[array_rand($colors)];
    ?>
    <style>
        body {
            background-color: #1a1a1a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            position: absolute;
            top: 20px;
        }
        .logo img {
            height: auto;
        }
        .description {
            text-align: center;
        }
        .description h1 {
            color: <?php echo $random_color; ?>;
            font-size: 14em;
            margin-bottom: 0.2em;
        }
        .description p {
            color: white;
            font-size: 3em;
            margin-top: 0;
            margin-bottom: 1em;
        }
        .description a {
            color: white;
            font-size: 2.5em;
            text-decoration: none;
            border: 2px solid <?php echo $random_color; ?>;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .description a:hover {
            background-color: <?php echo $random_color; ?>;
            color: #1a1a1a;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="mlogo.png" alt="Minigamer Logo">
        </div>
    </header>
    <main>
        <div class="description">
            <h1>404</h1>
            <p>Page Not Found</p>
            <a href="index.php">GO BACK</a>
        </div>
    </main>
</body>
</html>
