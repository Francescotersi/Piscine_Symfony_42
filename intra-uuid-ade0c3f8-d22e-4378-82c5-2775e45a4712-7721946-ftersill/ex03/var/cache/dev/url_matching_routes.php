<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/ex03/new' => [[['_route' => 'ex03_newTable', '_controller' => 'App\\Controller\\ex03Controller::newTable'], null, null, null, false, false, null]],
        '/ex03/delete' => [[['_route' => 'ex03_deleteTable', '_controller' => 'App\\Controller\\ex03Controller::deleteTable'], null, null, null, false, false, null]],
        '/ex03/list' => [[['_route' => 'ex03_listTable', '_controller' => 'App\\Controller\\ex03Controller::listTable'], null, null, null, false, false, null]],
        '/ex03/update' => [[['_route' => 'ex03_updateTable', '_controller' => 'App\\Controller\\ex03Controller::updateTable'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
