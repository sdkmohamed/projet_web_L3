<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;
class Accueil extends BaseController
{
public function member()
{
    $model = model(Db_model::class);
    $data['titre'] = 'Actualité :';
    $data['actualites'] = $model->get_all_actualites();

    return view('templates/haut', $data)
. view('menu_visiteur')
. view('affichage_accueil')
. view('templates/bas');
}


}
?>