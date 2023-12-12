<?php

namespace App\Models;

use CodeIgniter\Model;

class Db_model extends Model
{
    protected $db;

    public function __construct()
    {
        // Initialise la connexion à la base de données
        $this->db = db_connect();
    }

    // Exemple de méthode pour récupérer tous les comptes
    public function get_all_compte()
    {
        // Exécute une requête SQL pour récupérer certaines colonnes de la table t_compte_cmp
        $resultat = $this->db->query("SELECT cmp_login, cmp_nom, cmp_prenom, cmp_role, cmp_etat FROM t_compte_cmp ORDER BY cmp_etat;");
        
        // Retourne les résultats sous forme de tableau associatif
        return $resultat->getResultArray();
    }

    // Exemple de méthode pour récupérer une actualité par son numéro
    public function get_actualite($numero)
    {
        // Construction de la requête SQL avec un paramètre
        $requete = "SELECT * FROM t_actualite_act WHERE act_id = ?;";
        
        // Exécute la requête préparée avec le paramètre correspondant
        $resultat = $this->db->query($requete, [$numero]);
        
        // Retourne la première ligne du résultat
        return $resultat->getRow();
    }

    // Exemple de méthode pour compter le nombre total de comptes
    public function count_comptes()
    {
        // Exécute une requête SQL pour compter le nombre total de lignes dans la table t_compte_cmp
        $resultat = $this->db->query("SELECT COUNT(*) as total FROM t_compte_cmp;");
        
        // Récupère la première ligne du résultat
        $row = $resultat->getRow();
        
        // Retourne la valeur du champ 'total'
        return $row->total;
    }

