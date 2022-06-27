<?php

use App\Entity\Task;

/**
 * @var Task $task
 */

?>
<div class="row">
    <div class="col-12 mb-5">
        <a href="/"><< назад</a>
    </div>
    <div class="col-12">
        <form action="/update?id=<?= $task->getId() ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" name="name" id="name" disabled value="<?= $task->getName() ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" disabled value="<?= $task->getEmail() ?>">
            </div>
            <div class="mb-3">
                <input class="form-check-input" type="checkbox" name="status"
                       value="1"
                       id="status" <?= $task->getStatus() === 1 ? 'checked="checked" disabled' : '' ?>>
                <label class="form-check-label" for="status">
                    Статус
                </label>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Текст задачи</label>
                <textarea class="form-control" name="description"
                          id="description"><?= $task->getDescription() ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>