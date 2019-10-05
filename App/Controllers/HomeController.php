<?php


namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        echo $this->url->link('/home');
        //$this->url->redirectTo('/home');
        echo '<br>';
        echo assets('images/logo.png');
    }
}