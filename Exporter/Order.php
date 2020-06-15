<?php

namespace Spydemon\SalesOrderExport\Exporter;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface as OrderRepository;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderResourceModelCollection;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Order
 *
 * This class handle the logic that will define what to do with orders.
 * You should complete it in your own module that has to inherit it.
 */
class Order
{
    /**
     * Allow us to build complex queries for fetching collection entities.
     *
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Log what happen during the import.
     * Note that by default, the di configuration sets here an instance of the Spydemon\SalesOrderExport\Logger\Logger
     * virtual type that log its content in the var/log/export_order.log file.
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Allow us to fetch a collection of orders depending of conditions set in SearchCriteriaBuilder objects.
     *
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * Will create a complex condition build with FilterBuilder objects. This condition will be used for filtering the
     * orders.
     *
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Order constructor.
     *
     * @param FilterBuilder $filterBuilder
     * @param Logger $logger
     * @param OrderRepository $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        Logger $logger,
        OrderRepository $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get the ID of all orders that was not exported.
     *
     * @return OrderResourceModelCollection
     */
    public function fetchOrdersToExport() : OrderResourceModelCollection
    {
        // This first filter will handle all orders that has no exported_status. It should never happen if the order was
        // saved through the order repository but could occurs if it was saved through the old "save" method on the
        // order object directly.
        $filterNotNull = $this->filterBuilder
            ->setField('exported')
            ->setConditionType('null')
            ->create();
        // This second filter will handle all orders that has an exported_status different to one. Indeed, our import
        // will set this value to one when the export will be done on the given order. This behavior avoid us to export
        // the same order twice.
        $filterNotOne = $this->filterBuilder
            ->setField('exported')
            ->setConditionType('neq')
            ->setValue(1)
            ->create();
        // We combine $filterNotNull and $filterNotOne in a OR query. It means that only one of the should be true for
        // fetching the given order.
        $filter = $this->searchCriteriaBuilder
            ->addFilters([$filterNotNull, $filterNotOne])
            ->create();
        return $this->orderRepository->getList($filter);
    }
}
