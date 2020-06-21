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
        $this->spydemonSalesOrderExportResource->load($model, $id, 'order_id');
        return $this;
    }

    /**
     * @param SpydemonSalesOrderExport $model
     * @return $this
     * @throws AlreadyExistsException
     */
    public function save(SpydemonSalesOrderExport $model)
    {
        // It seems that the $model could easily be wrongly initialized. Indeed, its relation with its order should be
        // implemented by hand. This condition will check if the relation was not forgotten and will create the link
        // if it's the case. Without the ID assignation, the save will not understand that we are updating an already
        // existing line and will try to create a new one.
        if (is_null($model->getId())) {
            $modelClone = clone $model;
            $this->get($modelClone, $model->getOrderId());
            if (!is_null($modelClone->getId())) {
                $model->setId($modelClone->getId());
            }
        }
        $this->spydemonSalesOrderExportResource->save($model);
        return $this;
    }
}
