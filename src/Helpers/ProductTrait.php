<?php

namespace App\Helpers;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

trait ProductTrait
{
    /**
     * @param $rg
     * @return int[]
     */
    public function getRange($rg): array
    {
        [$min, $max] = [1, 1000];
        try {

            [$min, $max] = str_contains($rg, '-') ? explode('-', $rg) : [$min, $max];

        } catch (\Exception $ex) {

        }

        return [intval($min), intval($max)];
    }

    /**
     * @param string $string
     * @param string $separator
     * @return array
     */
    public function getExplodeBySeparator(string $string, string $separator = ','): array
    {
        $brandSlugs = [];

        try {

            $brandSlugs = explode($separator, $string);

        } catch (\Exception $ex) {

        }

        return $brandSlugs;

    }

    /**
     * @param $rangePrices
     * @return mixed|string
     */
    public function getRangePrice($rangePrices)
    {
        $min = min($rangePrices);
        $max = max($rangePrices);

        return $min == $max ? $max : $min.'-'.$max;
    }

    /**
     * @param array $cFields
     * @return array
     */
    public function combineAllSpecifications(array $cFields): array
    {
        $combine = [];

        for ($i = 0; $i < count($cFields) - 1; $i++) {

            $combine[$i] = $cFields[$i];

            for ($j = $i + 1; $j < count($cFields) - 1; $j++) {
                if ($cFields[$i]['customFields']['type'] == $cFields[$j]['customFields']['type'] && $cFields[$i]['slug'] == $cFields[$j]['slug']) {
                    foreach ($cFields[$j]['values'] as $value) {
                        $combine[$i]['values'][] = $value;
                    }
                    unset($cFields[$j]);
                    $cFields = array_values($cFields);
                }
            }
        }

        return $combine;

    }

    /**
     * @param PaginationInterface $pagination
     * @param array $items
     * @param array $brands
     * @param array $ctg
     * @param array $colors
     * @param array $filterValues
     * @param int $limit
     * @return array
     */
    public function getProductsList(
        PaginationInterface $pagination,
        array $items,
        array $brands,
        array $ctg,
        array $colors,
        array $filterValues,
        int $limit = 12
    ): array {

        [$total, $page] = [$pagination->getTotalItemCount(), $pagination->getCurrentPageNumber()];

        $from = $this->getFrom($limit, $page);
        $to = $this->getTo($limit, $from, $total);

        return [
            "items" => $items,
            "limit" => $limit,
            "total" => $total,
            "page" => $page,
            "pages" => intval($total / $limit),
            "from" => $from,
            "to" => $to,
            "sort" => "name_desc",
            "filters" => [
                [
                    "type" => "categories",
                    "slug" => "categories",
                    "name" => "Categorias",
                    "root" => true,
                    "items" => $ctg,
                ],
                [
                    "type" => "range",
                    "slug" => "price",
                    "name" => "Precio",
                    "value" => $this->parseFilterValues($filterValues, 'price'),
                    "min" => 1,
                    "max" => 3200,
                ],
                [
                    "type" => "check",
                    "slug" => "brand",
                    "name" => "Marca",
                    "value" => $this->parseFilterValues($filterValues, 'brand'),
                    "items" => $brands,
                ],
                [
                    "type" => "radio",
                    "slug" => "discount",
                    "name" => "Con descuento",
                    "value" => $this->parseFilterValues($filterValues, 'discount'),
                    "items" => [
                        [
                            "slug" => "any",
                            "name" => "Todos",
                            "count" => 16,
                        ],
                        [
                            "slug" => "no",
                            "name" => "No",
                            "count" => 15,
                        ],
                        [
                            "slug" => "yes",
                            "name" => "Si",
                            "count" => 1,
                        ],
                    ],
                ],
                [
                    "type" => "color",
                    "slug" => "color",
                    "name" => "Color",
                    "value" => $this->parseFilterValues($filterValues, 'color'),
                    "items" => $colors,
                ],
            ],
            "filterValues" => $filterValues,
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getFilterValues(Request $request): array
    {
        $fv = [];
        if ($d = $request->get('filter_discount')) {
            $fv['discount'] = $d;
        }
        if ($b = $request->get('filter_brand')) {
            $fv['brand'] = $b;
        }
        if ($p = $request->get('filter_price')) {
            $fv['price'] = $p;
        }
        if ($c = $request->get('filter_color')) {
            $fv['color'] = $c;
        }

        return $fv;
    }

    /**
     * @param array $filterValues
     * @param string $key
     * @return array|false|int[]|mixed|string|string[]
     */
    private function parseFilterValues(array $filterValues, string $key)
    {
        $values = [];
        if ($key == 'brand') {
            $values = isset($filterValues[$key]) ? explode(',', $filterValues[$key]) : [];
        }
        if ($key == 'color') {
            $values = isset($filterValues[$key]) ? explode(',', $filterValues[$key]) : [];
        }
        if ($key == 'price') {
            $values = isset($filterValues[$key]) ? explode('-', $filterValues[$key]) : [1, 3200];
        }
        if ($key == 'discount') {
            $values = $filterValues[$key] ?? "yes";
        }

        return $values;
    }

    /**
     * @param $limit
     * @param $page
     * @return float|int
     */
    private function getFrom($limit, $page)
    {
        return (($limit * $page) - $limit) + 1;
    }

    /**
     * @param $limit
     * @param $from
     * @param $total
     * @return int|mixed
     */
    private function getTo($limit, $from, $total)
    {
        $to = $from + ($limit - 1);
        if ($total <= $to) {
            $to = $total;
        }

        return $to;
    }
}
