<!-- Afficher le message s'il existe -->
<?php if (isset($message)) : ?>
    <p><?= esc($message) ?></p>
<?php endif; ?>

<h2>Modifier le mot de passe</h2>

<?php if (!empty($user_info)) : ?>
    <form method="post" action="<?= base_url('index.php/compte/modifier_mot_de_passe') ?>">
        <!-- Afficher les informations de l'utilisateur comme des champs en lecture seule -->
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?= esc($user_info['cmp_nom']) ?>" readonly>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" value="<?= esc($user_info['cmp_prenom']) ?>" readonly>

        <label for="etat">État :</label>
        <input type="text" name="etat" value="<?= esc($user_info['cmp_etat']) ?>" readonly>

        <label for="role">Rôle :</label>
        <input type="text" name="role" value="<?= esc($user_info['cmp_role']) ?>" readonly>

        <!-- Ajout du champ de nouveau mot de passe -->
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" required>

        <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirm_password" required>

        <!-- Ajout du champ CSRF -->
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

        <button type="submit">Modifier le mot de passe</button>

        <!-- Bouton d'annulation -->
        <a href="<?= base_url('index.php/compte/afficher_profil') ?>">
            <button type="button">Annuler</button>
        </a>
    </form>
<?php else : ?>
    <p>Impossible de récupérer les informations de l'utilisateur.</p>
<?php endif; ?>
