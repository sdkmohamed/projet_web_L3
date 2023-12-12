<h2>Informations du profil </h2>

<?php
// Afficher les informations supplémentaires si disponibles
if (!empty($user_info)) {
    echo "<br>Pseudo : " . $user_info['cmp_login'];
    echo "<br>Nom : " . $user_info['cmp_nom'];
    echo "<br>Prénom : " . $user_info['cmp_prenom'];
    echo "<br>État : " . $user_info['cmp_etat'];
    echo "<br>Rôle : " . $user_info['cmp_role'];
    echo "<br>Mot de passe : " . $user_info['cmp_mp'];

    // Ajouter le bouton de modification du mot de passe
    echo '<br><a href="' . base_url('index.php/compte/modifier_mot_de_passe') . '">Modifier le mot de passe</a>';
}
?>

