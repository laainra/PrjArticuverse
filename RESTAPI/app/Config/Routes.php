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
$routes->match(['get', 'options'], 'materials-category/(:any)', 'MaterialController::getMaterialsByCategory/$1');
$routes->match(['get', 'options'], 'search-materials', 'MaterialController::searchMaterials');

//artwork
$routes->match(['post', 'options'], 'insert-artwork', 'ArtworkController::create');
$routes->match(['delete', 'options'], 'delete-artwork/(:any)', 'ArtworkController::deleteArtwork/$1');
$routes->match(['put', 'options'], 'update-artwork/(:any)', 'ArtworkController::updateArtwork/$1');
$routes->match(['get', 'options'], 'artwork/(:any)', 'ArtworkController::showArtworkById/$1');
$routes->match(['get', 'options'], 'artwork', 'ArtworkController::index');
$routes->match(['post', 'options'], 'search-artwork', 'ArtworkController::searchArtwork');
$routes->match(['get', 'options'], 'artworks-user/(:any)', 'ArtworkController::getArtworksByUserId/$1');
$routes->match(['post', 'options'],'like-artwork/(:num)', 'LikeController::likeArtwork/$1'); 
$routes->match(['get', 'options'],'get-likes/(:num)', 'LikeController::getLikesByArtwork/$1'); 
$routes->match(['post', 'options'],'save-artwork/(:num)', 'SavedController::saveArtwork/$1');
$routes->match(['get', 'options'],'saved-artworks/(:num)', 'SavedController::getArtworksByUserId/$1');
$routes->match(['get', 'options'],'check-like/(:segment)/(:segment)', 'ArtworkController::checkLike/$1/$2');
$routes->match(['get', 'options'],'genre-artworks/(:num)', 'ArtworkController::getArtworksByGenre/$1');

$routes->group("api", function ($routes) {
    $routes->match(['post', 'options'],"register", "Auth::register");
    $routes->match(['post', 'options'],"login", "Auth::login");
    $routes->match(['get', 'options'],"users", "UserController::index", ['filter' => 'authFilter']);
    $routes->match(['get', 'options'],'user', 'UserController::getUserById');
    $routes->match(['post', 'options'],'update-profile', 'UserController::updateProfile');
});

$routes->get('uploads/(:any)', 'MediaController::index/$1');



//support
$routes->match(['post', 'options'], 'insert-support', 'CommissionController::create');
$routes->match(['get', 'options'], 'support', 'CommissionController::index');
$routes->match(['put', 'options'], 'validate-commission/(:any)', 'CommissionController::validateCommission/$1');
$routes->match(['put', 'options'], 'unvalidate-commission/(:any)', 'CommissionController::unvalidateCommission/$1');
$routes->match(['delete', 'options'], 'delete-commission/(:any)', 'CommissionController::deleteCommission/$1');
$routes->match(['get', 'options'], 'user-commission/(:any)', 'CommissionController::userCommission/$1');

//comments
$routes->match(['get', 'options'],'getComments/(:num)', 'CommentController::getComments/$1');
$routes->match(['post', 'options'],'createComment', 'CommentController::createComment');
$routes->match(['put', 'options'],'updateComment/(:num)', 'CommentController::updateComment/$1');
$routes->match(['delete', 'options'],'deleteComment/(:num)', 'CommentController::deleteComment/$1');
