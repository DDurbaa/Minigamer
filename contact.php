<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creators Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh; 
        }
        h1 {
            color: var(--primary-color); /* proměnná barva */
            font-size: 4.5em;
            margin-bottom: 20px;
        }
        p.email {
            font-size: 3em;
            color: white; /* bílá barva */
            margin-bottom: 40px;
        }
        h2 {
            color: var(--primary-color); /* proměnná barva */
            font-size: 3em;
            margin-bottom: 20px;
        }
        .creators {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .card {
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            text-align: center; /* zarovnání na střed */
            background-color: #1a1a1a; 
        }
        .card h3 {
            color: white;
            font-size: 2em;
            margin-bottom: 10px;
        }
        .card a {
            color: var(--primary-color); /* proměnná barva */
            text-decoration: none;
        }
        .card a:hover {
            text-decoration: underline;
        }
        .icon {
            font-size: 36px; /* zvětšené ikony */
            margin-right: 10px;
            transition: transform 0.2s; /* animace při hover */
        }
        .icon:hover {
            transform: scale(1.2); /* zvětšení při hover */
        }
        .icon-container {
            display: flex;
            justify-content: center; /* zarovnání ikon na střed */
            align-items: center;
            margin-top: 10px;
        }
        .logo{
            margin-top: 20px;
        }
        .buttonexit {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 20px 40px;
    border: 2px solid #CD090F;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-size: 1.5em;
    transition: background-color 0.3s, color 0.3s;
  }
  .buttonexit:hover {
      background-color: #CD090F;
      color: #333;
  }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="buttonexit">EXIT</a>
        <div class="logo">
            <img src="mlogo.png" alt="Minigamer Logo">
        </div>
    </header>
    <h1>Contact Us</h1>
    <p class="email">support@minigamer.eu</p>

    <h2>Creators</h2>
    <div class="creators">
        <div class="card">
            <h3>Dominik Urbánek</h3>
            <div class="icon-container">
                <a href="https://dominikurbanek.cz/"><i class="fas fa-globe icon"></i></a>
                <a href="https://github.com"><i class="fab fa-github icon"></i></a>
                <a href="https://www.instagram.com/urbanekdominik/"><i class="fab fa-instagram icon"></i></a>
            </div>
        </div>
        <div class="card">
            <h3>Šimon Švarc</h3>
            <div class="icon-container">
                <a href="https://simonsvarc.cz/"><i class="fas fa-globe icon"></i></a>
                <a href="https://github.com/simyxx"><i class="fab fa-github icon"></i></a>
                <a href="https://www.instagram.com/simythecreator/"><i class="fab fa-instagram icon"></i></a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            document.documentElement.style.setProperty('--primary-color', randomColor);
        });
    </script>
</body>
</html>
