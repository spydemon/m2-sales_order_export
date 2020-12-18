<?php

namespace Spydemon\SalesOrderExport\Plugin\OrderRepository;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Spydemon\SalesOrderExport\Model\SpydemonSalesOrderExport;
use Spydemon\SalesOrderExport\Model\SpydemonSalesOrderExportFactory;
use Spydemon\SalesOrderExport\Model\SpydemonSalesOrderExportRepository;

/**
 * Class ExtAttrExported
 *
 * Add the "exported" attribute of the "spydemon_sales_order_export" table as an extended attribute on the order entity.
 */
class AddExtAttributeExportedStatus
{
    /**
     * @var SpydemonSalesOrderExportFactory
     */
    protected $spydemonSalesOrderExportFactory;

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
     * @param SpydemonSalesOrderExportFactory $spydemonSalesOrderExportFactory
     * @param SpydemonSalesOrderExportRepository $spydemonSalesOrderExportRepository
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        SpydemonSalesOrderExportFactory $spydemonSalesOrderExportFactory,
        SpydemonSalesOrderExportRepository $spydemonSalesOrderExportRepository
    ) {
        $this->spydemonSalesOrderExportFactory = $spydemonSalesOrderExportFactory;
        $this->spydemonSalesOrderExportRepository = $spydemonSalesOrderExportRepository;
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
    ) {
        $this->fetchExportedStatusForOrder($order);
        return $order;
    }

    /**
     *
     * Load the "exported" extended attribute to the given orders.
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $result
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $result
    ) {
        foreach ($result->getItems() as $entity) {
            $this->fetchExportedStatusForOrder($entity);
        }
        return $result;
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
    ) {
        $extensionAttributes = $order->getExtensionAttributes();
        $spydemonSalesOrderExport = $extensionAttributes->getExported() ?: $this->initializeDefaultAttribute($order);
        // The order id seems missing if the model was loaded from a "getList" call of the repository…
        $spydemonSalesOrderExport->setOrderId($order->getId());
        $this->spydemonSalesOrderExportRepository->save($spydemonSalesOrderExport);
        return $order;
    }

    /**
     * @param OrderInterface $order
     */
    protected function fetchExportedStatusForOrder(OrderInterface $order)
    {
        $spydemonSalesOrderExport = $this->spydemonSalesOrderExportFactory->create();
        $this->spydemonSalesOrderExportRepository->get($spydemonSalesOrderExport, $order->getId());
        // This condition is true if no exported extension attribute exists on the order.
        if (count($spydemonSalesOrderExport->getData()) == 0) {
            $spydemonSalesOrderExport = $this->initializeDefaultAttribute($order);
        }
        $orderExtension = $order->getExtensionAttributes() ?: $this->orderExtensionFactory->create();
        $orderExtension->setExported($spydemonSalesOrderExport);
        $order->setExtensionAttributes($orderExtension);
    }

    /**
     * Will create the default exported status attribute if the current order doesn't have one.
     * It default value will be 0.
     *
     * @param $order
     * @return SpydemonSalesOrderExport
     */
    protected function initializeDefaultAttribute(OrderInterface $order)
    {
        $spydemonSalesOrderExport = $this->spydemonSalesOrderExportFactory->create();
        $spydemonSalesOrderExport->setOrderId($order->getId());
        $spydemonSalesOrderExport->setExportedStatus('0');
        return $spydemonSalesOrderExport;
    }
}
