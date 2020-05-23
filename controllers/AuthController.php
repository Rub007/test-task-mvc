<?php

namespace controllers;

use components\App;
use components\Request;
use components\Session;
use components\Ui;
use components\Validator;
use models\User;
use rules\Email;
use rules\Required;

class AuthController extends Controller
{
    public function showLoginForm(): void
    {
        if (Session::has('user')) {
            App::$request->redirect('/tasks');
        }

        $this->view('auth/login');
    }

    public function auth(): void
    {
        if (Session::has('user')) {
            App::$request->redirect('/tasks');
        }

        $validator = new Validator([
            'email'  => [
                new Required(App::$request->post('email')),
                new Email(App::$request->post('email'))
            ],
            'password' => [
                new Required(App::$request->post('password')),
            ]
        ]);

        if ($validator->validate()) {
            $user = new User;
            $user
                ->select('*')
                ->where('email', '=', App::$request->post('email'))
                ->first();

            if ($user->hasAttributes() && password_verify(App::$request->post('password'), $user->getAttribute('password'))) {
                Session::store('user', [
                    'name' => $user->getAttribute('name'),
                ]);

                Ui::alert('success', 'You are logged in success!');
                App::$request->redirect('/tasks');
            } else {
                Ui::alert('errors', ['Invalid email or password!']);
                App::$request->redirect(
                    App::$request->referrer()
                );
            }
        } else {
            Ui::alert('errors', $validator->errors());
            App::$request->redirect('/login');
        }

    }

    public function logout(): void
    {
        if (Session::has('user')) {
            Session::remove('user');
        }

        App::$request->redirect('/tasks');
    }
}