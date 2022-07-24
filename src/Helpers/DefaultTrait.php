<?php

namespace App\Helpers;

use App\Entity\Category;
use App\Repository\CategoryRepository;

trait DefaultTrait
{
    /**
     * @param CategoryRepository $repository
     * @return array
     */
    public function menuItem(CategoryRepository $repository): array
    {
        $menuItem = ['type' => 'link', 'label' => 'Categorias', 'url' => '', 'children' => []];
        /** @var Category $item */
        foreach ($repository->filterCategory() as $item) {
            $menuItem['children'][] = $item->asMenu();
        }

        return [$menuItem];
    }

}
