<?php
require_once __DIR__ . '/../config/bootstrap.php';
use App\Core\Router;

$router = new Router();

$router->get('/',             'HomeController@index');
$router->get('/noticias',     'NewsController@index');
$router->get('/noticia',      'NewsController@show');
$router->get('/argentina',    'ArgentinaController@index');
$router->get('/europa',       'EuropaController@index');
$router->get('/mundial',      'MundialController@index');
$router->get('/login',        'AuthController@login');
$router->post('/login',       'AuthController@authenticate');
$router->get('/logout',       'AuthController@logout');
$router->get('/admin',        'DashboardController@index');
$router->post('/admin/scrape','DashboardController@scrape');

$router->dispatch();
