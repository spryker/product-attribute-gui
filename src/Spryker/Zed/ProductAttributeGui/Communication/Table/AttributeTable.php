<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Table;

use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductAttributeGui\Communication\Table\PluginExecutor\AttributeTablePluginExecutorInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class AttributeTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';

    /**
     * @var string
     */
    public const COL_INPUT_TYPE = 'input_type';

    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    protected const string DEFAULT_COMBINE_OPERATOR = 'and';

    protected ?ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer = null;

    public function __construct(
        protected readonly ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        protected readonly AttributeTablePluginExecutorInterface $pluginExecutor,
    ) {
    }

    /**
     * @return $this
     */
    public function applyCriteria(ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer)
    {
        $this->productAttributeTableCriteriaTransfer = $productAttributeTableCriteriaTransfer;

        return $this;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('/table', $this->getRequest()->query->all());
        $config->setUrl($url);

        $baseHeader = [
            static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => 'Attribute ID',
            SpyProductAttributeKeyTableMap::COL_KEY => 'Attribute Key',
            SpyProductAttributeKeyTableMap::COL_IS_SUPER => 'Super Attribute',
            static::COL_INPUT_TYPE => 'Type',
        ];

        $expandedHeader = $this->pluginExecutor->executeTableHeaderExpanderPlugins();

        $header = $baseHeader + $expandedHeader + [static::COL_ACTIONS => 'Actions'];

        $config->setHeader($header);

        $config->setRawColumns(
            array_merge(array_keys($expandedHeader), [static::COL_ACTIONS]),
        );

        $config->setSearchable([
            SpyProductAttributeKeyTableMap::COL_KEY,
            SpyProductManagementAttributeTableMap::COL_INPUT_TYPE,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE,
            SpyProductAttributeKeyTableMap::COL_KEY,
            SpyProductAttributeKeyTableMap::COL_IS_SUPER,
            static::COL_INPUT_TYPE,
        ]);

        $config = $this->pluginExecutor->executeTableConfigExpanderPlugins($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productAttributeQueryContainer
            ->queryProductAttributeKey()
            ->joinSpyProductManagementAttribute()
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, static::COL_INPUT_TYPE);

        $productAttributeTableCriteriaTransfer = $this->pluginExecutor->executeTableCriteriaExpanderPlugins(
            $this->productAttributeTableCriteriaTransfer ?? new ProductAttributeTableCriteriaTransfer(),
        );

        $query = $this->applyTableCriteria($query, $productAttributeTableCriteriaTransfer);

        $queryResults = $this->runQuery($query, $config);

        $productAbstractCollection = [];
        foreach ($queryResults as $item) {
            $row = [
                static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $this->formatInt($item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE]),
                SpyProductAttributeKeyTableMap::COL_KEY => $item[SpyProductAttributeKeyTableMap::COL_KEY],
                SpyProductAttributeKeyTableMap::COL_IS_SUPER => $item[SpyProductAttributeKeyTableMap::COL_IS_SUPER],
                static::COL_INPUT_TYPE => $item[static::COL_INPUT_TYPE],
            ];

            $row += $this->pluginExecutor->executeTableDataExpanderPlugins($item);
            $row[static::COL_ACTIONS] = implode(' ', $this->createActionColumn($item));

            $productAbstractCollection[] = $row;
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $productAttributeKeyQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function applyTableCriteria(
        SpyProductAttributeKeyQuery $productAttributeKeyQuery,
        ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer
    ) {
        foreach ($productAttributeTableCriteriaTransfer->getWithColumns() as $column => $alias) {
            $productAttributeKeyQuery->withColumn($column, $alias);
        }

        $queryConditions = $productAttributeTableCriteriaTransfer->getQueryConditions();

        if (!$queryConditions->count()) {
            return $productAttributeKeyQuery;
        }

        $conditionNames = [];

        foreach ($queryConditions as $index => $queryConditionTransfer) {
            $conditionName = sprintf('criteria_condition_%d', $index);
            $productAttributeKeyQuery->condition($conditionName, $queryConditionTransfer->getExpressionOrFail(), $queryConditionTransfer->getValueOrFail());
            $conditionNames[] = $conditionName;
        }

        $productAttributeKeyQuery->combine($conditionNames, $productAttributeTableCriteriaTransfer->getConditionCombineOperator() ?? static::DEFAULT_COMBINE_OPERATOR);

        return $productAttributeKeyQuery;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-attribute-gui/attribute/view', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'View',
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-attribute-gui/attribute/edit', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'Edit',
        );

        return $urls;
    }
}
