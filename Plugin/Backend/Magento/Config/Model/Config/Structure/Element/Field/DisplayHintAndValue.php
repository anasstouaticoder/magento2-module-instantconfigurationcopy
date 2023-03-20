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
use Magento\Framework\View\Element\Template;

class DisplayHintAndValue extends FieldPlugin
{
    /**
     * System path hints path
     */
    public const XML_CONFIG_PATH_ENABLE_HINTS_PATH = 'anasstouaticoder_dev/system_config/system_path_hint';

    /**
     * System field value
     */
    public const XML_CONFIG_PATH_SYSTEM_FIELD_VALUE = 'anasstouaticoder_dev/system_config/system_field_value';

    /**
     * Add path to the field comment
     *
     * @param MagentoField $subject
     * @param string $result
     * @return string
     */
    public function afterGetComment(MagentoField $subject, string $result): string
    {
        if (!in_array(true, $this->config, true)) {
            return $result;
        }
        return $result . $this->getAdditionalHTML($result, $subject);
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
        $fieldPath = $this->getConfigPath($field);
        $block
            ->setTemplate('AnassTouatiCoder_InstantConfigurationCopy::config_field_info.phtml')
            ->setBreakLine(strlen($comment))
            ->addData($this->config)
            ->setPath($fieldPath);

        if (in_array($field->getType(), self::ALLOWED_FIELD_TYPE_LIST)) {
            $block->setFieldId($this->getFieldHTMLId($field))
                ->setFieldType($field->getType());
        }

        return $block->toHtml();
    }

    /**
     * {@inheritdoc }
     */
    protected function initConfig(): void
    {
        $this->config = [
            'display_path' => $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_ENABLE_HINTS_PATH),
            'display_value' => $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_SYSTEM_FIELD_VALUE)
        ];
    }
}
