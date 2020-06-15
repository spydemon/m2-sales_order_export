<?php /** @noinspection PhpUndefinedClassInspection */

namespace Spydemon\SalesOrderExport\Cron;

use Psr\Log\LoggerInterface;

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
     * ExportOrder constructor.
     *
     * @param LoggerInterface $modelLogger
     */
    public function __construct(
        LoggerInterface $modelLogger
    ) {
        $this->modelLogger = $modelLogger;
    }

    /**
     * CRON task to run.
     */
    public function execute()
    {
        $this->modelLogger->info('Start ExportOrder CRON job.');
        $this->modelLogger->info('End ExportOrder CRON job.');
    }
}
