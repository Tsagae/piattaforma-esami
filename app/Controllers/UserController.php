<?php

namespace App\Controllers;

use App\Repositories\EsamiData;
use App\Repositories\StudentiData;
use App\Repositories\UsersData;


class UserController extends BaseController
{
    public function profileGet()
    {
        helper('form');
        $user = session()->get('user');
        return view('templates/header', ['title' => 'Profilo']) .
            view('profile', ['user' => $user]) .
            ($user->ruolo === 'studente' && StudentiData::getNumeroEsamiMancanti(session()->get('studente')->matricola) !== 0 ? "<br><a class='w-10 btn btn-danger btn-lg' href='/studenti/rinuncia'>Rinuncia agli studi</a>" : "") .
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
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
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