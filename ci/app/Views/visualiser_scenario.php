<h2>Détails du Scénario</h2>

<?php if (isset($message)) : ?>
    <p><?= $message ?></p>
<?php endif; ?>


<?php if (isset($scenario)) : ?>
    <ul>
        <?php foreach ($scenario as $key => $value) : ?>
            <?php if ($key == 'scn_image') : ?>
                <li><?= ucfirst($key) ?> : <img src="<?= base_url('ressources/') . $value ?>" alt="Scenario Image" style="max-width: 200px; max-height: 200px;"></li>
            <?php elseif ($key == 'scn_id') : ?>
                <!-- Récupérer les questions et réponses pour le scénario -->
                <?php $questionsReponses = getQuestionsReponses($value); ?>
                <li><?= ucfirst($key) ?> : <?= $value ?></li>
                <!-- Afficher les questions et réponses si elles existent -->
                <?php if (!empty($questionsReponses)) : ?>
                    <li>Questions et Réponses :
                        <ul>
                            <?php foreach ($questionsReponses as $etape) : ?>
                                <li>
                                    <strong>Question :</strong> <?= $etape['eta_question'] ?><br>
                                    <strong>Réponse :</strong> <?= $etape['eta_reponse'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else : ?>
                    <li>Aucune étape disponible pour ce scénario.</li>
                <?php endif; ?>
            <?php else : ?>
                <li><?= ucfirst($key) ?> : <?= $value ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= base_url('index.php/scenario/accueil'); ?>">Retour à la liste des scénarios</a>

<?php
// Fonction pour récupérer les questions et réponses d'un scénario
function getQuestionsReponses($scn_id)
{
    $model = model(Db_model::class);
    return $model->getQuestionsReponses($scn_id);
}
?>
