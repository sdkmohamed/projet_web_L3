<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;
class Scenario extends BaseController
{
    public function lister()
    {
        $model = model(Db_model::class);
        $data['scenarios'] = $model->get_all_scenarios();
        $data['titre'] = 'Liste des Scénarios';
        return view('templates/haut', $data)
            . view('menu_visiteur')
            . view('affichage_scenario')
            . view('templates/bas');
    }


//      public function afficherPremiereEtape($code, $niveau)
// {
//     $model = model(Db_model::class);
//     $data['etape'] = $model->get_scenario_eta($code, $niveau);

//     if ($data['etape']) {
//         $data['titre'] = 'Détails de la première étape';
//         return view('templates/haut', $data)
//             . view('menu_visiteur')
//             . view('affichage_premiere_etape')
//             . view('templates/bas');
//     } else {
//         $data['titre'] = 'Scénarios';
//         $data['scenarios'] = $model->get_all_scenarios();
//         return view('templates/haut', $data)
//             . view('menu_visiteur')
//             . view('affichage_premiere_etape')
//             . view('templates/bas');
//     }
// }

public function afficherPremiereEtape($code, $niveau)
    {
        helper('form');
        $model = model(Db_model::class);
        $data['etape'] = $model->get_scenario_eta($code, $niveau);

        if ($this->request->getMethod() == 'post') {
            // L’utilisateur a validé le formulaire
            $reponseUtilisateur = $this->request->getPost('reponse');
            $reponseBonne = $model->get_bonne_reponse($code); // Assurez-vous d'avoir cette fonction dans votre modèle

            if ($reponseUtilisateur === $reponseBonne) {
                // Si c’est la bonne réponse, recherche du code de la prochaine étape
                $prochaineEtapeNum = $model->get_prochaine_etape_num($code);

                if ($prochaineEtapeNum !== null) {
                    $code_prochaineEtape = $model->get_prochaine_etape_code($prochaineEtapeNum,$code);
                
                    if ($code_prochaineEtape) {
                        return redirect()->to(site_url("scenario/franchir_etape/{$code_prochaineEtape}/{$niveau}"));
                    } else {
                        return redirect()->to(site_url("scenario/fin_etape/{$code}/{$niveau}"));
                    }
                } else {
                    // Gérez le cas où il n'y a pas de prochaine étape
                    return redirect()->to(site_url("scenario/fin_etape/{$code}/{$niveau}"));
                }
            } else {
                $data['lecode'] = $code; // Ajoutez cette ligne pour passer le code à la vue
        $data['niveau'] = $niveau; // Ajoutez cette ligne pour passer le niveau à la vue
        return view('templates/haut', $data)
            . view('menu_visiteur')
            . view('affichage_premiere_etape')
            . view('templates/bas');
            }
        }

        // L'utilisateur veut afficher le formulaire
        $data['titre'] = 'Détails de la première étape';
        $data['lecode'] = $code; // Ajoutez cette ligne pour passer le code à la vue
        $data['niveau'] = $niveau; // Ajoutez cette ligne pour passer le niveau à la vue
        return view('templates/haut', $data)
            . view('menu_visiteur')
            . view('affichage_premiere_etape')
            . view('templates/bas');
    }

