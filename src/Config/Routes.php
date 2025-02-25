<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->match(['get', 'post'], '/login', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::login', ["as" => "loginurl"]);
$routes->match(['get', 'post'], '/logout', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::logout', ["as" => "logouturl"]);
$routes->match(['get', 'post'], '/register', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::register', ["as" => "registerurl"]);
