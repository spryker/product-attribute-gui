<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Table\PluginExecutor;

use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface AttributeTablePluginExecutorInterface
{
    public function executeTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration;

    /**
     * @return array<string, string>
     */
    public function executeTableHeaderExpanderPlugins(): array;

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function executeTableDataExpanderPlugins(array $item): array;

    public function executeTableCriteriaExpanderPlugins(
        ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer,
    ): ProductAttributeTableCriteriaTransfer;
}
