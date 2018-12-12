<?php
declare(strict_types=1);

namespace Bold\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class OrderCommentConfigProvider
 * @package Bold\OrderComment\Model
 */
class OrderCommentConfigProvider implements ConfigProviderInterface
{
    const CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';
    
    const CONFIG_FIELD_COLLAPSE_STATE = 'sales/ordercomments/collapse_state';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * OrderCommentConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'max_length' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_LENGTH),
            'comment_initial_collapse_state' => (int) $this->scopeConfig->getValue(self::CONFIG_FIELD_COLLAPSE_STATE)
        ];
    }
}
