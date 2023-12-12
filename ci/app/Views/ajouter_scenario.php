<title>Création de Scénario</title>
<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<h2> <?php echo $titre; ?>

  <div class="container mt-5">
  <?php  echo form_open_multipart('/scenario/ajouter_scenario'); ?>
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="intitule">Intitulé :</label>
        <input type="text" class="form-control" name="intitule" required>
      </div>
      <div class="form-group">
        <label for="image">Image (URL) :</label>
        <input type="file" class="form-control" name="fichier" required>
      </div>
      <button  class="btn btn-primary">Submit</button>
      </div>
      <a href="<?= base_url('index.php/scenario/accueil') ?>">
            <button type="button">Annuler</button>
      </a>
    </form>



















