    // Exemple de méthode pour récupérer toutes les actualités avec des informations supplémentaires
    public function get_all_actualites()
    {
        // Exécute une requête SQL avec des jointures pour récupérer des informations supplémentaires
        $resultat = $this->db->query("SELECT act_id, act_intitule, act_description, cmp_nom AS Auteur, act_date, act_etat
                                      FROM t_actualite_act
                                      JOIN t_compte_cmp USING (cmp_id)
                                      JOIN t_scenario_scn ON t_actualite_act.cmp_id = t_scenario_scn.cmp_id;");
        
        // Retourne les résultats
        return $resultat->getResult();
    }

    // Exemple de méthode pour récupérer tous les scénarios avec des informations spécifiques
    public function get_all_scenarios()
    {
        // Exécute une requête SQL avec des jointures pour récupérer des informations spécifiques
        $resultat = $this->db->query("SELECT DISTINCT scn_intitule AS Intitule,
                                        cmp_login AS Auteur,
                                        scn_image AS img,
                                        scn_code AS code,
                                        reu_niveau AS niveau
                                        FROM t_scenario_scn
                                        LEFT JOIN t_compte_cmp ON t_scenario_scn.cmp_id = t_compte_cmp.cmp_id
                                        LEFT JOIN t_reussite_reu ON t_scenario_scn.scn_id = t_reussite_reu.scn_id
                                        WHERE scn_etat = 'A';");
        
        // Retourne les résultats sous forme de tableau associatif
        return $resultat->getResultArray();
    }

    // Exemple de méthode pour récupérer les détails de la première étape d'un scénario
    public function get_scenario_eta($code, $nv)
    {
        // Construction de la requête SQL avec des jointures et des conditions spécifiques
        $requete = "SELECT
                        eta_intitule AS IntituleEtape,
                        ind_description AS Indice,
                        ind_lien AS IndiceLien,
                        eta_question AS ques
                    FROM
                        t_etape_eta
                    LEFT JOIN t_scenario_scn ON t_scenario_scn.scn_id = t_etape_eta.scn_id
                    LEFT JOIN t_indice_ind ON t_indice_ind.eta_id = t_etape_eta.eta_id AND ind_niveau = '$nv'
                    WHERE
                        scn_code = '$code' AND eta_num = 1;";
        
        // Exécute la requête
        $resultat = $this->db->query($requete);
        
        // Retourne la première ligne du résultat
        return $resultat->getRow();
    }

    // Exemple de méthode pour récupérer les détails d'une étape par son code
    public function get_etape_details($code, $nv)
    {
        // Construction de la requête SQL avec des jointures et des conditions spécifiques
        $requete = "SELECT
                        eta_intitule AS IntituleEtape,
                        ind_description AS Indice,
                        ind_lien AS IndiceLien,
                        eta_question AS ques
                    FROM
                        t_etape_eta
                    LEFT JOIN t_indice_ind ON t_indice_ind.eta_id = t_etape_eta.eta_id AND ind_niveau = '$nv'
                    WHERE
                        eta_code = ? 
                    LIMIT 1;";
        
        // Exécute la requête préparée avec le paramètre correspondant
        $resultat = $this->db->query($requete, [$code]);
        
        // Retourne la première ligne du résultat
        return $resultat->getRow();
    }

    // Exemple de méthode pour obtenir le nombre total d'étapes pour une étape donnée
    public function get_total_etapes($code_etape)
    {
        // Utilise le code de l'étape pour obtenir le nombre total d'étapes du scénario
        $requete = "SELECT COUNT(*) AS total_etapes
                    FROM t_etape_eta
                    WHERE scn_id = (SELECT scn_id FROM t_etape_eta WHERE eta_code = ? LIMIT 1);";
        
        // Exécute la requête préparée avec le paramètre correspondant
        $resultat = $this->db->query($requete, [$code_etape]);
        $row = $resultat->getRow();
        
        // Vérifie si la valeur du champ 'total_etapes' est disponible
        if ($row && !empty($row->total_etapes)) {
            return $row->total_etapes;
        }

        // Retourne 0 si la valeur n'est pas disponible
        return 0;
    }

// Méthode pour obtenir la bonne réponse d'une étape donnée en fonction du code de scénario
public function get_bonne_reponse($code)
{
    $requete = "SELECT eta_reponse 
                FROM t_etape_eta 
                LEFT JOIN t_scenario_scn ON t_scenario_scn.scn_id = t_etape_eta.scn_id
                WHERE scn_code = ? 
                LIMIT 1;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete, [$code]);
    $row = $resultat->getRow();

    // Vérifier si une réponse a été trouvée
    if ($row) {
        return $row->eta_reponse;
    }

    return null; // Aucune réponse trouvée
}

// Autre méthode pour obtenir la bonne réponse en fonction du code d'étape uniquement
public function get_bonne_reponse2($code)
{
    $requete = "SELECT eta_reponse 
                FROM t_etape_eta 
                WHERE eta_code = ? 
                LIMIT 1;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete, [$code]);
    $row = $resultat->getRow();

    // Vérifier si une réponse a été trouvée
    if ($row) {
        return $row->eta_reponse;
    }

    return null; // Aucune réponse trouvée
}

// Méthode pour obtenir le numéro de la prochaine étape en fonction du code de scénario
public function get_prochaine_etape_num($code)
{
    $requete = "SELECT MIN(eta_num) AS prochaine_etape 
                FROM t_etape_eta
                LEFT JOIN t_scenario_scn ON t_scenario_scn.scn_id = t_etape_eta.scn_id
                WHERE scn_code = ?;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete, [$code]);
    $row = $resultat->getRow();

    // Vérifier si le numéro de la prochaine étape a été trouvé
    if ($row && !empty($row->prochaine_etape)) {
        return $row->prochaine_etape + 1; // Incrémentez pour obtenir la prochaine étape
    }

    return null; // Aucune prochaine étape trouvée
}

// Autre méthode pour obtenir le numéro de la prochaine étape en fonction du code d'étape uniquement
public function get_prochaine_etape_num2($code)
{
    $requete = "SELECT MIN(eta_num) AS prochaine_etape 
                FROM t_etape_eta
                WHERE eta_code = ?;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete, [$code]);
    $row = $resultat->getRow();

    // Vérifier si le numéro de la prochaine étape a été trouvé
    if ($row && !empty($row->prochaine_etape)) {
        return $row->prochaine_etape + 1; // Incrémentez pour obtenir la prochaine étape
    }

    return null; // Aucune prochaine étape trouvée
}

