<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('mahasiswa', ['controller' => 'MahasiswaController']);
$routes->resource('user', ['controller' => 'UserController']);
$routes->resource('artwork', ['controller' => 'ArtworkController']);
$routes->resource('material', ['controller' => 'MaterialController']);
$routes->resource('exhibition', ['controller' => 'ExhibitionController']);
$routes->resource('category', ['controller' => 'CategoryController']);
$routes->resource('genre', ['controller' => 'GenreController']);

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

$routes->group("api", function ($routes) {
    $routes->match(['post', 'options'],"register", "Auth::register");
    $routes->match(['post', 'options'],"login", "Auth::login");
    $routes->match(['get', 'options'],"users", "UserController::index", ['filter' => 'authFilter']);
    $routes->get('user/(:num)', 'UserController::getUserById/$1');
});