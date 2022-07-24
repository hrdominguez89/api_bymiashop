<?php

namespace App\Helpers;

use Knp\Component\Pager\Pagination\PaginationInterface;

trait OrderTrait
{
    /**
     * @param PaginationInterface $pagination
     * @param $page
     * @param $limit
     * @return array
     */
    public function getListOrders(PaginationInterface $pagination, $page, $limit): array
    {
        $pages = intval($pagination->getTotalItemCount() / $limit);

        return [
            'items' => $pagination->getItems(),
            'current' => intval($page),
            'pages' => $pages == 0 ? 1 : $pages,
        ];

    }


    /**
     * @param string|null $method
     * @param string|null $status
     * @return string
     */
    public function statusPayment(?string $method, ?string $status): string
    {
        $response = $status;

        if ($method == 'PAYPAL') {

            //'APPROVED' | 'SAVED' | 'CREATED' | 'VOIDED' | 'COMPLETED'

            switch (strtoupper($status)) {
                case 'APPROVED':
                    $response = 'Aprovado';
                    break;
                case 'SAVED':
                    $response = 'Salvado';
                    break;
                case 'CREATED':
                    $response = 'Creado';
                    break;
                case 'VOIDED':
                    $response = 'Anulado';
                    break;
                default:
                    $response = 'Completado';
                    break;
            }
        }

        return $response;
    }

    /**
     * @param string|null $status
     * @return string
     */
    public function statusOrder(?string $status): string
    {

        $response = "";

        // COMPLETED | SENT | PROCESSING | CANCELLED | REFUNDED | DELIVERED

        switch (strtoupper($status)) {
            case 'NEW':
                $response = 'Nuevo';
                break;
            case 'PENDING':
                $response = 'Pendiente';
                break;
            case 'COMPLETED':
                $response = 'Completado';
                break;
            case 'SENT':
                $response = 'Enviado';
                break;
            case 'PROCESSING':
                $response = 'Procesando';
                break;
            case 'CANCELLED':
                $response = 'Cancelado';
                break;
            case 'REFUNDED':
                $response = 'Rembolsado';
                break;
            case 'DELIVERED':
                $response = 'Entregado';
                break;
        }

        return $response;
    }
}
