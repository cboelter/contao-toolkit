<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Dca\Formatter\Value;

use Contao\StringUtil;

/**
 * DeserializeFormatter deserialize any value.
 */
final class DeserializeFormatter implements ValueFormatter
{
    /** {@inheritDoc} */
    public function accepts(string $fieldName, array $fieldDefinition): bool
    {
        return true;
    }

    /** {@inheritDoc} */
    public function format(mixed $value, string $fieldName, array $fieldDefinition, mixed $context = null): mixed
    {
        return StringUtil::deserialize($value);
    }
}
