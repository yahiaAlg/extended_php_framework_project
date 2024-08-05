<?php
$router->get(
    '/',
    "PagesController@index",
    ['auth' => false],
    'home'
);
$router->get(
    '/about',
    "PagesController@about",
    ['auth' => false],
    'home'
);
$router->get(
    '/contact',
    "PagesController@contact",
    ['auth' => false],
    'home'
);

