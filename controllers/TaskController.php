<?php

namespace controllers;

use components\App;
use components\Validator;
use models\Task;
use models\User;

class TaskController extends Controller
{
    const PER_PAGE = 3;

    protected int $page;

    public function index(): void
    {
        $this->page = (int) App::$request->get('page') ?: 1;

        $task = new Task;

        $tasksCount = count(
            $task
                ->select('*')
                ->get()
        );

        $pagination = [
            'page'  => $this->page,
            'links' => (int) ceil($tasksCount / self::PER_PAGE)
        ];

        $tasks = $task->select('tasks.id, users.name, tasks.text, tasks.completed')
            ->join('users', 'tasks.user_id = users.id')
            ->limitOffset(($this->page - 1) * self::PER_PAGE, self::PER_PAGE)
            ->get();
        
        if (! count($tasks)) {
            http_response_code(404);
            die;
        }

        $orderDirection = $this->sort($tasks);

        $this->view('tasks/index', [
            'tasks' => $tasks,
            'pagination' => $pagination,
            'orderDirection' => $orderDirection
        ]);
    }

    public function create(): void
    {
        $this->view('tasks/create');
    }
    
    public function store(): void
    {
        $validator = new Validator([
            'email'  => App::$request->post('email'),
        ]);

        if ($validator->validate()) {

            $user = new User;
            $user->setAttribute('name', App::$request->post('name'));
            $user->setAttribute('email', App::$request->post('email'));
            $user->save();

            $task = new Task;
            $task->setAttribute('user_id', $user->getAttribute('id'));
            $task->setAttribute('text', App::$request->post('task'));
            $task->save();

            App::$request->redirect('/tasks');
        } else {
            App::$request->redirect('/tasks/create');
        }
    }

    public function edit()
    {
        $task = new Task;

        $this->view('tasks/update', [
            'task' => $task
                ->select('*')
                ->where('id', '=', App::$request->post('id'))
                ->first()
        ]);
    }

    public function update()
    {
        $task = new Task;
        $task
            ->select('*')
            ->where('id', '=', App::$request->post('id'))
            ->first();

        $task->setAttribute('text', App::$request->post('task'));
        $task->setAttribute('completed', (bool) App::$request->post('completed'));
        $task->update();

        App::$request->redirect('/tasks');
    }


    private function sort(&$tasks)
    {
        // ugly code sorry))
        $sortCredentials = explode(',', App::$request->get('sort'));

        $sort = [
            ! empty($sortCredentials[0]) ? $sortCredentials[0] : 'id',
            ! empty($sortCredentials[1]) ? $sortCredentials[1] : 'desc'
        ];

        $orderDirection = $sort[1] === 'desc' ? 'asc' : 'desc';

        usort($tasks, function ($item1, $item2) use ($orderDirection, $sort) {
            return $orderDirection === 'asc' ? $item1->getAttribute($sort[0]) > $item2->getAttribute($sort[0]) : $item1->getAttribute($sort[0]) < $item2->getAttribute($sort[0]);
        });

        return $orderDirection;
    }
}