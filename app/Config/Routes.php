<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. THE TRAFFIC COP (Root URL)
// Checks if logged in. If yes -> Dashboard. If no -> Landing Page.
$routes->get('/', 'Index::index');

// 2. THE DASHBOARD (Logged In Users Only)
// Checks User Role (ITSO, Associate, Student) and shows specific view.
$routes->get('dashboard', 'Dashboard::index');


// --- EQUIPMENT MANAGEMENT ROUTES ---
$routes->get('equipment', 'Equipment::index');
$routes->get('equipment/add', 'Equipment::add');
$routes->post('equipment/insert', 'Equipment::insert');
// We use (:segment) to allow strings like "ITSO-LAP-001"
$routes->get('equipment/view/(:segment)', 'Equipment::view/$1');
$routes->get('equipment/edit/(:segment)', 'Equipment::edit/$1');
$routes->post('equipment/update/(:segment)', 'Equipment::update/$1');
$routes->get('equipment/deactivate/(:segment)', 'Equipment::deactivate/$1');


// --- USER MANAGEMENT ROUTES ---
$routes->get('users', 'Users::index');
$routes->get('users/add', 'Users::add');
$routes->post('users/insert', 'Users::insert');
$routes->get('users/view/(:any)', 'Users::view/$1'); // Changed (:num) to (:any) just in case school_id has letters
$routes->get('users/edit/(:any)', 'Users::edit/$1');
$routes->post('users/update/(:any)', 'Users::update/$1');
$routes->get('users/delete/(:any)', 'Users::delete/$1');


// --- AUTHENTICATION ROUTES ---
$routes->get('login', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::loginSubmit');

$routes->get('auth/signup', 'Auth::signup');
$routes->post('auth/signup', 'Auth::signupSubmit'); // Handles the actual registration logic

$routes->get('auth/verify/(:any)', 'Auth::verify/$1');

// Single Logout Route (Removed duplicate Users::logout to avoid confusion)
$routes->get('logout', 'Auth::logout');

//borrow and return
$routes->get('transaction', 'Transaction::borrow');
$routes->get('transaction/borrow', 'Transaction::borrow');

$routes->post('transaction/submitBorrow', 'Transaction::submitBorrow');

$routes->get('transaction', 'Transaction::returnList');
$routes->get('transaction/returnList', 'Transaction::returnList');
$routes->post('transaction/returnEquipment/(:num)', 'Transaction::returnEquipment/$1');