<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class TableFilterForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAttributeTableCriteriaTransfer::class,
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);
        $attributeTableFilterFormExpanderPlugins = $this->getFactory()->getAttributeTableFilterFormExpanderPlugins();

        foreach ($attributeTableFilterFormExpanderPlugins as $attributeTableFilterFormExpanderPlugin) {
            $options = array_merge($options, $attributeTableFilterFormExpanderPlugin->getOptions());
        }

        foreach ($attributeTableFilterFormExpanderPlugins as $attributeTableFilterFormExpanderPlugin) {
            $attributeTableFilterFormExpanderPlugin->buildForm($builder, $options);
        }
    }
}
