<?php
declare(strict_types=1);

namespace Bold\OrderComment\Test\Integration\Observer;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class AddOrderCommentToOrderTest
 * @package Bold\OrderComment\Test\Integration\Observer
 *
 * tests if the comment gets passed from the quote to the order during order creation.
 * @magentoDbIsolation enabled
 */
class AddOrderCommentToOrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create order with product that has child items
     *
     * @magentoDataFixture Magento/Sales/_files/quote_with_bundle.php
     * @return void
     * @throws \Exception
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testExecute()
    {
        $comment = 'test comment';

        $objectManager = Bootstrap::getObjectManager();

        /** @var Quote $quote */
        $quote = $objectManager->create(Quote::class);
        $quote->load('test01', 'reserved_order_id');

        $quote->setData(OrderComment::COMMENT_FIELD_NAME, $comment);
        $quote->save();
        
        /** @var CartManagementInterface $model */
        $model = $objectManager->create(CartManagementInterface::class);
        /** @var \Magento\Sales\Model\Order $order */
        $order = $model->submit($quote);
        
        self::assertEquals($comment, $order->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
