<?php


namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
      /*  echo $this->db->data([
            'email' => 'dola@buseet.com',
            'status'   => 'disabled'
        ])->insert('users')->lastId();*/

      $this->db->data('email' , 'emad@buseet.com')
          ->where('id = ?' , 1)
          ->update('users');


    }
}