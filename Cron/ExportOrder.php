<?php /** @noinspection PhpUndefinedClassInspection */

namespace Spydemon\SalesOrderExport\Cron;

use Exception;
use Psr\Log\LoggerInterface;
use Spydemon\SalesOrderExport\Exporter\Order as OrderExporter;

/**
 * Class ExportOrder
 *
 * Will automatically export orders.
 */
class ExportOrder
{

    /**
     * @var LoggerInterface
     */
    protected $modelLogger;

    /**
     * @var OrderExporter
     */
    protected $orderExporter;

    /**
     * ExportOrder constructor.
     *
     * @param LoggerInterface $modelLogger
     */
    public function __construct(
        LoggerInterface $modelLogger,
        OrderExporter $orderExporter
    ) {
        $this->modelLogger = $modelLogger;
        $this->orderExporter = $orderExporter;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $this->modelLogger->info('Start ExportOrder CRON job.');
        $ordersToExport = $this->orderExporter->fetchOrdersToExport();
        if (count($ordersToExport) > 0) {
            try {
                $this->orderExporter->exportOrders($ordersToExport);
            } catch (Exception $e) {
                $this->modelLogger->error($e->getMessage());
                throw $e;
            }
        } else {
            $this->modelLogger->info('No order to export. This run does nothing.');
        }
        $this->modelLogger->info('End ExportOrder CRON job.');
    }
}
