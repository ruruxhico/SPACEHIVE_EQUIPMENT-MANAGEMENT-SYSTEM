<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//trial routes for Auth
// Routes for the Users controller
$routes->get('users', 'Users::index');
$routes->get('users/add', 'Users::add');
$routes->post('users/insert', 'Users::insert');
$routes->get('users/view/(:num)', 'Users::view/$1');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->post('users/delete/(:num)', 'Users::delete/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');


// Equipment Management Routes
$routes->get('equipment', 'Equipment::index');
$routes->get('equipment/add', 'Equipment::add');
$routes->post('equipment/insert', 'Equipment::insert');
$routes->get('equipment/view/(:segment)', 'Equipment::view/$1');
$routes->get('equipment/edit/(:segment)', 'Equipment::edit/$1');
$routes->post('equipment/update/(:segment)', 'Equipment::update/$1');
$routes->get('equipment/deactivate/(:segment)', 'Equipment::deactivate/$1');

//Routes for Auth
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::loginSubmit');
$routes->get('auth/signup', 'Auth::signup');
$routes->post('auth/signup', 'Auth::signupSubmit');
$routes->get('logout', 'Auth::logout');
$routes->get('logout', 'Users::logout');
$routes->get('dashboard', 'Auth::index');
$routes->get('auth/verify/(:any)', 'Auth::verify/$1');
