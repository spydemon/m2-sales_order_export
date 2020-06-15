<?php

namespace Spydemon\SalesOrderExport\Plugin\OrderRepository;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Spydemon\SalesOrderExport\Model\SpydemonSalesOrderExport;
use Spydemon\SalesOrderExport\Model\SpydemonSalesOrderExportRepository;

/**
 * Class ExtAttrExported
 *
 * Add the "exported" attribute of the "spydemon_sales_order_export" table as an extended attribute on the order entity.
 */
class AddExtAttributeExportedStatus
{
    /**
     * @var SpydemonSalesOrderExport
     */
    protected $spydemonSalesOrderExport;

    /**
     * @var OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * @var SpydemonSalesOrderExportRepository
     */
    protected $spydemonSalesOrderExportRepository;

    /**
     * AddAttrExtAttributeExportedStatus constructor.
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param SpydemonSalesOrderExport $spydemonSalesOrderExport
     * @param SpydemonSalesOrderExportRepository $spydemonSalesOrderExportRepository
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        SpydemonSalesOrderExport $spydemonSalesOrderExport,
        SpydemonSalesOrderExportRepository $spydemonSalesOrderExportRepository
    ) {
        $this->spydemonSalesOrderExportRepository = $spydemonSalesOrderExportRepository;
        $this->spydemonSalesOrderExport = $spydemonSalesOrderExport;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Load the "exported" extended attribute to the given $order.
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    )
    {
        $spydemonSalesOrderExport = clone $this->spydemonSalesOrderExport;
        $this->spydemonSalesOrderExportRepository->get($spydemonSalesOrderExport, $order->getId());
        // This condition is true if no exported extension attribute exists on the order.
        if (count($spydemonSalesOrderExport->getData()) == 0) {
            $spydemonSalesOrderExport = $this->initializeDefaultAttribute($order);
        }
        $orderExtension = $order->getExtensionAttributes() ?: $this->orderExtensionFactory->create();
        $orderExtension->setExported($spydemonSalesOrderExport);
        $order->setExtensionAttributes($orderExtension);
        return $order;
    }

    /**
     * Save the "exported" extended attribute on the database.
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @throws AlreadyExistsException
     */
    public function afterSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    )
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $spydemonSalesOrderExport = $extensionAttributes->getExported() ?: $this->initializeDefaultAttribute($order);
        // The order id seems missing if the model was loaded from a "getList" call of the repository…
        $spydemonSalesOrderExport->setOrderId($order->getId());
        $this->spydemonSalesOrderExportRepository->save($spydemonSalesOrderExport);
        return $order;
    }

    /**
     * Will create the default exported status attribute if the current order doesn't have one.
     * It default value will be 0.
     *
     * @param $order
     * @return SpydemonSalesOrderExport
     */
    protected function initializeDefaultAttribute($order)
    {
        $spydemonSalesOrderExport = clone $this->spydemonSalesOrderExport;
        $spydemonSalesOrderExport->setOrderId($order->getId());
        $spydemonSalesOrderExport->setExportedStatus('0');
        return $spydemonSalesOrderExport;
    }
}
