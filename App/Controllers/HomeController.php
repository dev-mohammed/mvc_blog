<?php


namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $users = $this->load->model('Users');
        pre($users->get(1));
    }
}