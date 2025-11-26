<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//trial routes for Auth
//Routes for Auth
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::loginSubmit');
$routes->get('auth/signup', 'Auth::signup');
$routes->post('auth/signup', 'Auth::signupSubmit');
$routes->get('logout', 'Auth::logout');
$routes->get('logout', 'Users::logout');
$routes->get('dashboard', 'Auth::index');
$routes->get('auth/verify/(:any)', 'Auth::verify/$1');
