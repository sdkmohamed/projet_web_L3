<div>
    <h2>Fin du scénario</h2>

    <?php if (isset($validation) && $validation->hasError('email')) : ?>
        <p style="color: red;"><?php echo $validation->getError('email'); ?></p>
    <?php endif; ?>

    <?php if (isset($message)) : ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php else : ?>
        <p>Félicitations, vous avez terminé ce scénario de niveau <?php echo $niveau; ?> !</p>
        <p>Remplissez le formulaire ci-dessous :</p>
        <?php echo form_open('scenario/fin_etape/' . $code_derniere_etape . '/' . $lecode . '/' . $concat . '/' . $niveau); ?>
        <label for="email">Adresse e-mail :</label>
        <input type="email" name="email" required>
        <input type="submit" name="submit" value="Valider">
        <?php echo form_close(); ?>
    <?php endif; ?>
</div>
