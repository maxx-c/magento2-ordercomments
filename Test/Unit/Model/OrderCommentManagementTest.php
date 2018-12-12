<?php
declare(strict_types=1);

namespace Bold\OrderComment\Test\Unit\Model;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Bold\OrderComment\Model\Data\OrderComment;
use Bold\OrderComment\Model\OrderCommentConfigProvider;
use Bold\OrderComment\Model\OrderCommentManagement;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class OrderCommentManagementTest
 * @package Bold\OrderComment\Test\Unit\Model
 */
class OrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|QuoteRepository
     */
    protected $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Quote
     */
    protected $quoteMock;

    /**
     * @var OrderCommentManagement
     */
    protected $testObject;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ScopeConfigInterface
     */
    protected $configMock;

    public function setUp()
    {
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
        $this->configMock = $this->getMockForAbstractClass(
            ScopeConfigInterface::class
        );

        $this->testObject = new OrderCommentManagement($this->quoteRepositoryMock, $this->configMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Cart 123 doesn't contain products
     */
    public function testSaveCommentWithEmptyCart()
    {
        $this->setupQuoteRepositoryMockQueries(123, 0);
        $this->testObject->saveOrderComment(123, $this->mockOrderComment());
    }

    /**
     * @expectedException CouldNotSaveException
     * @expectedExceptionMessage The order comment could not be saved
     */
    public function testSaveCommentWhenCouldNotSaveQuote()
    {
        $cartId = 123;
        $cartItemCount = 12;

        $this->setupQuoteRepositoryMockQueries($cartId, $cartItemCount);
        
        $exceptionMessage = 'The order comment could not be saved';
        $exception = new CouldNotSaveException(__($exceptionMessage));
        $this->quoteRepositoryMock->expects(static::once())
            ->method('save')
            ->with($this->quoteMock)
            ->willThrowException($exception);

        $this->testObject->saveOrderComment($cartId, $this->mockOrderComment());
    }
    
    /**
     * @expectedException \Magento\Framework\Exception\ValidatorException
     * @expectedExceptionMessage Comment is too long
     */
    public function testSaveCommentThatIsTooLong()
    {
        $cartId = 123;
        $cartItemCount = 12;
        $comment = '123456789';
        $this->configMock
            ->method('getValue')
            ->with(OrderCommentConfigProvider::CONFIG_MAX_LENGTH)
            ->willReturn(8);

        $this->setupQuoteRepositoryMockQueries($cartId, $cartItemCount);
        $this->quoteRepositoryMock->expects(static::never())
            ->method('save');

        $this->testObject->saveOrderComment($cartId, $this->mockOrderComment($comment));
    }

    public function testSaveComment()
    {
        $cartId = 123;
        $comment = 'test comment';
        $cartItemCount = 12;

        $this->setupQuoteRepositoryMockQueries($cartId, $cartItemCount);
        $this->quoteRepositoryMock->expects(static::once())
            ->method('save')
            ->with($this->quoteMock)
            ->will(static::returnSelf());

        $this->testObject->saveOrderComment($cartId, $this->mockOrderComment($comment));

        static::assertEquals($comment, $this->quoteMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    public function testSaveCommentWithTags()
    {
        $cartId = 123;
        $cartItemCount = 12;
        $comment = 'test comment<script>alert("abcd");</script><?php die("qwerty")?>';

        $this->setupQuoteRepositoryMockQueries($cartId, $cartItemCount);
        $this->quoteRepositoryMock->expects(static::once())
            ->method('save')
            ->with($this->quoteMock)
            ->will(static::returnSelf());

        $this->testObject->saveOrderComment($cartId, $this->mockOrderComment($comment));

        static::assertEquals(strip_tags($comment), $this->quoteMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    /**
     * @param int $cartId
     * @param int $cartItemCount
     */
    private function setupQuoteRepositoryMockQueries(int $cartId, int $cartItemCount)
    {
        $this->quoteRepositoryMock->expects(static::once())
            ->method('getActive')->with($cartId)->will(static::returnValue($this->quoteMock));
        $this->quoteMock->expects(static::once())->method('getItemsCount')->will(static::returnValue($cartItemCount));
    }

    /**
     * @param string|null $comment
     * @return \PHPUnit_Framework_MockObject_MockObject|OrderCommentInterface
     */
    private function mockOrderComment(string $comment = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $orderCommentMock = $this->getMockBuilder(OrderComment::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($comment !== null) {
            $orderCommentMock->expects(static::once())
                ->method('getComment')
                ->willReturn($comment);
        }
        return $orderCommentMock;
    }
}