    public function franchir_etape($code, $niveau)
    {
        helper('form');
        $model = model(Db_model::class);
    
        // Récupérer les informations de la prochaine étape en utilisant le code actuel
        $num_prochaineEtape = $model->get_prochaine_etape_num2($code);
        $code_scenario = $model->get_code_scenario($code);
        $code_prochaineEtape = $model->get_prochaine_etape_code2($num_prochaineEtape, $code_scenario);
        $data['etape'] = $model->get_etape_details($code, $niveau);
    
        // Initialiser la variable $reponseCorrecte à null
        $reponseCorrecte = null;
    
        if ($this->request->getMethod() == 'post') {
            $reponseUtilisateur = $this->request->getPost('reponse');
            $reponseBonne = $model->get_bonne_reponse2($code);
    
            // Comparer les réponses et définir la variable $reponseCorrecte
            $reponseCorrecte = ($reponseUtilisateur === $reponseBonne);
    
            // Si la réponse est correcte, rediriger vers la prochaine étape
            if ($reponseCorrecte) {
                return redirect()->to(base_url("index.php/scenario/franchir_etape/{$code_prochaineEtape}/{$niveau}"));
            }
        }
    
        // Affichez le formulaire pour la prochaine étape
        $data['titre'] = 'Détails de la prochaine étape';
        $data['code'] = $code;
        $data['lecode'] = $code_prochaineEtape;
        $data['niveau'] = $niveau;
        $data['lcode'] = $code_scenario;
        $data['reponseCorrecte'] = $reponseCorrecte;
        $data['concat'] = $code_scenario . $code . $niveau;
    
        return view('templates/haut', $data)
            . view('menu_visiteur')
            . view('affichage_etape')
            . view('templates/bas');
    }
    
    

    public function fin_etape($code_eta = 'null', $code, $concat = 'null' , $niveau)
    {
        helper('form');
        $model = new Db_model();
    
        if ($this->request->getMethod() == 'post') {
            // Validation des données du formulaire
            if (!$this->validate([
                'email' => 'required|valid_email|max_length[255]'
            ],
            [
                'email' => [
                    'required' => 'Veuillez entrer une adresse e-mail !',
                    'valid_email' => 'Veuillez entrer une adresse e-mail valide !',
                ]
            ])) {
                // Redirection en cas de validation échouée
                $data['validation'] = $this->validator;
            } else {
                // L'utilisateur a soumis le formulaire
                $email = $this->request->getVar('email');
    
                // Enregistrez l'adresse e-mail dans la table participant
                $model->enregistrer_email_participant($email);
                $id = $model->get_scenario_id($code);
    
                // Déterminez la valeur de $nv en fonction du niveau
                $nv = 1; // Par défaut, pour le niveau facile
                if ($niveau === 'moyen') {
                    $nv = 2;
                } elseif ($niveau === 'difficile') {
                    $nv = 3;
                }
    
                // Enregistrez le code du scénario, le niveau et l'email dans la table de réussite
                $model->enregistrer_reussite($id, $nv, $email);
                
                // Affichage du message de réussite
                $data['message'] = 'Votre réussite a été enregistrée avec succès !';
            }
        }
    
        $data['lecode'] = $code;
        $data['niveau'] = $niveau;
        $data['code_derniere_etape'] = $code_eta ;
        $data['concat'] = $concat;
    
        // Utilisez le service 'view' pour charger les vues
        return view('templates/haut', $data)
            . view('menu_visiteur')
            . view('affichage_fin_etape')
            . view('templates/bas');
    }
    
    



   
    
    


public function accueil()
{
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }
    $model = model(Db_model::class);

    $data['titre'] = "Espace Administrateur";
    $data['session_user'] = session()->get('user');

    // Ajoutez cette ligne pour récupérer les scénarios
    $data['scenarios'] = $model->get_all_scenarios_table();

    return view('templates/haut2', $data)
        . view('menu_organisateur')
        . view('affichage_liste_scenario', $data) 
        . view('templates/bas2');
}
   





public function visualiser($scenario_code)
{
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }

    $model = model(Db_model::class);

    // Utiliser la fonction scenarioExiste pour vérifier l'existence du scénario
    if (!$model->scenarioExiste($scenario_code)) {
        $data['titre'] = 'Erreur';
        $data['message'] = 'Le scénario demandé n\'existe pas.';
        return view('templates/haut2', $data)
            . view('menu_organisateur')
            . view('visualiser_scenario', $data)
            . view('templates/bas2');
    }

    $data['scenario'] = $model->get_scenario_details($scenario_code);

    $data['titre'] = 'Visualisation du Scénario';
    return view('templates/haut2', $data)
        . view('menu_organisateur')
        . view('visualiser_scenario', $data)
        . view('templates/bas2');
}








