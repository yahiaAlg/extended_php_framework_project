<?php

namespace Core\Views;

class View
{
    protected $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function render($data = [])
    {
        extract($data);
        ob_start();
        require_once VIEWS_DIR."/{$this->view}.view.php";
        return ob_get_clean();
    }
}

// // creating a view for test
// $view = new View('home');
// echo $view->render(['title' => 'Home Page']); // output: Home Page
