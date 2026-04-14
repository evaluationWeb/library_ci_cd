<?php

session_start();

include '../vendor/autoload.php';

use Dotenv\Dotenv;
use Mithridatem\Routing\Route;
use Mithridatem\Routing\Router;
use Mithridatem\Routing\Auth\ArrayGrantChecker;
use Mithridatem\Routing\Exception\RouteNotFoundException;
use Mithridatem\Routing\Exception\UnauthorizedException;

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

$router = new Router();

if (!isset($_SESSION["user"]["roles"])) {
    $_SESSION["user"]["roles"] = ['ROLE_PUBLIC'];
}

$roles = $_SESSION["user"]["roles"];
$router->setGrantChecker(new ArrayGrantChecker($roles));

$router->map(Route::controller('GET', '/', App\Controller\HomeController::class, 'index'));
$router->map(Route::controller('GET', '/login', App\Controller\RegisterController::class, 'login'));
$router->map(Route::controller('POST', '/login', App\Controller\RegisterController::class, 'login'));
$router->map(Route::controller('GET', '/register', App\Controller\RegisterController::class, 'register'));
$router->map(Route::controller('POST', '/register', App\Controller\RegisterController::class, 'register'));
$router->map(Route::controller('GET', '/logout', App\Controller\RegisterController::class, 'logout', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/profil', App\Controller\RegisterController::class, 'showProfil', ['ROLE_USER', 'ROLE_ADMIN']));

$router->map(Route::controller('GET', '/category/add', App\Controller\CategoryController::class, 'addCategory', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('POST', '/category/add', App\Controller\CategoryController::class, 'addCategory', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/category/all', App\Controller\CategoryController::class, 'showAllCategories', ['ROLE_USER', 'ROLE_ADMIN']));

$router->map(Route::controller('GET', '/book/add', App\Controller\BookController::class, 'addBook', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('POST', '/book/add', App\Controller\BookController::class, 'addBook', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/book/edit/{id}', App\Controller\BookController::class, 'editBook', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('POST', '/book/edit/{id}', App\Controller\BookController::class, 'editBook', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/book/delete/{id}', App\Controller\BookController::class, 'deleteBook', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/book/all', App\Controller\BookController::class, 'showAllBooks', ['ROLE_USER', 'ROLE_ADMIN']));

$router->map(Route::controller('GET', '/user/add', App\Controller\UserController::class, 'addUser', ['ROLE_ADMIN']));
$router->map(Route::controller('POST', '/user/add', App\Controller\UserController::class, 'addUser', ['ROLE_ADMIN']));
$router->map(Route::controller('GET', '/user/all', App\Controller\UserController::class, 'showAllUsers', ['ROLE_ADMIN']));

$router->map(Route::controller('GET', '/lending/add', App\Controller\LendingController::class, 'addLending', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('POST', '/lending/add', App\Controller\LendingController::class, 'addLending', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/lending/return/{id}', App\Controller\LendingController::class, 'returnLending', ['ROLE_USER', 'ROLE_ADMIN']));
$router->map(Route::controller('GET', '/lending/all', App\Controller\LendingController::class, 'showAllLendings', ['ROLE_USER', 'ROLE_ADMIN']));

try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    echo '404';
} catch (UnauthorizedException $e) {
    http_response_code(403);
    header('Location: /login');
    exit;
}
