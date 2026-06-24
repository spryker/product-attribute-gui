<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttributeGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const DEFAULT_LOCALE = '_';

    /**
     * @api
     *
     * @var string
     */
    public const KEY = 'key';

    /**
     * @api
     *
     * @var string
     */
    public const IS_SUPER = 'is_super';

    /**
     * @api
     *
     * @var string
     */
    public const ATTRIBUTE_ID = 'attribute_id';

    /**
     * @api
     *
     * @var string
     */
    public const ALLOW_INPUT = 'allow_input';

    /**
     * @api
     *
     * @var string
     */
    public const INPUT_TYPE = 'input_type';

    /**
     * @api
     *
     * @var string
     */
    public const ID_PRODUCT_ATTRIBUTE_KEY = 'id_product_attribute_key';

    /**
     * @api
     *
     * @var string
     */
    public const LOCALE_CODE = 'locale_code';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultLocaleCode()
    {
        return static::DEFAULT_LOCALE;
    }
}