// Méthode pour obtenir le code de scénario en fonction du code d'étape
public function get_code_scenario($code)
{
    $requete = "SELECT scn_code
                FROM t_scenario_scn 
                LEFT JOIN t_etape_eta ON t_etape_eta.scn_id = t_scenario_scn.scn_id
                WHERE eta_code = ?;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete, [$code]);
    $row = $resultat->getRow();

    // Vérifier si le code de scénario a été trouvé
    if ($row) {
        return $row->scn_code;
    }

    return null; // Aucun code de scénario trouvé
}

// Méthode pour obtenir le code de la prochaine étape en fonction du numéro et du code de scénario
public function get_prochaine_etape_code($num, $code)
{
    $requete = "SELECT eta_code 
                FROM t_etape_eta
                JOIN t_scenario_scn ON t_etape_eta.scn_id = t_scenario_scn.scn_id
                WHERE t_etape_eta.eta_num = '$num'  AND t_scenario_scn.scn_code = '$code';";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete);
    $row = $resultat->getRow();

    // Vérifier si le code de la prochaine étape a été trouvé
    if ($row) {
        return $row->eta_code;
    }

    return null; // Aucun code de la prochaine étape trouvé
}

// Autre méthode pour obtenir le code de la prochaine étape en fonction du numéro et du code d'étape uniquement
public function get_prochaine_etape_code2($num, $code_etape)
{
    $requete = "SELECT eta_code 
                FROM t_etape_eta
                JOIN t_scenario_scn ON t_etape_eta.scn_id = t_scenario_scn.scn_id
                WHERE t_etape_eta.eta_num = '$num' AND t_scenario_scn.scn_code = '$code_etape' ;";

    // Exécution de la requête SQL
    $resultat = $this->db->query($requete);
    $row = $resultat->getRow();

    // Vérifier si le code de la prochaine étape a été trouvé
    if ($row) {
        return $row->eta_code;
    }

    return null; // Aucun code de la prochaine étape trouvé
}

