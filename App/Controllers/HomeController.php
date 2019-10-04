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

        /*      $this->db->data('email' , 'emad@buseet.com')
                  ->where('id = ?' , 1)
                  ->update('users');*/
        /*
                $users = $this->db
                    ->select('*')
                    ->from('users')
                    ->where('id > ? AND id < ?', 1, 4)
                    ->fetchAll();
                pre($users);*/

        $this->db->where('id > ?', 2)->delete('users');

    }
}