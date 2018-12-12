<?php
declare(strict_types=1);

namespace Bold\OrderComment\Model;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Bold\OrderComment\Api\GuestOrderCommentManagementInterface;
use Bold\OrderComment\Api\OrderCommentManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestOrderCommentManagement
 * @package Bold\OrderComment\Model
 */
class GuestOrderCommentManagement implements GuestOrderCommentManagementInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var OrderCommentManagementInterface
     */
    protected $commentManagement;
    
    /**
     * GuestOrderCommentManagement constructor.
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param OrderCommentManagementInterface $commentManagement
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        OrderCommentManagementInterface $commentManagement
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->commentManagement = $commentManagement;
    }

    /**
     * {@inheritDoc}
     */
    public function saveOrderComment(
        $cartId,
        OrderCommentInterface $orderComment
    ) {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->commentManagement->saveOrderComment($quoteIdMask->getQuoteId(), $orderComment);
    }
}
