<?php
declare(strict_types=1);

namespace Bold\OrderComment\Model\Data;

use Bold\OrderComment\Api\Data\OrderCommentInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class OrderComment
 * @package Bold\OrderComment\Model\Data
 */
class OrderComment extends AbstractSimpleObject implements OrderCommentInterface
{
    const COMMENT_FIELD_NAME = 'bold_order_comment';
    
    /**
     * @return string|null
     */
    public function getComment()
    {
        return $this->_get(static::COMMENT_FIELD_NAME);
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        return $this->setData(static::COMMENT_FIELD_NAME, $comment);
    }
}
