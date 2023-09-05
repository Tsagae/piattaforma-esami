<?php

namespace App\Controllers;

use App\Repositories\DocentiData;
use App\Repositories\SegretariData;
use App\Repositories\StudentiData;
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
        if (
            !$this->validateData($post, [
                'email' => 'required|valid_email|max_length[255]|min_length[3]',
                'password' => 'required|max_length[5000]|min_length[3]',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }

        $error = "";
        $user = UsersData::getUtenteByEmail($post['email'], $error);
        //var_dump($user);
        if (!empty($error) || empty($user)) {
            if (empty($error))
                $error = "utente non esistente o password errata";
            $data['userError'] = $error;
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }

        if ($user->password !== $post['password']) { //TODO missing hashing
            $data['userError'] = "incorrect password";
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        } else {
            unset($user->password);
            $session = session();
            $session->set("user", $user);
            $error = "";
            error_log("userInfo: " . var_export($this::addUsersInfo($user, $error), true));
            error_log("error: " . $error);
            $session->set("$user->ruolo", $this::addUsersInfo($user, $error));
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

    private static function addUsersInfo(object $user, string &$error): ?object
    {
        switch ($user->ruolo) {
            case "studente":
                return StudentiData::getStudenteByIdUtente($user->id_utente, $error);
            case "docente":
                return DocentiData::getDocenteByIdUtente($user->id_utente, $error);
            case "segreteria":
                return SegretariData::getSegretarioByIdUtente($user->id_utente, $error);
            default:
                return null;
        }
    }
}