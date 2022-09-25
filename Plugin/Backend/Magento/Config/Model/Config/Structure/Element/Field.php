<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_DiscountNotice
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\InstantConfigurationCopy\Plugin\Backend\Magento\Config\Model\Config\Structure\Element;

use Magento\Config\Model\Config\Structure\Element\Field as MagentoField;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Framework\View\Element\Template;

class Field
{
    /**
     * System path hints path
     */
    const XML_CONFIG_PATH_ENABLE_HINTS_PATH = 'dev/debug/system_path_hints';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * FieldPlugin constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig, BlockFactory $blockFactory)
    {
        $this->scopeConfig = $scopeConfig;
        $this->blockFactory = $blockFactory;
    }

    /**
     * Add path to the field comment
     *
     * @param \Magento\Config\Model\Config\Structure\Element\Field $subject
     * @param string $result
     * @return string
     */
    public function afterGetComment(MagentoField $subject, string $result): string
    {
        if (!$this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_ENABLE_HINTS_PATH)) {
            return $result;
        }

        return $result .= $this->getConfigHintHTML($result, $subject);
    }

    /**
     * @param $comment
     * @param $field
     * @return string
     */
    public function getConfigHintHTML($comment, $field)
    {
        $block = $this->blockFactory->createBlock(Template::class);
        $block->setTemplate('AnassTouatiCoder_InstantConfigurationCopy::config_path_hints.phtml')
            ->setBreakLine(strlen($comment))
            ->setPath($field->getConfigPath() ?? $field->getPath());

        return $block->toHtml();
    }
}

