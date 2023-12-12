
<h2><?php echo $titre; ?></h2>
<h3>Nombre total de comptes : <?php echo $count; ?></h3>


<a> État : 
    <span style="color: #4CAF50; background-color: #E8F5E9; padding: 5px 10px; border-radius: 5px;">A = activé</span>
    <span style="color: #FF0000; background-color: #FFEBEE; padding: 5px 10px; border-radius: 5px;">D = désactivé</span>
</a>
<a> Rôle : 
    <span style="color: #4CAF50; background-color: #E8F5E9; padding: 5px 10px; border-radius: 5px;">A = Administrateur</span>
    <span style="color: #007BFF; background-color: #E3F2FD; padding: 5px 10px; border-radius: 5px;">O = Organisateur</span>
</a>

<?php if (!empty($logins) && is_array($logins)): ?>
        <!-- Ajouter le bouton "+" avec le lien vers la page de création de compte -->
<a href="<?= base_url('index.php/compte/creer') ?>"><button style="background-color: #4CAF50; color: white;">+</button></a>
    <table border="1">
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logins as $compte): ?>
                <tr>
                    <td><?php echo $compte["cmp_login"]; ?></td>
                    <td><?php echo $compte["cmp_nom"]; ?></td>
                    <td><?php echo $compte["cmp_prenom"]; ?></td>
                    <td><?php echo $compte["cmp_role"]; ?></td>
                    <td><?php echo $compte["cmp_etat"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <h3>Aucun compte pour le moment</h3>
<?php endif; ?>
