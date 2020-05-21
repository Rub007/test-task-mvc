<?php

/**
 * @var array $tasks
 * @var Task $task
 * @var array $pagination
 * @var string $orderDirection
 */

use components\Session;
use models\Task;
use components\App;

?>

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">ID <a href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'id,' . $orderDirection])) ?>"><i class="fa fa-fw fa-sort"></i></a></th>
        <th scope="col">User <a href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'name,' . $orderDirection])) ?>"><i class="fa fa-fw fa-sort"></i></a></th>
        <th scope="col">Text <a href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'text,' . $orderDirection])) ?>"><i class="fa fa-fw fa-sort"></i></a></th>
        <th scope="col">Completed <a href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'completed,' . $orderDirection])) ?>"><i class="fa fa-fw fa-sort"></i></a></th>
        <?php if (Session::has('user')): ?>
        <th scope="col">Action</th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= $task->getAttribute('id') ?></td>
            <td><?= $task->getAttribute('name') ?></td>
            <td><?= $task->getAttribute('text') ?></td>
            <td><?= $task->getAttribute('completed') ? 'Yes' : 'No' ?></td>
            <?php if (Session::has('user')): ?>
                <td>
                    <form action="/tasks/edit" method="post">
                        <input type="hidden" name="id" value="<?= $task->getAttribute('id') ?>">
                        <input type="submit" class="btn btn-primary" value="Edit">
                    </form>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $pagination['links']; $i++): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor;?>
    </ul>
</nav>