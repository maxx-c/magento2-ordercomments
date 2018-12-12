<?php
declare(strict_types=1);

namespace Bold\OrderComment\Plugin\Model\Order;

use Magento\Sales\Api\Data\OrderSearchResultInterface;
use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use \Magento\Sales\Model\OrderFactory;
use \Magento\Sales\Api\Data\OrderExtensionFactory;

/**
 * Class LoadOrderComment
 * @package Bold\OrderComment\Plugin\Model\Order
 */
class LoadOrderComment
{
    private $orderFactory;

    private $orderExtensionFactory;

    /**
     * LoadOrderComment constructor.
     * @param OrderFactory $orderFactory
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderExtensionFactory $extensionFactory
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderExtensionFactory = $extensionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $resultOrder
    ) {
        $this->setOrderComment($resultOrder);
        return $resultOrder;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orderSearchResult
     * @return OrderSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orderSearchResult
    ) {
        foreach ($orderSearchResult->getItems() as $order) {
            $this->setOrderComment($order);
        }
        return $orderSearchResult;
    }

    /**
     * @param OrderInterface $order
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function setOrderComment(OrderInterface $order)
    {
        if ($order instanceof Order) {
            $value = $order->getBoldOrderComment();
        } else {
            $temp = $this->getOrderFactory()->create();
            $temp->load($order->getId());
            $value = $temp->getBoldOrderComment();
        }

        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->getOrderExtensionFactory()->create();
        $orderExtension->setBoldOrderComment($value);
        $order->setExtensionAttributes($orderExtension);
    }

    /**
     * @return OrderFactory
     */
    public function getOrderFactory()
    {
        return $this->orderFactory;
    }

    /**
     * @return OrderExtensionFactory
     */
    public function getOrderExtensionFactory()
    {
        return $this->orderExtensionFactory;
    }
}
