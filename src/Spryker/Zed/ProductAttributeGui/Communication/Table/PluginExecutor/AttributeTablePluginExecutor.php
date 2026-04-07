<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Table\PluginExecutor;

use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AttributeTablePluginExecutor implements AttributeTablePluginExecutorInterface
{
    /**
     * @param array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableConfigExpanderPluginInterface> $attributeTableConfigExpanderPlugins
     * @param array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableHeaderExpanderPluginInterface> $attributeTableHeaderExpanderPlugins
     * @param array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableDataExpanderPluginInterface> $attributeTableDataExpanderPlugins
     * @param array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableCriteriaExpanderPluginInterface> $attributeTableCriteriaExpanderPlugins
     */
    public function __construct(
        protected readonly array $attributeTableConfigExpanderPlugins,
        protected readonly array $attributeTableHeaderExpanderPlugins,
        protected readonly array $attributeTableDataExpanderPlugins,
        protected readonly array $attributeTableCriteriaExpanderPlugins,
    ) {
    }

    public function executeTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration
    {
        foreach ($this->attributeTableConfigExpanderPlugins as $attributeTableConfigExpanderPlugin) {
            $config = $attributeTableConfigExpanderPlugin->expandConfig($config);
        }

        return $config;
    }

    /**
     * @return array<string, string>
     */
    public function executeTableHeaderExpanderPlugins(): array
    {
        $expandedData = [];

        foreach ($this->attributeTableHeaderExpanderPlugins as $attributeTableHeaderExpanderPlugin) {
            $expandedData += $attributeTableHeaderExpanderPlugin->expandHeader();
        }

        return $expandedData;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function executeTableDataExpanderPlugins(array $item): array
    {
        $expandedData = [];

        foreach ($this->attributeTableDataExpanderPlugins as $attributeTableDataExpanderPlugin) {
            $expandedData += $attributeTableDataExpanderPlugin->expandData($item);
        }

        return $expandedData;
    }

    public function executeTableCriteriaExpanderPlugins(
        ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer,
    ): ProductAttributeTableCriteriaTransfer {
        foreach ($this->attributeTableCriteriaExpanderPlugins as $attributeTableCriteriaExpanderPlugin) {
            $productAttributeTableCriteriaTransfer = $attributeTableCriteriaExpanderPlugin->expandProductAttributeTableCriteria($productAttributeTableCriteriaTransfer);
        }

        return $productAttributeTableCriteriaTransfer;
    }
}
