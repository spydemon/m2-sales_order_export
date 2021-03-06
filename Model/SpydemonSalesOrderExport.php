<?php

namespace Spydemon\SalesOrderExport\Model;

use Magento\Framework\Model\AbstractModel as InheritedClass;
use Spydemon\SalesOrderExport\Api\Data\OrderExtension;

/**
 * Class SpydemonSalesOrderExport
 *
 * This model handles the "exported" extended attribute that the present module add on Magento orders.
 */
class SpydemonSalesOrderExport extends InheritedClass implements OrderExtension
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        // Define the resource model linked to this model.
        $this->_init(SpydemonSalesOrderExportResource::class);
    }

    /**
     * @return int
     */
    public function getOrderId() : int
    {
        return $this->getData('order_id');
    }

    /**
     * TODO: map the integer to const for more visibility?
     * @return int
     */
    public function getExportedStatus() : int
    {
        return $this->getData('exported');
    }

    /**
     * @param $orderId int
     * @return $this
     */
    public function setOrderId(int $orderId)
    {
        $this->setData('order_id', $orderId);
        return $this;
    }

    /**
     * @param $status int
     * @return $this
     */
    public function setExportedStatus(int $status)
    {
        $this->setData('exported', $status);
        return $this;
    }
}
