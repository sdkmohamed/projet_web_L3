<h2><?= $titre; ?></h2>

<?= session()->getFlashdata('error') ?>

<?php echo form_open('/compte/creer'); ?>
<?= csrf_field() ?>

<label for="nom">Nom : </label>
<input type="input" name="nom" >
<?= validation_show_error('nom') ?></br>

<label for="prenom">Prénom : </label>
<input type="input" name="prenom" >
<?= validation_show_error('prenom') ?></br>

<label for="pseudo">Pseudo : </label>
<input type="input" name="pseudo" >
<?= validation_show_error('pseudo') ?></br>

<label for="mdp">Mot de passe : </label>
<input type="password" name="mdp" >
<?= validation_show_error('mdp') ?></br>

<label for="conf_mdp">Confirmer le mot de passe : </label>
<input type="password" name="conf_mdp" >
<?= validation_show_error('conf_mdp') ?></br>

<label for="role">Rôle : </label>
<select name="role" >
    <option value="A">Administrateur</option>
    <option value="O">Organisateur</option>
</select></br>

<label for="etat">État : </label>
<select name="etat" >
    <option value="A">Activé</option>
    <option value="D">Désactivé</option>
</select></br>

<input type="submit" name="submit" value="Créer un nouveau compte">
<a href="<?= base_url('index.php/compte/lister') ?>">
            <button type="button">Annuler</button>
      </a>
</form>
