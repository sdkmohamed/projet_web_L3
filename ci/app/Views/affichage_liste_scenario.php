<h2>Liste des Scénarios</h2>

<div style="text-align: center; margin-top: 10px;">
    <a href="<?= base_url('index.php/scenario/ajouter_scenario'); ?>">
        <button style="background-color: #4CAF50; color: white;">+</button> Ajouter un scénario
    </a>
</div>

<table border="1">
    <tr>
        <th>Intitule</th>
        <th>Auteur</th>
        <th>Image</th>
        <th>Nb_etape</th>
        <th>Code</th>
        <th>État</th>
        <th>Visualiser</th>
        <th>Modifier</th>
        <th>Supprimer</th>
        <th>Copier</th>
        <th>Activer/Desactiver</th>
        <th>Remise à 0</th>
    </tr>

    <?php foreach ($scenarios as $scenario): ?>
        <tr<?= session()->has('user') && session('user') == $scenario['Auteur'] ? ' style="background-color: #aaffaa;"' : '' ?>>
            <td><?= $scenario['Intitule'] ?></td>
            <td><?= $scenario['Auteur'] ?></td>
            <td style="text-align: center;"><img src="<?= base_url('ressources/') . $scenario['img'] ?>" alt="Scenario Image" style="max-width: 50px; max-height: 50px;"></td>
            <td><?= $scenario['Nb_etape'] ?></td>
            <td><?= $scenario['code'] ?></td>
            <td><?= $scenario['etat'] ?></td>
            
            <!-- Colonnes pour les boutons -->
            <td>
                <a href="<?= base_url('index.php/scenario/visualiser/' . $scenario['code']); ?>">
                    <button>Visualiser</button>
                </a>
            </td>

            <td>
                <?php if (session()->has('user') && session('user') == $scenario['Auteur']): ?>
                    <a href="<?= base_url('index.php/scenario/accueil/'); ?>">
                        <button>Modifier</button>
                    </a>
                <?php endif; ?>
            </td>

            <td>
                <?php if (session()->has('user') && session('user') == $scenario['Auteur']): ?>
                    <a href="<?= base_url('index.php/scenario/supprimer/' . $scenario['code']); ?>"
                        onclick="return confirmDeletion();">
                        <button>Supprimer</button>
                    </a>

                    <script>
                        function confirmDeletion() {
                        return confirm("Êtes-vous sûr de vouloir supprimer <?= addslashes($scenario['Intitule']) ?> de <?= $scenario['Auteur'] ?> ?");
                        }
                    </script>
                <?php endif; ?>

            </td>



            <td>
                <?php if (session()->has('user') && session('user') != $scenario['Auteur']): ?>
                    <a href="<?= base_url('index.php/scenario/accueil/'); ?>">
                        <button>Copier</button>
                    </a>
                <?php endif; ?>
            </td>

            <td>
                <?php if (session()->has('user') && session('user') == $scenario['Auteur']): ?>
                    <a href="<?= base_url('index.php/scenario/activer_desactiver/' . $scenario['code'] . '/' . ($scenario['etat'] == 'A' ? 'D' : 'A')); ?>">
                        <button><?= $scenario['etat'] == 'A' ? 'Desactiver' : 'Activer' ?></button>
                    </a>
                <?php endif; ?>
            </td>

            <td>
                <?php if (session()->has('user') && session('user') == $scenario['Auteur']): ?>
                    <a href="<?= base_url('index.php/scenario/accueil'); ?>">
                        <button>Remise à 0</button>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
