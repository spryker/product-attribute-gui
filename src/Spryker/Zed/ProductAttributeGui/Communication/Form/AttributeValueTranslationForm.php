<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class AttributeValueTranslationForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE = 'id_product_management_attribute_value';

    /**
     * @var string
     */
    public const FIELD_VALUE = 'value';

    /**
     * @var string
     */
    public const FIELD_TRANSLATION = 'translation';

    /**
     * @var string
     */
    public const FIELD_FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    public const GROUP_VALUE_TRANSLATIONS = 'value_translations_group';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdField($builder)
            ->addValueField($builder)
            ->addTranslationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, HiddenType::class, [
            'label' => null,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALUE, TextType::class, [
            'label' => 'Value',
            'disabled' => true,
            'attr' => [
                'readonly' => true,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATION, TextType::class, [
            'label' => 'Translation',
            'constraints' => [
                new NotBlank([
                    'groups' => static::GROUP_VALUE_TRANSLATIONS,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'value_translation';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
