<?php /** @noinspection PhpUndefinedClassInspection */

namespace Spydemon\SalesOrderExport\Console;

use Psr\Log\LoggerInterface;
use Spydemon\SalesOrderExport\Exporter\Order as OrderExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Will export all existing order that currently wasn't exported.
 */
class ExportOrder extends Command
{

    /**
     * @var OutputInterface
     */
    protected $commandOutput;

    /**
     * @var OrderExporter
     */
    protected $orderExporter;

    /**
     * @var LoggerInterface
     */
    protected $modelLogger;

    /**
     * ExportOrder constructor.
     *
     * @param LoggerInterface $modelLogger
     * @param OrderExporter $orderExporter
     * @param string|null $name
     */
    public function __construct(
        LoggerInterface $modelLogger,
        OrderExporter $orderExporter,
        $name = null
    ) {
        $this->modelLogger = $modelLogger;
        $this->orderExporter = $orderExporter;
        return parent::__construct($name);
    }

    /**
     * Check if the command is valid.
     */
    protected function configure()
    {
        $this->setName('export:order')
            ->setDescription('Manage order exportation from Magento.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandOutput = $output;
        $this->modelLogger->info('Start export:order console command.');
        $ordersToExport = $this->orderExporter->fetchOrdersToExport();
        if (count($ordersToExport) > 0) {
            $ordersToExportString = '';
            foreach ($ordersToExport as $currentOrder) {
                $ordersToExportString .= " {$currentOrder->getId()}";
            }
            $this->commandOutput->writeln("<info>Orders to export (by ID):$ordersToExportString.</info>");
            try {
                $this->orderExporter->exportOrders($ordersToExport);
            } catch (\Exception $e) {
                $this->commandOutput->writeln("<error>Exportation aborted: {$e->getMessage()}.");
                $this->modelLogger->error("Exportation aborted: {$e->getMessage()}.");
            }
        } else {
            $this->commandOutput->writeln("<info>Order to export : none. The export stop thus here.</info>");
            $this->modelLogger->info('No order to export. This run does nothing.');
        }
        $this->commandOutput->writeln('<info>Start order exportation process.</info>');
        $this->modelLogger->info('End export:order console command.');
    }
}
