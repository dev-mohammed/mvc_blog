<?php


namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->load->controller('Header')->index();
    }
}