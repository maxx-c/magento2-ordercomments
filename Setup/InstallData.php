<?php
declare(strict_types=1);

namespace Bold\OrderComment\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * Class InstallData
 * @package Bold\OrderComment\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;
    
    /**
     * @var QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * InstallData constructor.
     * @param SalesSetupFactory $salesSetupFactory
     * @param QuoteSetupFactory $quoteSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);

        /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);
        
        $quoteInstaller->addAttribute(
            'quote',
            'bold_order_comment',
            ['type' => Table::TYPE_TEXT, 'length' => '64k', 'nullable' => true]
        );

        $salesInstaller->addAttribute(
            'order',
            'bold_order_comment',
            ['type' => Table::TYPE_TEXT, 'length' => '64k', 'nullable' => true, 'grid' => true]
        );

        $setup->endSetup();
    }
}
