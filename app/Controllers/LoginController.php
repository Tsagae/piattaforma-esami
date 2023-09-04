<?php

namespace App\Controllers;

use App\Repositories\UsersData;
use function PHPUnit\Framework\isEmpty;

class LoginController extends BaseController
{
    public function login()
    {
        if (session()->get('user') !== null) {
            return redirect()->to(site_url('/'));
        }

        helper('form');
        $data['userError'] = "";
        // Checks whether the form is submitted.
        if (!$this->request->is('post')) {
            // The form is not submitted, so returns the form.
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['email', 'password']);
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'email' => 'required|valid_email|max_length[255]|min_length[3]',
            'password' => 'required|max_length[5000]|min_length[3]',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }

        $error = "";
        $user = UsersData::getUtenteByEmail($post['email'], $error);
        //var_dump($user);
        if (!empty($error) || empty($user)) {
            if(empty($error)) $error = "utente non esistente o password errata";
            $data['userError'] = $error;
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }
        log_message('info', "pass");

        if ($user->password !== $post['password']) { //TODO missing hashing
            $data['userError'] = "incorrect password";
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        } else {
            unset($user->password);
            $session = session();
            $session->set("user", $user);
        }
        /*
            return view('templates/header', ['title' => 'Login'])
                . view('success')
                . view('templates/footer');
        */
        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('/'));
    }
}