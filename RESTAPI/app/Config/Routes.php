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

//exhibition
$routes->match(['post', 'options'], 'insert-exhibition', 'ExhibitionController::create');
$routes->match(['delete', 'options'], 'delete-exhibition', 'ExhibitionController::delete');
$routes->match(['put', 'options'], 'update-exhibition/(:any)', 'ExhibitionController::update/$1');
