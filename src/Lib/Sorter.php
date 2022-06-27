<?php

namespace App\Lib;

class Sorter
{
    public function createSortLink(string $fieldName, string $defaultDirection): string
    {
        $query = $_GET;

        if (!isset($query['sortField'])
            || $query['sortField'] !== $fieldName
            || $query['sortDirection'] !== $defaultDirection
        ) {
            $query['sortDirection'] = $defaultDirection;
        } else {
            $query['sortDirection'] = $defaultDirection === 'asc' ? 'desc' : 'asc';
        }

        $query['sortField'] = $fieldName;

        return parse_url($_SERVER['REQUEST_URI'])['path'] . '?' . http_build_query($query);
    }
}