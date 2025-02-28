<?php

use CodeIgniter\Router\RouteCollection;

$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
$setUserLibRoutes = $config->setUserLibRoutes;

/**
 * Allows the user to disable the routes
 * This is useful if the user wants to change the urls, or override the controller used
 */
if ($setUserLibRoutes) {
	/**
	 * @var RouteCollection $routes
	 */
	$routes->match(['get', 'post'], '/login', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::login', ["as" => "loginurl"]);
	$routes->match(['get', 'post'], '/logout', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::logout', ["as" => "logouturl"]);
	$routes->match(['get', 'post'], '/register', '\Franky5831\CodeIgniter4UserLibrary\Controllers\User::register', ["as" => "registerurl"]);
}
