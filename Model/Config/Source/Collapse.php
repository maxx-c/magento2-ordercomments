<?php
declare(strict_types=1);

namespace Bold\OrderComment\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Collapse
 * @package Bold\OrderComment\Model\Config\Source
 */
class Collapse implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->toArray();
        $result = [];

        foreach ($options as $value => $label) {
            $result[] = [
                'value' => $value, 'label' => $label
            ];
        }

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            0 => __('Starts with field closed'),
            1 => __('Starts with field opened'),
            2 => __('Render field without collapse')
        ];
    }
}
