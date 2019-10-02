<?php


namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->response->setHeader('name', 'dola');
        $data['my_name'] = 'dola';
        return $this->view->render('home', $data);
    }
}