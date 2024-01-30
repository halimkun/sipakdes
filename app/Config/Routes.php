<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// Admin
$routes->get('/admin', "Dashboard::toIndex");
$routes->get('/admin/dashboard', "Dashboard::index");