public function activer_desactiver($scenario_code, $nouvel_etat)
{
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }
    $model = model(Db_model::class);

    try {
        $model->activer_desactiver_scenario($scenario_code, $nouvel_etat);
        return redirect()->to(base_url('index.php/scenario/accueil/'));
    } catch (\Exception $e) {
        return "Erreur : " . $e->getMessage();
    }
}

// public function supprimer($code)
// {
//     // Assurez-vous que l'utilisateur est connecté
//     if (!session()->has('user')) {
//         return view('templates/haut', ['error' => 'Vous devez être connecté pour effectuer cette action.'])
//             . view('templates/bas');
//     }

//     // Créez une instance du modèle
//     $model = model(Db_model::class);

//     // Obtenez les détails du scénario avec la vérification des droits intégrée
//     $scenario = $model->get_scenario_details($code);

//     // Vérifiez si les droits sont valides
//     if ($scenario === false || $scenario['Auteur'] !== session('user')) {
//         return view('templates/haut', ['error' => 'Vous n\'avez pas les droits nécessaires pour supprimer ce scénario.'])
//             . view('templates/bas');
//     }

//     // Appel à la méthode du modèle pour supprimer le scénario et ses étapes
//     $result = $model->supprimer_scenario($code);

//     // Vérifiez si la suppression a réussi
//     if ($result === true) {
//         return view('templates/haut', ['success' => 'Scénario supprimé avec succès.'])
//             . view('templates/bas');
//     } else {
//         return view('templates/haut', ['error' => 'Une erreur s\'est produite lors de la suppression du scénario.'])
//             . view('templates/bas');
//     }
// }

public function supprimer($code)
{
    // Assurez-vous que l'utilisateur est connecté
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }

    // Créez une instance du modèle
    $model = model(Db_model::class);

    // Obtenez les détails du scénario avec la vérification des droits intégrée
    $scenario = $model->get_scenario_details($code);

    // Vérifiez si les droits sont valides
    if ($scenario === false) {
        return redirect()->back()->with('error', 'Vous n\'avez pas les droits nécessaires pour supprimer ce scénario.');
    }

    // Appel à la méthode du modèle pour supprimer le scénario et ses étapes
    $result = $model->supprimer_scenario($code);

    // Vérifiez si la suppression a réussi
    if ($result === true) {
        // Ajoutez un message flash de confirmation
        return redirect()->to(base_url('index.php/scenario/accueil'))->with('success', 'Scénario supprimé avec succès. Confirmez la suppression.');
    } else {
        return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression du scénario.');
    }
}


public function ajouter_scenario()
{
    helper('form');
    $session = session();

    if ($session->has('user')) {
        $model = model(Db_model::class);
        $username = $_SESSION['user'];
        $user_info = $model->get_user_info($session->get('user'));

        if ($user_info['cmp_role'] === 'O') {
            if ($this->request->getMethod() == "post") {
                $validationRules = [
                    'intitule' => 'required|max_length[255]|min_length[5]',
                    'fichier' => [
                        'label' => 'Fichier image',
                        'rules' => 'uploaded[fichier]|is_image[fichier]|mime_in[fichier,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[fichier,1000]|max_dims[fichier,1024,768]'
                    ]
                ];

                if (!$this->validate($validationRules)) {
                    // La validation du formulaire a échoué, retour au formulaire !
                    return view('templates/haut2', ['titre' => 'Créer un nouveau scenario ERREUR DE FORMULAIRE'])
                        . view('menu_organisateur')
                        . view('ajouter_scenario')
                        . view('templates/bas2');
                }
                $session = session();
                $username = $session->get('user');
                $intitule = $this->request->getPost('intitule');
                $etat = 'A'; // État par défaut
                $fichier = $this->request->getFile('fichier');
                $cmp_id = $model->get_id($username);


                if (!empty($fichier)) {
                    // Récupération du nom du fichier téléversé
                    $nom_fichier = $fichier->getName();

                    // Dépôt du fichier dans le répertoire ci/public/images
                    if ($fichier->move("ressources", $nom_fichier)) {
                        // Récupération du cmp_id à partir de la session
                        $object = $session->get('user_id'); // Assurez-vous que la clé de session est correcte
                        $cmp_id = $model->get_id($username); // Assurez-vous que la clé de session est correcte
                        $id = $cmp_id;
                        $model->add_scenario($intitule, $nom_fichier, $etat, $cmp_id);
                        $data['titre'] = "Liste de tous les scenarios";
                        $data['message2'] = 'Bravo ! Formulaire rempli, le scenario a été bien ajouté .';
   
                        return  view('templates/haut2', $data)
                            . view('menu_organisateur')
                            . view('ajouter_scenario')
                            . view('templates/bas2');
                    }
                }
            } else {
                return view('templates/haut2', ['titre' => 'Créer un scenario'])
                    . view('menu_organisateur')
                    . view('ajouter_scenario')
                    . view('templates/bas2');
            }
        }
    }

    // L’utilisateur veut afficher le formulaire pour créer un compte
    return view('templates/haut', ['titre' => 'Se connecter'])
        . view('menu_visiteur')
        . view('connexion/compte_connecter')
        . view('templates/bas');
}















