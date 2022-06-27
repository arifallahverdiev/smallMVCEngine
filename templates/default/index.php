<?php

use App\Entity\Task;
use App\Lib\Paginator;
use App\Lib\Sorter;

/**
 * @var array|Task[] $items
 * @var Paginator $paginator
 * @var Sorter $sorter
 */
?>
<div class="row">
    <div class="col-12 my-5">
        <a href="/add" class="btn btn-success">Добавить задачу</a>
        <?php if ($this->isAuth()): ?>
            <a href="/logout" class="btn btn-warning">Выйти из профиля</a>
        <?php else: ?>
            <a href="/login" class="btn btn-success">Авторизация</a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">
                    <a href="<?= $sorter->createSortLink('name', 'asc') ?>">Имя пользователя</a>
                </th>
                <th scope="col">
                    <a href="<?= $sorter->createSortLink('email', 'asc') ?>">Email</a>
                </th>
                <th scope="col">Текст задачи</th>
                <th scope="col">
                    <a href="<?= $sorter->createSortLink('status', 'asc') ?>">Статус</a>
                </th>
                <th scope="col">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $k => $item): ?>
                <tr>
                    <td class="col-name"><?= $item->getName() ?></td>
                    <td class="col-email"><?= $item->getEmail() ?></td>
                    <td class="col-description"><?= $item->getDescription() ?></td>
                    <td class="col-status">
                        <?php if ($item->getStatus() === Task::STATUS_FINISHED): ?>
                            <span class="d-inline-block badge bg-success">Выполнена</span>
                        <?php else: ?>
                            <span class="d-inline-block badge bg-warning">Новая</span>
                        <?php endif; ?>
                        <?php if ($item->getUpdatedAt() !== null): ?>
                            <span class="d-inline-block badge bg-secondary">отредактировано<br/>администратором</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($this->isAuth()): ?>
                            <a title="Edit task" href="/update?id=<?= $item->getId(); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-12 mt-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php foreach ($paginator->getPagesData() as $item): ?>
                    <li class="page-item <?= $item['isActive'] ? 'active' : '' ?>">
                        <a class="page-link"
                           href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</div>