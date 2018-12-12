<?php
declare(strict_types=1);

namespace Bold\OrderComment\Observer;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddOrderCommentToOrder
 * @package Bold\OrderComment\Observer
 */
class AddOrderCommentToOrder implements ObserverInterface
{
    /**
     * transfer the order comment from the quote object to the order object during the
     * sales_model_service_quote_submit_before event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        
        /** @var \Magento\Quote\Model\Quote $quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $order->setData(OrderComment::COMMENT_FIELD_NAME, $quote->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
