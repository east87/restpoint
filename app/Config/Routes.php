<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Points');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

$routes->resource('points');
$routes->get('points/(:num)/(:num)', 'point::show/$1/$2');
$routes->put('points/(:any)/(:any)',      'point::update/$1/$2');

$routes->presenter('points');

// Equivalent to the following:
$routes->get('points/new',                			'points::new');
$routes->post('points/create',            			'points::create');
$routes->post('points',                   			'points::create');   // alias
$routes->get('points',                    			'points::index');
$routes->get('points/show/(:segment)/(:segment)',   'points::show/$1/$2');
$routes->get('points/(:segment)/(:segment)',        'points::show/$1');  // alias
$routes->get('points/edit/(:segment)',    			'points::edit/$1');
$routes->post('points/update/(:segment)/(:segment)', 'points::update/$1');
$routes->get('points/remove/(:segment)',  			 'points::remove/$1');
$routes->post('points/delete/(:segment)', 			 'points::update/$1');



//$routes->get('points/update/(:any)', 'points::update/$1');
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
