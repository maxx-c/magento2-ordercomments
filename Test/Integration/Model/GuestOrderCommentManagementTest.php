<?php
declare(strict_types=1);

namespace Bold\OrderComment\Test\Integration\Model;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Bold\OrderComment\Api\GuestOrderCommentManagementInterface;
use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdMask;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class GuestOrderCommentManagementTest
 * @package Bold\OrderComment\Test\Integration\Model
 *
 * @magentoDbIsolation enabled
 */
class GuestOrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_bundle.php
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGuestSaveOrderComment()
    {
        $objectManager = Bootstrap::getObjectManager();

        $comment = 'test comment guest';

        /** @var Quote $quote */
        $quote = $objectManager->create(Quote::class);
        $quote->load('test01', 'reserved_order_id');

        /** @var QuoteIdMask $quoteMask */
        $quoteMask = $objectManager->create(QuoteIdMask::class);
        $quoteMask->load($quote->getId(), 'quote_id');
        
        $model = $objectManager->create(GuestOrderCommentManagementInterface::class);

        $data = $objectManager->create(OrderCommentInterface::class);
        $data->setComment($comment);

        $model->saveOrderComment($quoteMask->getMaskedId(), $data);
        $quote->load('test01', 'reserved_order_id');

        self::assertEquals($comment, $quote->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
