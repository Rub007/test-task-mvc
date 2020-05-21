<?php

namespace controllers;

use components\App;
use components\Request;
use components\Session;
use components\Validator;
use models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('user')) {
            App::$request->redirect('/tasks');
        }

        $this->view('auth/login');
    }

    public function auth()
    {
        $validator = new Validator([
            'email'  => App::$request->post('email'),
        ]);

        if ($validator->validate()) {
            $user = new User;
            $user
                ->select('*')
                ->where('email', '=', App::$request->post('email'))
                ->first();

            if ($user) {
                if (password_verify(App::$request->post('password'), $user->getAttribute('password'))) {
                    $_SESSION['user'] = [
                        'name' => $user->getAttribute('name')
                    ];
                }

                App::$user = $user;
                App::$request->redirect('/tasks');
            } else {
                Session::flash('messages', ['User does not exists in our database']);
                App::$request->redirect('/login');
            }
        } else {
            App::$request->redirect('/login');
        }

    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        App::$request->redirect('/tasks');
    }
}