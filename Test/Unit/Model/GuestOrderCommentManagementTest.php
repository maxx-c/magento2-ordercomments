<?php
declare(strict_types=1);

namespace Bold\OrderComment\Test\Unit\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Bold\OrderComment\Model\GuestOrderCommentManagement;
use Bold\OrderComment\Model\OrderCommentManagement;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Unit\Model\GuestCart\GuestCartTestHelper;

/**
 * Class GuestOrderCommentManagementTest
 * @package Bold\OrderComment\Test\Unit\Model
 */
class GuestOrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuestOrderCommentManagement
     */
    protected $testObject;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $commentMock;

    /**
     * @var string
     */
    protected $maskedCartId;

    /**
     * @var int
     */
    protected $cartId;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;
    
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        
        $this->quoteRepositoryMock = $this->getMock(CartRepositoryInterface::class);

        $this->quoteMock = $this->getMock(
            Quote::class,
            [
                'getItemsCount',
                'save',
                '__wakeup'
            ],
            [],
            '',
            false
        );
        
        $this->commentMock = $this->getMock(
            OrderCommentManagement::class,
            [],
            [],
            '',
            false
        );

        $this->maskedCartId = 'f216207248d65c789b17be8545e0aa73';
        $this->cartId = 123;

        $guestCartTestHelper = new GuestCartTestHelper($this);
        list($this->quoteIdFactoryMock, $this->quoteIdMock) = $guestCartTestHelper->mockQuoteIdMask(
            $this->maskedCartId,
            $this->cartId
        );

        $this->testObject = $objectManager->getObject(
            GuestOrderCommentManagement::class,
            [
                'orderCommentManagement' => $this->commentMock,
                'quoteIdMaskFactory' => $this->quoteIdFactoryMock
            ]
        );
    }

    public function testSaveComment()
    {
        $comment = 'test comment';

        $orderCommentMock = $this->getMockBuilder(OrderComment::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->commentMock->expects(static::once())
            ->method('saveOrderComment')
            ->with($this->cartId, $orderCommentMock)
            ->willReturn($comment);
        $result = $this->testObject->saveOrderComment($this->maskedCartId, $orderCommentMock);
        static::assertEquals($comment, $result);
    }
}
