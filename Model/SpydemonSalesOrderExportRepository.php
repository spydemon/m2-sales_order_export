<?php

namespace Spydemon\SalesOrderExport\Model;

use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Class SpydemonSalesOrderExportRepository
 */
class SpydemonSalesOrderExportRepository
{
    /**
     * @var SpydemonSalesOrderExportResource
     */
    protected $spydemonSalesOrderExportResource;

    /**
     * SpydemonSalesOrderExportRepository constructor.
     *
     * @param SpydemonSalesOrderExportResource $spydemonSalesOrderExportResource
     */
    public function __construct(
        SpydemonSalesOrderExportResource $spydemonSalesOrderExportResource
    ){
        $this->spydemonSalesOrderExportResource = $spydemonSalesOrderExportResource;
    }

    /**
     * @param SpydemonSalesOrderExport $model
     * @param int                      $id
     * @return $this
     */
    public function get(SpydemonSalesOrderExport $model, $id)
    {
        $this->spydemonSalesOrderExportResource->load($model, $id);
        return $this;
    }

    /**
     * @param SpydemonSalesOrderExport $model
     * @return $this
     * @throws AlreadyExistsException
     */
    public function save(SpydemonSalesOrderExport $model)
    {
        $this->spydemonSalesOrderExportResource->save($model);
        return $this;
    }
}
