<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scénarios</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #cfe4f7;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        .scenarios-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        .scenario {
            border-radius: 15px;
            overflow: hidden;
            margin: 15px;
            width: 300px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease-in-out;
            position: relative;
        }

        .scenario:hover {
            transform: scale(1.05);
        }

        .scenario img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }

        .scenario-content {
            padding: 15px;
        }

        .difficulty {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .author {
            margin-top: 10px;
            color: #555;
        }

        .easy,
        .medium,
        .difficult {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .easy {
            background-color: #2ecc71;
            color: white;
        }

        .medium {
            background-color: #f39c12;
            color: white;
        }

        .difficult {
            background-color: #e74c3c;
            color: white;
        }

        .emoji {
            font-size: 24px;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
    </style>
</head>

<body>

    <header>
        <h1>Scénarios :</h1>
    </header>

    <div class="scenarios-container">
        <?php
        if (!empty($scenarios) && is_array($scenarios)) {
            foreach ($scenarios as $scenario) {
                echo "<div class='scenario'>";
                echo "<img src='" . base_url('ressources/') . $scenario["img"] . "' alt='Scenario Image'>";
                echo "<div class='scenario-content'>";
                echo "<h2>" . $scenario["Intitule"] . "</h2>";
                echo "<p class='difficulty'>Niveau de difficulté :</p>";
                echo "<p class='easy' onclick='navigateToDifficulty(\"" . $scenario["code"] . "\", \"facile\")'>🟢 Facile</p>";
                echo "<p class='medium' onclick='navigateToDifficulty(\"" . $scenario["code"] . "\", \"moyen\")'>🟠 Moyen</p>";
                echo "<p class='difficult' onclick='navigateToDifficulty(\"" . $scenario["code"] . "\", \"difficile\")'>🔴 Difficile</p>";
                echo "<p class='author'>Auteur: " . $scenario["Auteur"] . "</p>";
                echo "</div>";
                echo "<div class='emoji'>🎮</div>";
                echo "</div>";
            }
        } else {
            // Aucun scénario disponible
            echo "<p> ℹ️ Aucun scénario disponible pour le moment.</p>";
        }
        // Afficher le message d'erreur s'il est défini
    if (isset($erreur)) {
        echo "<p style='color: red;'>{$erreur}</p>";
    }
    ?>
    </div>

    <script>
        function navigateToDifficulty(scenarioCode, difficulty) {
            window.location.href = "<?php echo base_url('index.php/scenario/afficherPremiereEtape/'); ?>" + scenarioCode + "/" + difficulty;
        }
    </script>

</body>

</html>