// public function ajouter_scenario()
// {
//     helper('form');
//     $session = session();

//     if ($session->has('user')) {
//         $model = model(Db_model::class);
//         $username = $_SESSION['user'];
//         $user_info = $model->get_user_info($session->get('user'));

//         if ($user_info['cmp_role'] === 'O') {
//             if ($this->request->getMethod() == "post") {
//                 $validationRules = [
//                     'intitule' => 'required|max_length[255]|min_length[5]',
//                     'fichier' => [
//                         'label' => 'Fichier image',
//                         'rules' => 'uploaded[fichier]|is_image[fichier]|mime_in[fichier,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[fichier,1000]|max_dims[fichier,1024,768]'
//                     ]
//                 ];

//                 if (!$this->validate($validationRules)) {
//                     // La validation du formulaire a échoué, retour au formulaire !
//                     return view('templates/haut2', ['titre' => 'Créer un nouveau scenario ERREUR DE FORMULAIRE'])
//                         . view('menu_organisateur')
//                         . view('ajouter_scenario')
//                         . view('templates/bas2');
//                 }
//                 $intitule = $this->request->getPost('intitule');
//                 $etat = 'A'; // État par défaut
//                 $fichier = $this->request->getFile('fichier');
//                 // Génération automatique du mot de passe
//                 // $mot_de_passe = $model->generate_password();

//                 if (!empty($fichier)) {
//                     // Récupération du nom du fichier téléversé
//                     $nom_fichier = $fichier->getName();

//                     // Dépôt du fichier dans le répertoire ci/public/images
//                     if ($fichier->move("ressources", $nom_fichier)) {
//                         // Récupération du cmp_id à partir de la session
//                         $cmp_id = $session->get('user_id'); // Assurez-vous que la clé de session est correcte

//                         $model->add_scenario($intitule, $nom_fichier, $etat, $cmp_id);
//                         $data['titre'] = "Liste de tous les scenarios";
//                         $data['logins'] = $model->get_all_scenario();

//                         return  view('templates/haut2', $data)
//                             . view('menu_organisateur')
//                             . view('affichage_accueil')
//                             . view('templates/bas2');
//                     }
//                 }
//             } else {
//                 return view('templates/haut2', ['titre' => 'Créer un scenario'])
//                     . view('menu_organisateur')
//                     . view('ajouter_scenario')
//                     . view('templates/bas2');
//             }
//         }
//     }

//     // L’utilisateur veut afficher le formulaire pour créer un compte
//     return view('templates/haut2', ['titre' => 'Se connecter'])
//         . view('connexion/compte_connecter')
//         . view('templates/bas2');
// }














}
?>
