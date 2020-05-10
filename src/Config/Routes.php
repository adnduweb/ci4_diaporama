<?php

$routes->group(CI_SITE_AREA, ['namespace' => '\Adnduweb\Ci4_diaporama\Controllers\Admin', 'filter' => 'apiauth'], function ($routes) {
    $routes->get('(:num)/(:any)/diaporamas', 'AdminDiaporamasController::renderViewList', ['as' => 'diaporama-index']);
    $routes->get('(:num)/(:any)/diaporamas/edit/(:any)', 'AdminDiaporamasController::renderForm/$3');
    $routes->post('(:num)/(:any)/diaporamas/edit/(:any)', 'AdminDiaporamasController::postProcess/$3');
    $routes->get('(:num)/(:any)/diaporamas/add', 'AdminDiaporamasController::renderForm');
    $routes->post('(:num)/(:any)/diaporamas/add', 'AdminDiaporamasController::postProcess');
});

$locale = '/';
if (service('Settings')->setting_activer_multilangue == true) {
    $locale = '/{locale}';
}
$routes->get($locale . '/(:segment)', 'FrontDiaporamasController::show/$1', ['namespace' => '\Adnduweb\Ci4_diaporama\Controllers\Front']);
