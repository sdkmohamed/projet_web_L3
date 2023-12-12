<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Accueil::member');
use App\Controllers\Accueil;
$routes->get('accueil/afficher', [Accueil::class, 'afficher']);
use App\Controllers\Compte;
$routes->get('compte/lister', [Compte::class, 'lister']);

$routes->get('compte/creer', [Compte::class, 'creer']);
$routes->post('compte/creer', [Compte::class, 'creer']);


$routes->get('compte/connecter', [Compte::class, 'connecter']);
$routes->post('compte/connecter', [Compte::class, 'connecter']);


$routes->get('compte/deconnecter', [Compte::class, 'deconnecter']);
$routes->get('compte/afficher_profil', [Compte::class, 'afficher_profil']);
$routes->get('compte/modifier_mot_de_passe', [Compte::class, 'modifier_mot_de_passe']);
$routes->post('compte/modifier_mot_de_passe', [Compte::class, 'modifier_mot_de_passe']);



use App\Controllers\Actualite;
$routes->get('actualite/afficher', [Actualite::class, 'afficher']);
$routes->get('actualite/afficher/(:num)', [Actualite::class, 'afficher']);
$routes->get('accueil/member', [Accueil::class, 'member']);
use App\Controllers\Scenario;

$routes->get('scenario/lister', [Scenario::class, 'lister']);
$routes->match(['get', 'post'], 'scenario/afficherPremiereEtape/(:segment)/(:segment)', [Scenario::class, 'afficherPremiereEtape/$1/$2']);
$routes->get('scenario/afficherPremiereEtape/', [Scenario::class, 'lister']);
$routes->get('scenario/afficherPremiereEtape/(:segment)/', [Scenario::class, 'lister']);
$routes->get('scenario/accueil', [Scenario::class, 'accueil']);
$routes->get('scenario/visualiser/(:any)', [Scenario::class, 'visualiser/$1']);
$routes->get('scenario/activer_desactiver/(:segment)/(:alpha)', [Scenario::class, 'activer_desactiver/$1/$2']);
$routes->get('scenario/ajouter_scenario', [Scenario::class, 'ajouter_scenario']);
$routes->post('scenario/ajouter_scenario', [Scenario::class, 'ajouter_scenario']);
$routes->get('scenario/supprimer/(:segment)', [Scenario::class, 'supprimer/$1']);
$routes->match(['get', 'post'],'scenario/franchir_etape/(:segment)/(:segment)', [Scenario::class, 'franchir_etape/$1/$2']);
$routes->match(['get', 'post'],'scenario/franchir_etape/(:segment)', [Scenario::class, 'lister']);
$routes->match(['get', 'post'],'scenario/franchir_etape/', [Scenario::class, 'lister']);
$routes->get('scenario/fin_etape/(:segment)/(:segment)/(:segment)/(:segment)', 'Scenario::fin_etape/$1/$2/$3/$4');
$routes->post('scenario/fin_etape/(:segment)/(:segment)/(:segment)/(:segment)', 'Scenario::fin_etape/$1/$2/$3/$4');
$routes->match(['get', 'post'],'scenario/fin_etape/(:segment)', [Scenario::class, 'lister']);
$routes->match(['get', 'post'],'scenario/fin_etape/(:segment)/(:segment)', [Scenario::class, 'lister']);
$routes->match(['get', 'post'],'scenario/fin_etape/(:segment)/(:segment)/(:segment)', [Scenario::class, 'lister']);
$routes->match(['get', 'post'],'scenario/fin_etape/', [Scenario::class, 'lister']);









