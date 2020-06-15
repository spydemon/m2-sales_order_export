<?php /** @noinspection PhpUndefinedClassInspection */

namespace Spydemon\SalesOrderExport\Console;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface
     */
    protected $modelLogger;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * ExportOrder constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface       $modelLogger
     * @param string|null           $name
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $modelLogger,
        $name = null
    ) {
        $this->modelLogger = $modelLogger;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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
        $this->commandOutput->writeln('<info>Start order exportation process.</info>');
        $this->modelLogger->info('End export:order console command.');
    }
}
