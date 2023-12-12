<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;
class Compte extends BaseController
{
public function __construct()
{
    helper('form');
    $this->model = model(Db_model::class);
}
public function lister()
    {
        if (!session()->has('user')) {
            return redirect()->to(base_url('index.php/compte/connecter'));
        }
        $model = model(Db_model::class);
        $data['titre'] = "Liste de tous les comptes";
        $data['count'] = $model->count_comptes();
        $data['logins'] = $model->get_all_compte();
        return view('templates/haut2', $data)
. view('menu_administrateur')
. view('affichage_comptes')
. view('templates/bas2');
    
}


public function creer()
{
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }
    $model = model(Db_model::class);

    // L’utilisateur a validé le formulaire en cliquant sur le bouton
    if ($this->request->getMethod() == "post") {
        if (!$this->validate([
            'pseudo' => 'required|max_length[255]|min_length[2]|is_unique[t_compte_cmp.cmp_login]',
            'mdp' => 'required|max_length[255]|min_length[8]',
            'conf_mdp' => 'matches[mdp]',
            'nom' => 'required|alpha_space',
            'prenom' => 'required|alpha_space',
            'role' => 'required|in_list[A,O]',
            'etat' => 'required|in_list[A,D]',
        ], [
            'pseudo' => [
                'required' => 'Le champ Pseudo est requis.',
                'max_length' => 'Le Pseudo est trop long.',
                'min_length' => 'Le Pseudo est trop court.',
                'is_unique' => 'Ce Pseudo existe déjà. Veuillez en choisir un autre.',
            ],
            'mdp' => [
                'required' => 'Le champ Mot de passe est requis.',
                'max_length' => 'Le Mot de passe est trop long.',
                'min_length' => 'Le Mot de passe est trop court.',
            ],
            'conf_mdp' => [
                'matches' => 'La confirmation du Mot de passe ne correspond pas au Mot de passe saisi.',
            ],
            'nom' => [
                'required' => 'Le champ Nom est requis.',
                'alpha_space' => 'Le Nom ne doit contenir que des caractères alphabétiques et des espaces.',
            ],
            'prenom' => [
                'required' => 'Le champ Prénom est requis.',
                'alpha_space' => 'Le Prénom ne doit contenir que des caractères alphabétiques et des espaces.',
            ],
            'role' => [
                'required' => 'Le champ Rôle est requis.',
                'in_list' => 'Le Rôle doit être soit A (Administrateur) ou O (Organisateur).',
            ],
            'etat' => [
                'required' => 'Le champ État est requis.',
                'in_list' => 'L\'État doit être soit A (Activé) ou D (Désactivé).',
            ],
        ])) {
            // La validation du formulaire a échoué, retour au formulaire !
            return view('templates/haut2', ['titre' => 'Créer un compte'])
                . view('menu_administrateur')
                . view('compte/compte_creer')
                . view('templates/bas2');
        }

        // La validation du formulaire a réussi, traitement du formulaire
        $recuperation = $this->validator->getValidated();
        $model->set_compte($recuperation);
        $data['le_compte'] = $recuperation['pseudo'];
        $data['le_message'] = "Nouveau nombre de comptes : ";
        // Appel de la fonction créée dans le précédent tutoriel :
        $data['le_total'] = $model->count_comptes();

        return view('templates/haut2', $data)
            . view('menu_administrateur')
            . view('compte/compte_succes')
            . view('templates/bas2');
    }

    // L’utilisateur veut afficher le formulaire pour créer un compte
    return view('templates/haut2', ['titre' => 'Créer un compte'])
        . view('menu_administrateur')
        . view('compte/compte_creer')
        . view('templates/bas2');
}


public function connecter()
{
    $model = model(Db_model::class);

    if ($this->request->getMethod() == "post") {
        if (!$this->validate([
            'pseudo' => 'required',
            'mdp' => 'required'
        ])) {
            // Validation échouée, affichez le formulaire de connexion
            return view('templates/haut', ['titre' => 'Se connecter'])
                . view('menu_visiteur')
                . view('connexion/compte_connecter')
                . view('templates/bas');
        }

        $username = $this->request->getVar('pseudo');
        $password = hash('sha256', $this->request->getVar('mdp'));

        $user_info = $model->connect_compte($username, $password);

        if ($user_info && $user_info->cmp_etat === 'A' && ($user_info->cmp_role === 'A' || $user_info->cmp_role === 'O')) {
            // Connexion réussie, définissez les variables de session
            $session = session();
            $session->set('user', $username);
            $session->set('user_info', $user_info);

            // Ajoutez le rôle sous forme de texte à la variable de session
            $session->set('user_role', ($user_info->cmp_role === 'A') ? 'Administrateur' : 'Organisateur');

            // Déterminez le type de menu en fonction du rôle et affichez la vue correspondante
            if ($user_info->cmp_role === 'A') {
                return view('templates/haut2')
                    . view('menu_administrateur')
                    . view('connexion/compte_accueil')
                    . view('templates/bas2');
            } elseif ($user_info->cmp_role === 'O') {
                return view('templates/haut2')
                    . view('menu_organisateur')
                    . view('connexion/compte_accueil')
                    . view('templates/bas2');
            }
        } else {
            // Login failed, set flash message for error
            session()->setFlashdata('error', 'Pseudo ou mot de passe incorrect.');
        }
    }

    // Display the login form with or without the error message
    return view('templates/haut', ['titre' => 'Se connecter'])
        . view('menu_visiteur')
        . view('connexion/compte_connecter')
        . view('templates/bas');
}










