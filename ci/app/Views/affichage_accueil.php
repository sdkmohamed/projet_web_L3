<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre; ?></title>

    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }

        /* Styles pour le tableau */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 15px;
        }

        th {
            background-color: #3498db;
            color: #ecf0f1;
        }

        tbody tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tbody tr:hover {
            background-color: #bdc3c7;
        }

        /* Styles pour l'icône */
        .arrow-icon {
            font-size: 1.2em;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1><?= $titre; ?></h1>
    </header>

    <div class="container">
        <?php
        $actualites_actives_found = false; // Variable pour vérifier si au moins une actualité active est trouvée
        $table_opened = false; // Variable pour savoir si le tableau a été ouvert

        foreach ($actualites as $actualite) :
            if ($actualite->act_etat == 'A') :
                $actualites_actives_found = true; // Actualité active trouvée

                // Ouvrir le tableau si ce n'est pas encore fait
                if (!$table_opened) :
        ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Intitulé</th>
                                <th>Description</th>
                                <th>Nom</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php
                    // Marquer que le tableau a été ouvert
                    $table_opened = true;
                endif;
                ?>
                <tr onclick="window.location='<?= base_url('index.php/actualite/afficher/' . $actualite->act_id); ?>'" style="cursor: pointer;">
                    <td>
                        <i class="bi bi-arrow-right-circle arrow-icon"></i> <?= $actualite->act_intitule; ?>
                    </td>
                    <td><?= $actualite->act_description; ?></td>
                    <td><?= $actualite->Auteur; ?></td>
                    <td><?= $actualite->act_date; ?></td>
                </tr>
        <?php
            endif;
        endforeach;

        // Fermer le tableau si au moins une actualité active a été trouvée
        if ($table_opened) :
        ?>
                </tbody>
            </table>
        <?php
        endif;

        // Si aucune actualité active n'a été trouvée, affichez le message
        if (!$actualites_actives_found) :
        ?>
            <p> ℹ️ Aucune actualité pour le moment.</p>
        <?php endif; ?>
    </div>
</body>

</html>
