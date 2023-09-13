<?php

namespace App\Controllers;

use App\Repositories\UsersData;


class UserController extends BaseController
{
    public function profileGet()
    {
        helper('form');
        $user = session()->get('user');
        return view('templates/header', ['title' => 'Profilo']) .
            view('profile', ['user' => $user]) .
            view('templates/footer');
    }

    public function profilePost()
    {
        helper('form');
        $user = session()->get('user');
        $data['user'] = $user;
        $post = $this->request->getPost(['password']);
        if (
            !$this->validateData($post, [
                'password' => 'required',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Profilo']) .
                view('profile', $data) .
                view('templates/footer');

        }
        $error = "";
        $post['id_utente'] = $user->id_utente;
        if (empty($post['password'])) {
            $data['queryError'] = "La password non può essere vuota";
            return view('templates/header', ['title' => 'Profilo']) .
                view('profile', $data) .
                view('templates/footer');

        } else {
            UsersData::updateUserPassword($user->id_utente, $post['password'], $error);
        }
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Profilo']) .
                view('profile', $data) .
                view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Password aggiornata correttamente</h1>'
            . view('templates/redirect', ['url' => '/', 'delay' => 3])
            . view('templates/footer');
    }
}