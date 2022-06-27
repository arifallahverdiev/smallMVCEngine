<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Routing\Annotation\Route;
use App\Lib\Paginator;
use App\Lib\Sorter;
use App\Repository\TaskRepository;

class DefaultController extends Controller
{
    const ITEMS_PER_PAGE = 3;

    #[Route(path: '/', methods: ['get'])]
    public function index(): Response
    {
        $qb = (new TaskRepository())->getQb()->select();

        $qb->sort($_GET['sortField'] ?? null, $_GET['sortDirection'] ?? null);

        $paginator = new Paginator($qb);
        $paginator->paginate($_GET['page'] ?? 1, self::ITEMS_PER_PAGE);

        $sorter = new Sorter();

        return $this->render('default/index', [
            'items' => $qb->getResult(),
            'title' => 'Items',
            'paginator' => $paginator,
            'sorter' => $sorter
        ]);
    }
}