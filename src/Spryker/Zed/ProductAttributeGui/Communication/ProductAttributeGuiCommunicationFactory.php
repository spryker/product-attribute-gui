<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication;

use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeCsrfForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyFormDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Form\TableFilterForm;
use Spryker\Zed\ProductAttributeGui\Communication\Table\AttributeTable;
use Spryker\Zed\ProductAttributeGui\Communication\Table\PluginExecutor\AttributeTablePluginExecutor;
use Spryker\Zed\ProductAttributeGui\Communication\Table\PluginExecutor\AttributeTablePluginExecutorInterface;
use Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeFormTransferMapper;
use Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeFormTransferMapperInterface;
use Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeTranslationFormTransferMapper;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface
     */
    public function getProductAttributeFacade()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyFormDataProviderInterface
     */
    public function createAttributeKeyFormDataProvider()
    {
        return new AttributeKeyFormDataProvider();
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeKeyForm(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()
            ->create(
                AttributeKeyForm::class,
                $formData,
                $formOptions,
            );
    }

    /**
     * @deprecated Use {@link getAttributeForm()} instead.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttributeForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(AttributeForm::class, $data, $options);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(AttributeForm::class, $data, $options);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeCsrfForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(AttributeCsrfForm::class, $data, $options);
    }

    public function createAttributeFormDataProvider(): AttributeFormDataProvider
    {
        return new AttributeFormDataProvider(
            $this->getProductAttributeQueryContainer(),
            $this->getProductAttributeFacade(),
            $this->getAttributeFormDataProviderExpanderPlugins(),
        );
    }

    public function createAttributeTable(): AttributeTable
    {
        return new AttributeTable(
            $this->getProductAttributeQueryContainer(),
            $this->createAttributeTablePluginExecutor(),
        );
    }

    public function createAttributeTablePluginExecutor(): AttributeTablePluginExecutorInterface
    {
        return new AttributeTablePluginExecutor(
            $this->getAttributeTableConfigExpanderPlugins(),
            $this->getAttributeTableHeaderExpanderPlugins(),
            $this->getAttributeTableDataExpanderPlugins(),
            $this->getAttributeTableCriteriaExpanderPlugins(),
        );
    }

    public function createTableFilterForm(
        ProductAttributeTableCriteriaTransfer $productAttributeTableCriteriaTransfer,
    ): FormInterface {
        return $this->getFormFactory()->create(TableFilterForm::class, $productAttributeTableCriteriaTransfer);
    }

    public function createAttributeFormTransferGenerator(): AttributeFormTransferMapperInterface
    {
        return new AttributeFormTransferMapper(
            $this->getAttributeFormTransferMapperExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider
     */
    public function createAttributeTranslationFormCollectionDataProvider()
    {
        return new AttributeTranslationFormCollectionDataProvider(
            $this->getProductAttributeFacade(),
            $this->getProductAttributeQueryContainer(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @deprecated Use {@link getAttributeTranslationFormCollection()} instead.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttributeTranslationFormCollection(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(AttributeTranslationCollectionForm::class, $data, $options);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeTranslationFormCollection(array $data = [], array $options = [])
    {
        return $this->createAttributeTranslationFormCollection($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createAttributeTranslationFormCollectionType()
    {
        return AttributeTranslationCollectionForm::class;
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeTranslationFormTransferMapper
     */
    public function createAttributeTranslationFormTransferGenerator()
    {
        return new AttributeTranslationFormTransferMapper();
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createAttributeFormType()
    {
        return AttributeForm::class;
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    public function getProductAttributeQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableConfigExpanderPluginInterface>
     */
    public function getAttributeTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableHeaderExpanderPluginInterface>
     */
    public function getAttributeTableHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_TABLE_HEADER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableDataExpanderPluginInterface>
     */
    public function getAttributeTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_TABLE_DATA_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableCriteriaExpanderPluginInterface>
     */
    public function getAttributeTableCriteriaExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_TABLE_CRITERIA_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormExpanderPluginInterface>
     */
    public function getAttributeFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_FORM_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormDataProviderExpanderPluginInterface>
     */
    public function getAttributeFormDataProviderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_FORM_DATA_PROVIDER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormTransferMapperExpanderPluginInterface>
     */
    public function getAttributeFormTransferMapperExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_FORM_TRANSFER_MAPPER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeTableFilterFormExpanderPluginInterface>
     */
    public function getAttributeTableFilterFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::PLUGINS_ATTRIBUTE_TABLE_FILTER_FORM_EXPANDER);
    }
}
