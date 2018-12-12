<?php
declare(strict_types=1);

namespace Bold\OrderComment\Plugin\Block\Adminhtml;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Sales\Block\Adminhtml\Order\View\Info;

/**
 * Class SalesOrderViewInfo
 * @package Bold\OrderComment\Plugin\Block\Adminhtml
 */
class SalesOrderViewInfo
{
    /**
     * @param Info $subject
     * @param string $result
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterToHtml(
        Info $subject,
        $result
    ) {
        $commentBlock = $subject->getLayout()->getBlock('order_comments');
        if ($commentBlock !== false && $subject->getNameInLayout() == 'order_info') {
            $commentBlock->setOrderComment($subject->getOrder()->getData(OrderComment::COMMENT_FIELD_NAME));
            $result = $result . $commentBlock->toHtml();
        }
        
        return $result;
    }
}
