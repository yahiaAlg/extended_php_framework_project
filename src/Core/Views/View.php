<?php

namespace Core\Views;

class View
{
    protected $view;

    public function __construct($view)
    {
        $this->view = $view;
        echo "<br/>launching the {$this->view} inside View Core class<br/>";
    }

    public function render($data = [])
    {
        extract($data);
        ob_start();
        echo "<br/>rendering the {$this->view} inside View Core class<br/>";
        require VIEWS_DIR."/{$this->view}.view.php";
        return ob_get_clean();
    }
}

// // creating a view for test
// $view = new View('home');
// echo $view->render(['title' => 'Home Page']); // output: Home Page
