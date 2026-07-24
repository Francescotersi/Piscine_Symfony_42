<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/ex04/new' => [[['_route' => 'ex04_newTable', '_controller' => 'App\\Controller\\ex04Controller::newTable'], null, null, null, false, false, null]],
        '/ex04/delete/table' => [[['_route' => 'ex04_deleteTable', '_controller' => 'App\\Controller\\ex04Controller::deleteTable'], null, null, null, false, false, null]],
        '/ex04/add' => [[['_route' => 'ex04_addUser', '_controller' => 'App\\Controller\\ex04Controller::addUser'], null, null, null, false, false, null]],
        '/ex04/list' => [[['_route' => 'ex04_listTable', '_controller' => 'App\\Controller\\ex04Controller::listTable'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/ex04/delete/([^/]++)(*:63)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        63 => [
            [['_route' => 'ex04_deleteUser', '_controller' => 'App\\Controller\\ex04Controller::deleteUser'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
