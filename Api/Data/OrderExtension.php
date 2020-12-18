<?php

namespace Spydemon\SalesOrderExport\Api\Data;

/**
 * Interface OrderExtension
 *
 * Represent data that can be fetched from the Magento API.
 *
 * @package Spydemon\SalesOrderExport\Api\Data
 */
interface OrderExtension
{
    /**
     * @return int
     */
    public function getExportedStatus() : int;

    /**
     * @return int
     */
    public function getOrderId() : int;

    /**
     * @param int $status
     * @return mixed
     */
    public function setExportedStatus(int $status);

    /**
     * @param int $orderId
     * @return mixed
     */
    public function setOrderId(int $orderId);
}
