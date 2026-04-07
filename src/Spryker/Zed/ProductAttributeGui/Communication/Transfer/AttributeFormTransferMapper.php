<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Symfony\Component\Form\FormInterface;

class AttributeFormTransferMapper implements AttributeFormTransferMapperInterface
{
    /**
     * @param array<\Spryker\Zed\ProductAttributeGuiExtension\Dependency\Plugin\AttributeFormTransferMapperExpanderPluginInterface> $attributeFormTransferMapperExpanderPlugins
     */
    public function __construct(
        protected readonly array $attributeFormTransferMapperExpanderPlugins = [],
    ) {
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $attributeForm
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createTransfer(FormInterface $attributeForm)
    {
        $attributeTransfer = (new ProductManagementAttributeTransfer())
            ->fromArray($attributeForm->getData(), true);

        $values = (array)$attributeForm->get(AttributeForm::FIELD_VALUES)->getData();

        $this->addAttributeValues($attributeTransfer, $values);

        foreach ($this->attributeFormTransferMapperExpanderPlugins as $attributeFormTransferMapperExpanderPlugin) {
            $attributeTransfer = $attributeFormTransferMapperExpanderPlugin->expandTransfer($attributeTransfer, $attributeForm);
        }

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param array $values
     *
     * @return void
     */
    protected function addAttributeValues(ProductManagementAttributeTransfer $attributeTransfer, array $values)
    {
        $attributeTransfer->setValues(new ArrayObject());

        foreach ($values as $value) {
            $attributeValueTransfer = new ProductManagementAttributeValueTransfer();
            $attributeValueTransfer->setValue($value);

            $attributeTransfer->addValue($attributeValueTransfer);
        }
    }
}
