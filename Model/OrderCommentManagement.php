<?php
declare(strict_types=1);

namespace Bold\OrderComment\Model;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Bold\OrderComment\Api\OrderCommentManagementInterface;
use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class OrderCommentManagement
 * @package Bold\OrderComment\Model
 */
class OrderCommentManagement implements OrderCommentManagementInterface
{
    /**
     * Quote repository.
     *
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *
     * @param CartRepositoryInterface $quoteRepository Quote repository.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $cartId
     * @param OrderCommentInterface $orderComment
     * @return null|string
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws ValidatorException
     */
    public function saveOrderComment(
        $cartId,
        OrderCommentInterface $orderComment
    ) {
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        $comment = $orderComment->getComment();

        $this->validateComment($comment);

        try {
            $quote->setData(OrderComment::COMMENT_FIELD_NAME, strip_tags($comment));
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The order comment could not be saved'));
        }

        return $comment;
    }

    /**
     * @param string $comment
     * @throws ValidatorException
     */
    protected function validateComment($comment)
    {
        $maxLength = $this->scopeConfig->getValue(OrderCommentConfigProvider::CONFIG_MAX_LENGTH);
        if ($maxLength && (mb_strlen($comment) > $maxLength)) {
            throw new ValidatorException(__('Comment is too long'));
        }
    }
}