// Méthode pour enregistrer l'e-mail d'un participant
public function enregistrer_email_participant($email)
{
    $sql = "INSERT INTO t_participant_par (par_adresse) VALUES (?);";

    // Exécution de la requête SQL
    $result = $this->db->query($sql, [$email]);
    return $result;
}
    // Méthode pour obtenir l'ID d'un scénario en fonction de son code
    public function get_scenario_id($code)
    {
        $requete = "SELECT scn_id
                    FROM t_scenario_scn
                    WHERE scn_code = '$code';";
        $resultat = $this->db->query($requete);
        $row = $resultat->getRow();

        // Vérifier si l'ID du scénario a été trouvé
        if ($row) {
            return $row->scn_id;
        }

        return null; // Aucun ID de scénario trouvé
    }

    // Méthode pour enregistrer la réussite d'un participant pour un scénario
    public function enregistrer_reussite($id, $nv, $email)
    {
        // Vérifier si $email n'est pas nul
        if ($email !== null) {
            $sql = "INSERT INTO t_reussite_reu (reu_datepremiere, reu_datederniere, reu_niveau, scn_id, par_adresse) VALUES (NOW(), NULL, ?, ?, ?);";
            $result = $this->db->query($sql, [$nv, $id, $email]);
            return $result;
        } else {
            // Gérer le cas où $email est nul
            return false;
        }
    }

    // Méthode pour enregistrer un compte utilisateur
    public function set_compte($saisie)
    {
        // Récupération des données du formulaire
        $login = $saisie['pseudo'];
        $mot_de_passe = $saisie['mdp'];
        $nom = $saisie['nom'];
        $prenom = $saisie['prenom'];
        $role = $saisie['role'];
        $etat = $saisie['etat'];
        $confirmation_mot_de_passe = $saisie['conf_mdp'];

        // Vérification des champs obligatoires
        if (empty($login) || empty($mot_de_passe) || empty($nom) || empty($prenom) || empty($role) || empty($etat)) {
            return 'Tous les champs obligatoires doivent être remplis.';
        }

        // Vérification des caractères spéciaux dans le nom et le prénom
        if (!preg_match('/^[A-Za-z\s]+$/', $nom) || !preg_match('/^[A-Za-z\s]+$/', $prenom)) {
            return 'Le nom et le prénom ne doivent contenir que des caractères alphabétiques et des espaces.';
        }

        // Vérification si le mot de passe et la confirmation correspondent
        if ($mot_de_passe !== $confirmation_mot_de_passe) {
            return 'Le mot de passe et la confirmation ne correspondent pas.';
        }

        // Validation du mot de passe (vous pouvez ajouter d'autres règles selon vos besoins)
        if (strlen($mot_de_passe) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        // Hashage du mot de passe
        $mot_de_passe_hash = hash('sha256', $mot_de_passe);

        // Construction de la requête SQL avec les valeurs correspondantes
        $sql = "INSERT INTO t_compte_cmp (cmp_login, cmp_mp, cmp_nom, cmp_prenom, cmp_etat, cmp_role) VALUES (?, ?, ?, ?, ?, ?);";

        // Exécution de la requête préparée avec les valeurs correspondantes
        $result = $this->db->query($sql, [$login, $mot_de_passe_hash, $nom, $prenom, $etat, $role]);

        if ($result) {
            return true; // Compte ajouté avec succès
        } else {
            return 'Une erreur s\'est produite lors de l\'ajout du compte.';
        }
    }

    // Méthode pour connecter un compte utilisateur
    public function connect_compte($u, $p)
    {
        $sql = "SELECT cmp_login, cmp_mp, cmp_role, cmp_etat, cmp_nom, cmp_prenom
                FROM t_compte_cmp
                WHERE cmp_login = ? AND cmp_mp = ?;";
        
        // Exécution de la requête SQL
        $resultat = $this->db->query($sql, [$u, $p]);

        if ($resultat->getNumRows() > 0) {
            $row = $resultat->getRow();

            // Vérifie si le compte est actif (état = A)
            if ($row->cmp_etat === 'A') {
                // Ajoutez ici une vérification du rôle (Admin ou Organisateur)
                if ($row->cmp_role === 'A') {
                    $row->role_type = 'admin';
                } elseif ($row->cmp_role === 'O') {
                    $row->role_type = 'organisateur';
                }

                return $row; // Compte connecté avec succès
            }
        }

        return false; // Échec de la connexion du compte
    }

    // Méthode pour obtenir les informations d'un utilisateur en fonction de son nom d'utilisateur
    public function get_user_info($username)
    {
        $this->table = 't_compte_cmp';
        $requete = "SELECT cmp_login, cmp_mp, cmp_role, cmp_etat, cmp_nom, cmp_prenom
                    FROM t_compte_cmp
                    WHERE cmp_login = ?;";

        // Exécution de la requête SQL
        $resultat = $this->db->query($requete, [$username]);

        if ($resultat) {
            return $resultat->getRowArray(); // Retourne les informations de l'utilisateur
        } else {
            // Ajoutez des logs ou des messages temporaires pour déboguer en cas d'erreur
            $error = $this->db->error();
            log_message('error', 'Erreur de base de données: ' . print_r($error, true));
            return null;
        }
    }

    // Procédure stockée pour changer le mot de passe d'un utilisateur
    public function update_mot_de_passe($username, $new_password)
    {
        $result = $this->db->query("CALL changerMotDePasse(?, ?)", [$username, $new_password]);

        return $result;
    }

    // Méthode pour obtenir toutes les informations des scénarios sous forme de tableau
    public function get_all_scenarios_table()
    {
        $resultat = $this->db->query("SELECT scn_intitule AS Intitule,
            cmp_login AS Auteur,
            scn_image AS img,
            scn_code AS code,
            scn_etat AS etat,
            COUNT(eta_num) AS Nb_etape
            FROM t_scenario_scn
            LEFT JOIN t_compte_cmp ON t_scenario_scn.cmp_id = t_compte_cmp.cmp_id
            LEFT JOIN t_etape_eta ON t_scenario_scn.scn_id = t_etape_eta.scn_id
            GROUP BY t_scenario_scn.scn_id;");
        return $resultat->getResultArray();
    }

    // Méthode pour obtenir les détails d'un scénario en fonction de son code
    public function get_scenario_details($scenario_code)
    {
        $resultat = $this->db->query("SELECT * FROM t_scenario_scn WHERE scn_code = ?;", [$scenario_code]);
        return $resultat->getRowArray();
    }

    // Méthode pour obtenir les questions et réponses d'un scénario en fonction de son ID
    public function getQuestionsReponses($scn_id)
    {
        $resultat = $this->db->query("SELECT eta_question, eta_reponse FROM t_etape_eta WHERE scn_id = $scn_id;");
        return $resultat->getResultArray();
    }

public function activer_desactiver_scenario($scenario_code, $nouvel_etat)
{
    // Assurez-vous que $nouvel_etat est soit 'A' (activer) ou 'D' (désactiver)
    if ($nouvel_etat != 'A' && $nouvel_etat != 'D') {
        throw new \Exception("L'état du scénario doit être 'A' (activer) ou 'D' (désactiver).");
    }

    // Exécutez la requête SQL pour mettre à jour l'état du scénario
    $sql = "UPDATE t_scenario_scn SET scn_etat = ? WHERE scn_code = ?;";

    $this->db->transStart(); // Démarrez la transaction

    $this->db->query($sql, [$nouvel_etat, $scenario_code]);

    $this->db->transComplete(); // Terminez la transaction

    if ($this->db->transStatus() === false) {
        throw new \Exception("Une erreur s'est produite lors de l'activation/désactivation du scénario.");
    }

    return true; // Opération réussie
}

// Model: app/Models/Db_model.php

public function supprimer_scenario($code)
{
    $this->db->transStart(); // Démarrez la transaction

    // Suppression des étapes du scénario
    $this->db->query("DELETE FROM t_etape_eta WHERE scn_id IN (SELECT scn_id FROM t_scenario_scn WHERE scn_code = ?)", [$code]);

    // Suppression des réussites associées au scénario
    $this->db->query("DELETE FROM t_reussite_reu WHERE scn_id IN (SELECT scn_id FROM t_scenario_scn WHERE scn_code = ?)", [$code]);

    // Suppression du scénario lui-même
    $this->db->query("DELETE FROM t_scenario_scn WHERE scn_code = ?", [$code]);

    $this->db->transComplete(); // Terminez la transaction

    if ($this->db->transStatus() === false) {
        return false; // Échec de la transaction
    }

    return true; // Suppression réussie
}


    // Méthode pour ajouter un nouveau scénario
    public function add_scenario($intitule, $fichier, $etat, $id)
    {
        // Nettoyage de l'intitulé en évitant les attaques XSS
        $intitule = htmlspecialchars($intitule);

        // Génération d'un mot de passe unique de 8 caractères
        $mot_de_passe = substr(uniqid(), -8);

        // Requête SQL pour insérer un nouveau scénario dans la base de données
        $sql = "INSERT INTO t_scenario_scn (scn_id, scn_intitule, scn_etat, scn_image, scn_code, cmp_id) VALUES (NULL, ?, ?, ?, ?, ?)";
        
        // Exécution de la requête avec les valeurs correspondantes
        $this->db->query($sql, [$intitule, $etat, $fichier, $mot_de_passe, $id]);
        
        return true; // Succès de l'opération
    }

    // Méthode pour obtenir l'ID d'un utilisateur en fonction de son nom d'utilisateur
    public function get_id($username)
    {
        // Requête SQL pour obtenir l'ID d'un utilisateur en fonction de son nom d'utilisateur
        $sql = "SELECT cmp_id FROM t_compte_cmp WHERE cmp_login = ?";
        
        // Exécution de la requête
        $result = $this->db->query($sql, [$username])->getRow();

        if ($result) {
            return $result->cmp_id; // Retourne l'ID de l'utilisateur
        }

        return null; // Aucun résultat trouvé
    }

    // Fonction dans le modèle de base de données pour vérifier l'existence d'un scénario en fonction de son code
    public function scenarioExiste($scenario_code)
    {
        // Exécution d'une requête SQL utilisant une fonction personnalisée
        $result = $this->db->query("SELECT ExisteScenarioParCode('$scenario_code') AS scenario_existe")->getRow();
        
        return $result->scenario_existe; // Retourne le résultat de l'existence du scénario
    }
}
?>
