<?php

$routes->group(CI_SITE_AREA, ['namespace' => '\Adnduweb\Ci4_diaporama\Controllers\Admin', 'filter' => 'apiauth'], function ($routes) {
    $routes->get(config('Diaporama')->urlMenuAdmin . '/diaporamas', 'AdminDiaporamasController::renderViewList', ['as' => 'diaporama-index']);
    $routes->get(config('Diaporama')->urlMenuAdmin . '/diaporamas/edit/(:any)', 'AdminDiaporamasController::renderForm/$1');
    $routes->post(config('Diaporama')->urlMenuAdmin . '/diaporamas/edit/(:any)', 'AdminDiaporamasController::postProcess/$1');
    $routes->get(config('Diaporama')->urlMenuAdmin . '/diaporamas/add', 'AdminDiaporamasController::renderForm');
    $routes->post(config('Diaporama')->urlMenuAdmin . '/diaporamas/add', 'AdminDiaporamasController::postProcess');
});

$locale = '/';
if (service('Settings')->setting_activer_multilangue == true) {
    $locale = '/{locale}';
}
$routes->get($locale . '/diaporama', 'FrontDiaporamasController::show/$1', ['namespace' => '\Adnduweb\Ci4_diaporama\Controllers\Front']);
