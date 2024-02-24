<?php

use CodeIgniter\Router\RouteCollection;
use Config\Auth as AuthConfig;

/**
 * @var RouteCollection $routes
 */

//  Default
$routes->get('/', 'Home::index');

// Dashboard
$routes->get('/dashboard', "Dashboard::index");

// Surat
$routes->group('/surat', ['namespace' => 'App\Controllers', 'filter' => 'role:admin,warga'], function ($routes) {
    $routes->group('kematian', function ($routes) {
        $routes->get('', "Kematian::index");
        $routes->get('new', "Kematian::new");

        $routes->post('store', "Kematian::store");
    });
});

// Pengguna
$routes->group('/pengguna', ['namespace' => 'App\Controllers', 'filter' => 'role:admin'], function ($routes) {
    $routes->get('', "Pengguna::index");
    $routes->get('new', "Pengguna::new");
    $routes->get('(:num)/edit', "Pengguna::edit/$1");
    $routes->get('(:num)/toggle', "Pengguna::toggle/$1");

    $routes->post('store', "Pengguna::store");
    $routes->post('(:num)/update', "Pengguna::update/$1");
    $routes->post('(:num)/change-role', "Pengguna::changeRole/$1");
    
    $routes->post('reset-password', "Pengguna::resetPassword");
});

// Penduduk
$routes->group('/penduduk', ['namespace' => 'App\Controllers', 'filter' => 'role:admin'], function ($routes) {
    $routes->get('', "Penduduk::index");
    $routes->get('new', "Penduduk::new");
    $routes->get('(:num)/edit', "Penduduk::edit/$1");
    $routes->get('(:num)/verify', "Penduduk::toggle_verified/$1");

    $routes->post('store', "Penduduk::store");
    $routes->post('upload-kk', "Penduduk::uploadKk");
    $routes->post('(:num)/update', "Penduduk::update/$1");
    $routes->post('(:num)/delete', "Penduduk::delete/$1");
});

// Keluarga
$routes->group('/keluarga', ['namespace' => 'App\Controllers', 'filter' => 'role:admin,warga'], function ($routes) {
    $routes->get('', "Keluarga::index");
    $routes->get('new', "Keluarga::new");
    $routes->get('(:num/edit)', "Keluarga::edit/$1");

    $routes->post('store', "Keluarga::store");
    $routes->post('upload-kk', "Keluarga::uploadKk");
    $routes->post('(:num)/update', "Keluarga::update/$1");
    $routes->post('(:num)/delete', "Keluarga::delete/$1");
});

// Profile
$routes->group('/profile', ['namespace' => 'App\Controllers', 'filter' => 'role:admin,warga'], function ($routes) {
    $routes->get('', "Profile::index");

    $routes->post('user-update', "Profile::userUpdate");
});

// settings
$routes->group('/settings', ['namespace' => 'App\Controllers', 'filter' => 'role:admin'], function ($routes) {
    $routes->get('', "Settings::index");
    $routes->post('update', "Settings::update");
    $routes->post('update-desa', "Settings::updateDesa");
});



















// --- ------------- Auth 
// Myth:Auth routes file.
$routes->group('', ['namespace' => 'App\Controllers'], static function ($routes) {
    // Load the reserved routes from Auth.php
    $config         = config(AuthConfig::class);
    $reservedRoutes = $config->reservedRoutes;

    // Login/out
    $routes->get($reservedRoutes['login'], 'AuthController::login', ['as' => $reservedRoutes['login']]);
    $routes->post($reservedRoutes['login'], 'AuthController::attemptLogin');
    $routes->get($reservedRoutes['logout'], 'AuthController::logout');

    // Registration
    $routes->get($reservedRoutes['register'], 'AuthController::register', ['as' => $reservedRoutes['register']]);
    $routes->post($reservedRoutes['register'], 'AuthController::attemptRegister');

    // Activation
    $routes->get($reservedRoutes['activate-account'], 'AuthController::activateAccount', ['as' => $reservedRoutes['activate-account']]);
    $routes->get($reservedRoutes['resend-activate-account'], 'AuthController::resendActivateAccount', ['as' => $reservedRoutes['resend-activate-account']]);

    // Forgot/Resets
    $routes->get($reservedRoutes['forgot'], 'AuthController::forgotPassword', ['as' => $reservedRoutes['forgot']]);
    $routes->post($reservedRoutes['forgot'], 'AuthController::attemptForgot');
    $routes->get($reservedRoutes['reset-password'], 'AuthController::resetPassword', ['as' => $reservedRoutes['reset-password']]);
    $routes->post($reservedRoutes['reset-password'], 'AuthController::attemptReset');
});
