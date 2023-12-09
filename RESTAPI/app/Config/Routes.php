<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('mahasiswa', ['controller' => 'MahasiswaController']);
$routes->resource('user', ['controller' => 'UserController']);
// $routes->resource('artwork', ['controller' => 'ArtworkController']);
$routes->resource('material', ['controller' => 'MaterialController']);
$routes->resource('exhibition', ['controller' => 'ExhibitionController']);
$routes->resource('category', ['controller' => 'CategoryController']);
$routes->resource('genre', ['controller' => 'GenreController']);
$routes->match(['get', 'options'], 'material/(:segment)', 'MaterialController::show/$1');
$routes->match(['get', 'options'], 'materials', 'MaterialController::index');

$routes->match(['post', 'options'], 'auth/register', 'Auth::register');
$routes->match(['post', 'options'], 'auth/login', 'Auth::login');
$routes->match(['delete', 'options'], 'delete-user/(:any)', 'UserController::delete/$1');

//exhibition
$routes->match(['post', 'options'], 'insert-exhibition', 'ExhibitionController::create');
$routes->match(['delete', 'options'], 'delete-exhibition/(:any)', 'ExhibitionController::deleteExhibition/$1');
$routes->match(['put', 'options'], 'update-exhibition/(:any)', 'ExhibitionController::updateExhibition/$1');

//material
$routes->match(['post', 'options'], 'insert-material', 'MaterialController::create');
$routes->match(['delete', 'options'], 'delete-material/(:any)', 'MaterialController::deleteMaterial/$1');
$routes->match(['put', 'options'], 'update-material/(:any)', 'MaterialController::updateMaterial/$1');

//artwork
$routes->match(['post', 'options'], 'insert-artwork', 'ArtworkController::create');
$routes->match(['delete', 'options'], 'delete-artwork/(:any)', 'ArtworkController::deleteArtwork/$1');
$routes->match(['put', 'options'], 'update-artwork/(:any)', 'ArtworkController::updateArtwork/$1');
$routes->match(['get', 'options'], 'artwork/(:any)', 'ArtworkController::showArtworkById/$1');
$routes->match(['get', 'options'], 'artwork', 'ArtworkController::index');
$routes->match(['post', 'options'], 'search-artwork', 'ArtworkController::searchArtwork');
$routes->match(['get', 'options'], 'artworks-user/(:any)', 'ArtworkController::getArtworksByUserId/$1');

$routes->group("api", function ($routes) {
    $routes->match(['post', 'options'],"register", "Auth::register");
    $routes->match(['post', 'options'],"login", "Auth::login");
    $routes->match(['get', 'options'],"users", "UserController::index", ['filter' => 'authFilter']);
    $routes->match(['get', 'options'],'user', 'UserController::getUserById');
    $routes->match(['post', 'options'],'update-profile', 'UserController::updateProfile');
});

$routes->get('uploads/(:any)', 'MediaController::index/$1');


