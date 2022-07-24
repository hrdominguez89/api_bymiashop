<?php

namespace App\Helpers;

trait ShoppingTrait
{
    /**
     * @param array $shImport
     * @return array
     */
    public function getIds(array $shImport): array
    {
        $ids = [];
        foreach ($shImport as $item) {
            $ids[] = $item['id'];
        }

        return $ids;
    }

    /**
     * @param $pid
     * @param array $shImport
     * @param int $qty
     * @return int|mixed
     */
    public function getQuantity($pid, array $shImport, int $qty = 1)
    {
        foreach ($shImport as $item) {
            if ($item['id'] == $pid) {
                $qty = $item['qty'];
                break;
            }
        }

        return $qty;
    }
}
