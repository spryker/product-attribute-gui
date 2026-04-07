<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductBridge;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_ATTRIBUTE = 'QUERY_CONTAINER_PRODUCT_ATTRIBUTE';

    public const string PLUGINS_ATTRIBUTE_TABLE_CONFIG_EXPANDER = 'PLUGINS_ATTRIBUTE_TABLE_CONFIG_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_TABLE_HEADER_EXPANDER = 'PLUGINS_ATTRIBUTE_TABLE_HEADER_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_TABLE_DATA_EXPANDER = 'PLUGINS_ATTRIBUTE_TABLE_DATA_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_TABLE_CRITERIA_EXPANDER = 'PLUGINS_ATTRIBUTE_TABLE_CRITERIA_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_FORM_EXPANDER = 'PLUGINS_ATTRIBUTE_FORM_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_FORM_DATA_PROVIDER_EXPANDER = 'PLUGINS_ATTRIBUTE_FORM_DATA_PROVIDER_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_FORM_TRANSFER_MAPPER_EXPANDER = 'PLUGINS_ATTRIBUTE_FORM_TRANSFER_MAPPER_EXPANDER';

    public const string PLUGINS_ATTRIBUTE_TABLE_FILTER_FORM_EXPANDER = 'PLUGINS_ATTRIBUTE_TABLE_FILTER_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addLocaleFacade($container);
        $container = $this->addProductAttributeFacade($container);
        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductAttributeQueryContainer($container);
        $container = $this->addAttributeTableConfigExpanderPlugins($container);
        $container = $this->addAttributeTableHeaderExpanderPlugins($container);
        $container = $this->addAttributeTableDataExpanderPlugins($container);
        $container = $this->addAttributeTableCriteriaExpanderPlugins($container);
        $container = $this->addAttributeFormExpanderPlugins($container);
        $container = $this->addAttributeFormDataProviderExpanderPlugins($container);
        $container = $this->addAttributeFormTransferMapperExpanderPlugins($container);
        $container = $this->addAttributeTableFilterFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductAttributeGuiToProductBridge($container->getLocator()->product()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductAttributeGuiToLocaleBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductAttributeGuiToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductAttributeGuiToProductAttributeQueryContainerBridge(
                $container->getLocator()->productAttribute()->queryContainer(),
            );
        });

        return $container;
    }

    protected function addAttributeTableConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_TABLE_CONFIG_EXPANDER, function () {
            return $this->getAttributeTableConfigExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableConfigExpanderPluginInterface>
     */
    protected function getAttributeTableConfigExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeTableHeaderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_TABLE_HEADER_EXPANDER, function () {
            return $this->getAttributeTableHeaderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableHeaderExpanderPluginInterface>
     */
    protected function getAttributeTableHeaderExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeTableDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_TABLE_DATA_EXPANDER, function () {
            return $this->getAttributeTableDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableDataExpanderPluginInterface>
     */
    protected function getAttributeTableDataExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeTableCriteriaExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_TABLE_CRITERIA_EXPANDER, function () {
            return $this->getAttributeTableCriteriaExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableCriteriaExpanderPluginInterface>
     */
    protected function getAttributeTableCriteriaExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_FORM_EXPANDER, function () {
            return $this->getAttributeFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormExpanderPluginInterface>
     */
    protected function getAttributeFormExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeFormDataProviderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_FORM_DATA_PROVIDER_EXPANDER, function () {
            return $this->getAttributeFormDataProviderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormDataProviderExpanderPluginInterface>
     */
    protected function getAttributeFormDataProviderExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeFormTransferMapperExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_FORM_TRANSFER_MAPPER_EXPANDER, function () {
            return $this->getAttributeFormTransferMapperExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormTransferMapperExpanderPluginInterface>
     */
    protected function getAttributeFormTransferMapperExpanderPlugins(): array
    {
        return [];
    }

    protected function addAttributeTableFilterFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ATTRIBUTE_TABLE_FILTER_FORM_EXPANDER, function () {
            return $this->getAttributeTableFilterFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableFilterFormExpanderPluginInterface>
     */
    protected function getAttributeTableFilterFormExpanderPlugins(): array
    {
        return [];
    }
}
