<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_InstantConfigurationCopy
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
    /** @var string[]  */
    public const ALLOWED_FIELD_TYPE_LIST = [
        'select',
        'text',
        'textarea',
        'multiselect'
    ];

    /**
     * System path hints path
     */
    public const XML_CONFIG_PATH_ENABLE_HINTS_PATH = 'dev/debug/system_path_hint';

    /**
     * System field value
     */
    public const XML_CONFIG_PATH_SYSTEM_FIELD_VALUE = 'dev/debug/system_field_value';

    /**
     * @var array
     */
    protected $config;

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
     * @param BlockFactory $blockFactory
     */
    public function __construct(ScopeConfigInterface $scopeConfig, BlockFactory $blockFactory)
    {
        $this->scopeConfig = $scopeConfig;
        $this->blockFactory = $blockFactory;
        $this->initConfig();
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
        if (!in_array(true, $this->config, true)) {
            return $result;
        }
        return $result .= $this->getAdditionalHTML($result, $subject);
    }

    /**
     * HTML Renderer
     *
     * @param string $comment
     * @param MagentoField $field
     * @return string
     */
    public function getAdditionalHTML(string $comment, MagentoField $field): string
    {

        $block = $this->blockFactory->createBlock(Template::class);
        $block
            ->setTemplate('AnassTouatiCoder_InstantConfigurationCopy::config_field_info.phtml')
            ->setBreakLine(strlen($comment))
            ->addData($this->config)
            ->setPath($field->getConfigPath() ?? $field->getPath());

        if (in_array($field->getType(), self::ALLOWED_FIELD_TYPE_LIST)) {
            $fieldId = str_replace('/', '_', $field->getPath());
            $block->setFieldId($fieldId)
                ->setFieldType($field->getType());
        }

        return $block->toHtml();
    }

    /**
     * HTML Renderer
     *
     * @return void
     */
    protected function initConfig(): void
    {
        $this->config = [
            'display_path' => $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_ENABLE_HINTS_PATH),
            'display_value' => $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_SYSTEM_FIELD_VALUE)
        ];
    }
}
