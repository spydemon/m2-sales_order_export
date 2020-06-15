<?php

namespace Spydemon\SalesOrderExport\Model;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb as InheritedClass;

/**
 * Class SpydemonSalesOrderExport
 *
 * Link the Magento model with the "spydemon_sales_order_export" database.
 */
class SpydemonSalesOrderExportResource extends InheritedClass
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('spydemon_sales_order_export', 'id');
    }
}
