<?php
declare(strict_types=1);
namespace AnassTouatiCoder\InstantConfigurationCopy\Plugin\Backend\Magento\Config\Model\Config\Structure\Element\Field;

use Magento\Config\Model\Config\Structure\Element\Field as MagentoField;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class DisplayOverrideValue extends FieldPlugin
{
    public const SCOPE_TYPE_WEBSITES = 'websites';

    public const SCOPE_TYPE_STORES = 'stores';

    public const XML_CONFIG_PATH_SYSTEM_OVERRIDE_VALUES = 'dev/debug/system_override_values';

    /** @var Escaper */
    private $escaper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param BlockFactory $blockFactory
     * @param Escaper $escaper
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     */
    public function __construct(
        ScopeConfigInterface       $scopeConfig,
        BlockFactory               $blockFactory,
        Escaper                    $escaper,
        StoreManagerInterface      $storeManager,
        RequestInterface           $request
    ) {
        $this->escaper = $escaper;
        $this->storeManager = $storeManager;
        $this->request = $request;
        parent::__construct($scopeConfig, $blockFactory);
    }

    /**
     * Main entry
     *
     * @param MagentoField $subject
     * @param string $result
     * @return string
     */
    public function afterGetLabel(MagentoField $subject, $result)
    {
        if (!$this->config['display_tooltip']) {
            return $result;
        }

        return $result . $this->getToolTipHTML($subject);
    }

    /**
     * Get Tooltip html output
     *
     * @param MagentoField $field
     * @return string
     */
    private function getToolTipHTML(MagentoField $field)
    {
        $tooltip = '';
        $lines = [];

        foreach ($this->storeManager->getWebsites(false) as $website) {
            if ($this->getWebsiteParam() || $this->getStoreParam()) {
                continue;
            }
            // Only show website specific values in default scope
            if ($scopeLine = $this->getScopeHint($field, self::SCOPE_TYPE_WEBSITES, $website)) {
                $lines[] = $scopeLine;
            }
        }
        foreach ($this->storeManager->getStores(false) as $store) {
            if ($this->getStoreParam()) {
                continue;
            }
            if (($websiteId = $this->getWebsiteParam()) && ($store->getWebsiteId() != $websiteId)) {
                continue;
            }
            // show store specific values in default scope and in parent website scope
            if ($scopeLine = $this->getScopeHint($field, self::SCOPE_TYPE_STORES, $store)) {
                $lines[] = $scopeLine;
            }
        }
        if (count($lines) > 0) {
            $tooltipContent = implode('<br />', $lines);
            $tooltip = $this->blockFactory->createBlock(Template::class)
                ->setTemplate('AnassTouatiCoder_InstantConfigurationCopy::override_tooltip.phtml')
                ->setToolTipContent($tooltipContent)
                ->toHtml();
        }

        return $tooltip;
    }

    /**
     * {@inheritdoc }
     */
    protected function initConfig(): void
    {
        $this->config = [
            'display_tooltip' => $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_SYSTEM_OVERRIDE_VALUES)
        ];
    }

    /**
     * Get scope data
     *
     * @param MagentoField $field
     * @param string $scopeType
     * @param $scope
     * @return Phrase|string
     */
    private function getScopeHint(MagentoField $field, string $scopeType, $scope)
    {
        $path = $this->getConfigPath($field);
        $scopeLine = '';
        if ($websiteId = $this->getWebsiteParam()) {
            $currentValue = $this->scopeConfig->getValue(
                $path,
                ScopeInterface::SCOPE_WEBSITE,
                $websiteId
            );
        } else {
            $currentValue = $this->scopeConfig->getValue($path);
        }
        $scopeValue = $this->scopeConfig->getValue($path, $scopeType, $scope->getId());

        if (is_array($currentValue) || is_array($scopeValue)) {
            return $scopeLine;
        }

        $currentValue = (string)$currentValue;
        $scopeValue = (string)$scopeValue;

        if ($scopeValue !== $currentValue) {
            $scopeValue = $this->escaper->escapeHtml($scopeValue);

            switch ($scopeType) {
                case self::SCOPE_TYPE_STORES:
                    return __(
                        'Store <code>%1</code>: <br/>"%2"',
                        $scope->getCode(),
                        $this->getValueLabel($field, $scopeValue)
                    );
                case self::SCOPE_TYPE_WEBSITES:
                    return __(
                        'Website <code>%1</code>: <br/>"%2"',
                        $scope->getCode(),
                        $this->getValueLabel($field, $scopeValue)
                    );
            }
        }
        return $scopeLine;
    }

    /**
     * Get Value
     *
     * @param MagentoField $field
     * @param string $scopeValue
     * @return string
     */
    private function getValueLabel(MagentoField $field, string $scopeValue): string
    {
        $scopeValue = trim($scopeValue);
        if ($field->hasOptions()) {
            if ($field->getType() === 'multiselect' && strpos($scopeValue, ',') !== false) {
                return implode(
                    ', ',
                    array_map(
                        function ($key) use ($field) {
                            return $this->getValueLabel($field, $key);
                        },
                        explode(',', $scopeValue)
                    )
                );
            }
            foreach ($field->getOptions() as $option) {
                if (is_array($option) && $option['value'] == $scopeValue) {
                    return $option['label'];
                }
            }
        }
        return $scopeValue;
    }

    /**
     * Get Website ID parameter from request
     *
     * @return string|null
     */
    private function getWebsiteParam(): ?string
    {
        return $this->request->getParam('website');
    }

    /**
     * Get store ID parameter from request
     *
     * @return string|null
     */
    private function getStoreParam(): ?string
    {
        return $this->request->getParam('store');
    }
}
