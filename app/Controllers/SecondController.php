<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class SecondController extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function create()
    {
        helper('form');

        // Checks whether the form is submitted.
        if (!$this->request->is('post')) {
            // The form is not submitted, so returns the form.
            return view('templates/header', ['title' => 'Create a news item'])
                . view('secondtest/create')
                . view('templates/footer');
        }

        $post = $this->request->getPost(['title', 'body']);

        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body' => 'required|max_length[5000]|min_length[10]',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Create a news item'])
                . view('secondtest/create')
                . view('templates/footer');
        }

        return view('templates/header', ['title' => 'Create a news item'])
            . view('secondtest/success')
            . view('templates/footer');
    }
}