<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (!empty($etape)) {
            echo 'Détails de la première étape';
        } else {
            echo 'Erreur';
        }
        ?>
    </title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f7ff;
            display: flex;
            height: 100vh;
        }

        .etape-container {
            border: 2px solid #004080;
            border-radius: 15px;
            padding: 20px;
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            text-align: center;
            margin-left: 670px;
        }

        .etape-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .reponse-input {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            box-sizing: border-box;
        }

        .indice-button {
            background-color: #004080;
            color: #ffffff;
            border: none;
            padding: 10px;
            margin-top: 15px;
            cursor: pointer;
            border-radius: 8px;
        }

        .indice-details {
            display: none;
            margin-top: 20px;
            color: #004080;
        }

        .emoji {
            font-size: 36px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="etape-container">
        <?php if (!empty($etape)) : ?>
            <h1 style="color: #004080; margin-bottom: 20px;">Détails de la première étape 🎮</h1>
            <div>
                <h2 style="color: #004080;"><?php echo $etape->IntituleEtape; ?></h2>
                <?php if (property_exists($etape, 'Ressource')) : ?>
                    <img src="<?php echo base_url('ressources/') . $etape->Ressource; ?>" alt="Image de l'étape">
                <?php endif; ?>
                <p style="color: #004080;">Question : <?php echo $etape->ques; ?></p>

                <!-- Bouton d'indice -->
                <?php if (!empty($etape->Indice)) : ?>
                    <button class="indice-button" onclick="afficherIndice()">Obtenir un Indice ℹ️</button>
                    <div class="indice-details" id="indice-details" style="display: none;">
                        <p>Description de l'indice : <?php echo $etape->Indice; ?></p>
                        <p>Lien de l'indice : <a href="<?php echo $etape->IndiceLien; ?>" target="_blank"><?php echo $etape->IndiceLien; ?></a></p>
                    </div>
                    <script>
                        function afficherIndice() {
                            var indiceDetails = document.getElementById('indice-details');
                            if (indiceDetails.style.display === 'none' && indiceDetails.innerHTML.trim() !== '') {
                                indiceDetails.style.display = 'block';
                            } else {
                                indiceDetails.style.display = 'none';
                            }
                        }
                    </script>
                <?php else : ?>
                    <p style="color: #004080;"> 🤷‍♂️ Aucun indice disponible pour cette étape.</p>
                <?php endif; ?>

                <form action="<?php echo base_url("index.php/scenario/afficherPremiereEtape/{$lecode}/{$niveau}"); ?>" method="post">
                <?= csrf_field() ?>
                <label for="reponse">Votre réponse :</label>
                <textarea class="reponse-input" name="reponse" placeholder="Votre réponse"></textarea>
                <input type="submit" name="submit" value="Valider ✅" style="background-color: #004080; color: #ffffff; padding: 10px;">
                </form>
            </div>
        <?php else : ?>
            <p style="color: #004080;">La première étape n'a pas été trouvée.</p>
        <?php endif; ?>

        <div class="emoji">🌟</div>

    </div>

</body>

</html>
