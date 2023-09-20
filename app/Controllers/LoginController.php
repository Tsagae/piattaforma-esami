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
            $redirect = $this->redirectUserByRole(session()->get('user')->ruolo);
            if ($redirect !== null) return $redirect;
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
        if (!password_verify($post['password'], $user->password)) {
            $data['userError'] = "utente non esistente o password errata";
            return view('templates/header', ['title' => 'Login'])
                . view('login', $data)
                . view('templates/footer');
        }
        //successful login
        unset($user->password);
        $session = session();
        $session->set("user", $user);
        $error = "";
        $usersInfo = $this::addUsersInfo($user, $error);
        $session->set("$user->ruolo", $usersInfo);
        $redirect = $this->redirectUserByRole($user->ruolo);
        if ($redirect !== null) return $redirect;
        $data['userError'] = "utente non esistente o password errata";
        return view('templates/header', ['title' => 'Login'])
            . view('login', ['userError' => ''])
            . view('templates/footer');
    }

    private function redirectUserByRole($role): ?\CodeIgniter\HTTP\RedirectResponse
    {
        switch ($role) {
            case "studente":
                return redirect()->to(site_url('/studenti'));
            case "docente":
                return redirect()->to(site_url('/docenti'));
            case "segreteria":
                return redirect()->to(site_url('/segreteria'));
            default:
                break;
        }
        return null;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('/'));
    }

    public function unauthorized()
    {
        return view('templates/header', ['title' => 'Unauthorized'])
            . view('unauthorized')
            . view('templates/footer');
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