public function afficher_profil()
{
    $session = session();
    if ($session->has('user')) {
        $model = model(Db_model::class);

        // Utilisez la fonction get_user_info du modèle pour obtenir les informations du profil
        $user_info = $model->get_user_info($session->get('user'));

        $data['le_message'] = "Affichage des données du profil ici !!!";
        $data['user_info'] = $user_info; // Utilisez les informations du profil

        // Vérifiez le rôle de l'utilisateur pour déterminer le menu à afficher
        if ($user_info['cmp_role'] === 'A') {
            return view('templates/haut2', $data)
                 . view('menu_administrateur')
                . view('connexion/compte_profil')
                . view('templates/bas2');
        } elseif ($user_info['cmp_role'] === 'O') {
            return view('templates/haut2', $data)
                 . view('menu_organisateur')
                . view('connexion/compte_profil')
                . view('templates/bas2');
        } else {
            // Gestion d'autres rôles si nécessaire
            return view('templates/haut2', $data)
                 . view('menu_visiteur')
                . view('connexion/compte_profil')
                . view('templates/bas2');
        }
    } else {
        return view('templates/haut', ['titre' => 'Se connecter'])
            . view('menu_visiteur')
            . view('connexion/compte_connecter')
            . view('templates/bas');
    }
}

// Compte.php
public function modifier_mot_de_passe()
{
    if (!session()->has('user')) {
        return redirect()->to(base_url('index.php/compte/connecter'));
    }
    $session = session();
    $data = [];


    // Récupérez les informations du profil
    $model = model(Db_model::class);
    $user_info = $model->get_user_info($session->get('user'));

    // Vérifiez si les informations de l'utilisateur ont été récupérées avec succès
    if (!$user_info) {
        // Gérer le cas où les informations de l'utilisateur ne sont pas disponibles
        $data['message'] = 'Impossible de récupérer les informations de l\'utilisateur';
    } else {
        // Passez les informations du profil à la vue
        $data['user_info'] = $user_info;

        // Traitement du formulaire de modification du mot de passe
        if ($this->request->getMethod() === 'post') {
            $new_password = $this->request->getVar('new_password');
            $confirm_password = $this->request->getVar('confirm_password');

            // Validation du formulaire
            if ($new_password !== $confirm_password) {
                $data['message'] = 'Les mots de passe ne correspondent pas.';
            } else {
                $username = $session->get('user');

                // Appel de la méthode pour mettre à jour le mot de passe
                $result = $model->update_mot_de_passe($username, $new_password);

                if ($result) {
                    $data['message'] = 'Mot de passe mis à jour avec succès.';
                    return redirect()->to(base_url('index.php/compte/afficher_profil'));

                } else {
                    $data['message'] = 'Échec de la mise à jour du mot de passe.';
                }
            }
        }
    }

    // Vérifiez le rôle de l'utilisateur pour déterminer le menu à afficher
    if ($session->get('user_info')->cmp_role === 'A') {
        $menu_view = 'menu_administrateur';
    } elseif ($session->get('user_info')->cmp_role === 'O') {
        $menu_view = 'menu_organisateur';
    } else {
        // Gestion d'autres rôles si nécessaire
        $menu_view = 'menu_visiteur';
    }

    // Afficher le formulaire de modification du mot de passe avec les informations du profil
    return view('templates/haut2', ['titre' => 'Modifier le mot de passe'])
        . view($menu_view)
        . view('connexion/modifier_mot_de_passe', $data)
        . view('templates/bas2');
}






    public function deconnecter()
    {
    $session=session();
    $session->destroy();
    return view('templates/haut', ['titre' => 'Se connecter']) 
    . view('menu_visiteur')
    . view('connexion/compte_connecter')
    . view('templates/bas');
    }







}
?>