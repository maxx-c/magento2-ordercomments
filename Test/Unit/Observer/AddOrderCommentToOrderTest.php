<?php
namespace Bold\OrderComment\Test\Unit\Observer;

use Bold\OrderComment\Observer\AddOrderCommentToOrder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Bold\OrderComment\Model\Data\OrderComment;

/**
 * Class AddOrderCommentToOrderTest
 * @package Bold\OrderComment\Test\Unit\Observer
 */
class AddOrderCommentToOrderTest extends \PHPUnit_Framework_TestCase
{
    protected $objectManager;

    /**
     * @var AddOrderCommentToOrder
     */
    protected $observer;
    
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->observer = new AddOrderCommentToOrder();
    }
    
    public function testExecute()
    {
        $comment = 'test comment';

        $observerMock = $this->getMock('Magento\Framework\Event\Observer', [], [], '', false);
        $eventMock = $this->getMock('Magento\Framework\Event', ['getData'], [], '', false);

        $quoteMock = $this->getMock('Magento\Quote\Model\Quote', ['getData'], [], '', false);
        $orderMock = $this->getMock('Magento\Sales\Model\Order', null, [], '', false);

        $map = [
            ['quote', null, $quoteMock],
            ['order', null, $orderMock]
        ];
        
        $observerMock->expects($this->atLeastCount(2))
            ->method('getEvent')
            ->willReturn($eventMock);
        $eventMock->expects($this->atLeastCount(2))
            ->method('getData')
            ->will(static::returnValueMap($map));

        $quoteMock->expects(static::atLeastOnce())
            ->method('getData')
            ->with(OrderComment::COMMENT_FIELD_NAME)
            ->willReturn($comment);
        
        $this->observer->execute($observerMock);

        static::assertEquals($comment, $orderMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    /**
     * @param $num
     * @return \PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastCount
     */
    public function atLeastCount($num)
    {
        return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastCount($num);
    }
}
