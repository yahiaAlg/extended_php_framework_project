<?php

use Core\Controllers\Controller;

class PagesController extends Controller 
{
    public function index()
    {
        $this->render(
            'home',
            [
                'title' => 'Home Page',
                'message' => 'Welcome to our website'

            ]
        );
    }
}
