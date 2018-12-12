<?php
declare(strict_types=1);

namespace Bold\OrderComment\Block\Order;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Sales\Model\Order;

/**
 * Class Comment
 * @package Bold\OrderComment\Block\Order
 */
class Comment extends Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Comment constructor.
     * @param TemplateContext $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->_isScopePrivate = true;
        $this->_template = 'order/view/comment.phtml';
        parent::__construct($context, $data);
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * @return string
     */
    public function getOrderComment(): string
    {
        return trim($this->getOrder()->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    /**
     * @return bool
     */
    public function hasOrderComment(): bool
    {
        return strlen($this->getOrderComment()) > 0;
    }

    /**
     * @return string
     */
    public function getOrderCommentHtml(): string
    {
        return nl2br($this->escapeHtml($this->getOrderComment()));
    }
}
