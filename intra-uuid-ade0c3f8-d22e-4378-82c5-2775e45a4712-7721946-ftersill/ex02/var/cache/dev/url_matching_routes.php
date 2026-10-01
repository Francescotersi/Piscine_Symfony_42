<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/ex02/new' => [[['_route' => 'ex02_newTable', '_controller' => 'App\\Controller\\ex02Controller::tableSetUp'], null, null, null, false, false, null]],
        '/ex02/update' => [[['_route' => 'ex02_updateTable', '_controller' => 'App\\Controller\\ex02Controller::tableUpdate'], null, null, null, false, false, null]],
        '/ex02/list' => [[['_route' => 'ex02_listTable', '_controller' => 'App\\Controller\\ex02Controller::listTable'], null, null, null, false, false, null]],
        '/ex02/delete' => [[['_route' => 'ex02_deleteTable', '_controller' => 'App\\Controller\\ex02Controller::deleteTable'], null, null, null, false, false, null]],
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
