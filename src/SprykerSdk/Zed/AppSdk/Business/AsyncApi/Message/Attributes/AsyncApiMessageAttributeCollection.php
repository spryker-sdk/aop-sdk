<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes;

class AsyncApiMessageAttributeCollection implements AsyncApiMessageAttributeCollectionInterface
{
    /**
     * @var array<(\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)>
     */
    protected array $attributes;

    /**
     * @param array<(\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)> $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return iterable<(\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)>
     */
    public function getAttributes(): iterable
    {
        foreach ($this->attributes as $attributeName => $attribute) {
            yield $attributeName => $attribute;
        }
    }

    /**
     * @param string $attributeName
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null
     */
    public function getAttribute(string $attributeName)
    {
        return $this->attributes[$attributeName] ?? null;
    }
}
