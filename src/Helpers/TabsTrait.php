<?php

namespace App\Helpers;

use Knp\Component\Pager\Pagination\PaginationInterface;

trait TabsTrait
{
    /**
     * @param PaginationInterface $pagination
     * @param $page
     * @param $limit
     * @return array
     */
    public function getListReviews(PaginationInterface $pagination, $page, $limit): array
    {
        $pages = intval($pagination->getTotalItemCount() / $limit);

        return [
            'items' => $pagination->getItems(),
            'current' => intval($page),
            'pages' => $pages == 0 ? 1 : $pages,
        ];

    }

}
