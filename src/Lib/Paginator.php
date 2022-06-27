<?php

namespace App\Lib;

use App\Core\Database\QueryBuilder;

class Paginator
{
    private QueryBuilder $queryBuilder;

    private int $totalItemsCount;
    private int $page;
    private int $size;
    protected string $pageParamName = 'page';

    /**
     * @param string $pageParamName
     */
    public function setPageParamName(string $pageParamName): void
    {
        $this->pageParamName = $pageParamName;
    }

    /**
     * @return string
     */
    public function getPageParamName(): string
    {
        return $this->pageParamName;
    }

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function paginate(int $page, int $size): void
    {
        $qb = clone $this->queryBuilder;

        $this->totalItemsCount = $qb->count();
        $this->page = $page;
        $this->size = $size;

        $this
            ->queryBuilder
            ->limit($size)
            ->offset(($page - 1) * $size);
    }

    public function getPagesData(): array
    {
        $data = [];
        $pagesNumbers = ceil($this->totalItemsCount / $this->size);

        for ($pageNumber = 1; $pageNumber <= $pagesNumbers; $pageNumber++) {
            $data[$pageNumber] = [
                'url' => $this->getPaginationUrl($pageNumber),
                'label' => $pageNumber,
                'isActive' => $pageNumber === $this->page
            ];
        }

        return count($data) > 1 ? $data : [];
    }

    private function getPaginationUrl(int $pageNumber): string
    {
        $query = $_GET;
        $query['page'] = $pageNumber;

        return parse_url($_SERVER['REQUEST_URI'])['path'] . '?' . http_build_query($query);
    }
}