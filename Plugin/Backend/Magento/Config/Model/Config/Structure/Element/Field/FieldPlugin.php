<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_InstantConfigurationCopy
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\InstantConfigurationCopy\Plugin\Backend\Magento\Config\Model\Config\Structure\Element\Field;

use Magento\Config\Model\Config\Structure\Element\Field as MagentoField;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\BlockFactory;

abstract class FieldPlugin
{
    /** @var string[] */
    public const ALLOWED_FIELD_TYPE_LIST = [
        'select',
        'text',
        'textarea',
        'multiselect'
    ];

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * FieldPlugin constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param BlockFactory $blockFactory
     */
    public function __construct(ScopeConfigInterface $scopeConfig, BlockFactory $blockFactory)
    {
        $this->scopeConfig = $scopeConfig;
        $this->blockFactory = $blockFactory;
        $this->initConfig();
    }

    /**
     * Get active HTML Renderer
     *
     * @return void
     */
    abstract protected function initConfig(): void;

    /**
     * Get field config path
     *
     * @param MagentoField $field
     * @return string|null
     */
    protected function getConfigPath(MagentoField $field): ?string
    {
        return $field->getConfigPath() ?: $field->getPath();
    }

    /**
     * Get Field HTML ID
     *
     * @param MagentoField $field
     * @return array|string|string[]
     */
    protected function getFieldHTMLId(MagentoField $field)
    {
        return str_replace('/', '_', $field->getPath());
    }
}
