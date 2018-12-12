<?php
declare(strict_types=1);

namespace Bold\OrderComment\Api\Data;

/**
 * Interface OrderCommentInterface
 * @package Bold\OrderComment\Api\Data
 */
interface OrderCommentInterface
{
    /**
     * @return string|null
     */
    public function getComment();

    /**
     * @param string $comment
     * @return null
     */
    public function setComment($comment);
}
