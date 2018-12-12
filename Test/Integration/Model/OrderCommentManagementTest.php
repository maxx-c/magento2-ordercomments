<?php
declare(strict_types=1);

namespace Bold\OrderComment\Test\Integration\Model;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Bold\OrderComment\Api\OrderCommentManagementInterface;
use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class OrderCommentManagementTest
 * @package Bold\OrderComment\Test\Integration\Model
 *
 * @magentoDbIsolation enabled
 */
class OrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_bundle.php
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSaveOrderComment()
    {
        $objectManager = Bootstrap::getObjectManager();

        $comment = 'test comment';

        /** @var Quote $quote */
        $quote = $objectManager->create(Quote::class);
        $quote->load('test01', 'reserved_order_id');
        
        $model = $objectManager->create(OrderCommentManagementInterface::class);
        $data = $objectManager->create(OrderCommentInterface::class);

        $data->setComment($comment);
        
        $model->saveOrderComment($quote->getId(), $data);

        $quote->load('test01', 'reserved_order_id');

        self::assertEquals($comment, $quote->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
