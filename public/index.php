<?php

require_once __DIR__ . '/../config/config.php';
require_once BASEROOT.'/vendor/autoload.php';


use Core\Router\Router;

// surround with error handling 
try {
    // create router instance
    $router = new Router();
    // run router
    $router->get(
        '/',
        "PagesController@index",
        ['auth' => false],
        'home'
    );

    // Dispatch the request
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    // echoing the current uri with it's method
    echo "<br/>Current URI: $uri with method: $method<br/>";
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    // log the error
    error_log($e->getMessage());
    // display error message
    echo 'Error: ' . $e->getMessage();
} finally {
    // close the database connection
    // if you have one
}





// use cases
// $router->get('/', function() {
//     echo "Welcome to the homepage!";
// });

// $router->post('/users', 'UserController@store');

// $router->put('/users/{id}', 'UserController@update');

// $router->delete('/users/{id}', 'UserController@destroy');
// $router->get('/dashboard', 'DashboardController@index', ['auth']);
// $router->get('/users/{id}', 'UserController@show', [], 'user.show');

// // Later, generate a URL:
// echo $router->url('user.show', ['id' => 5]); // Outputs: /users/5