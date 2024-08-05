<?php
// putting it under the App\Controller namespace
namespace App\Controllers;
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

    // about and contact functions
    public function about()
    {
        $this->render(
            'about',
            [
                'title' => 'About Us',
                'message' => 'This is the about page'
            ]
        );
    }
    
    public function contact()
    {
        $this->render(
            'contact',
            [
                'title' => 'Contact Us',
                'message' => 'This is the contact page'
            ]
        );
    }


}
