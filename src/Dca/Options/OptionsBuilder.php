<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Dca\Options;

use Contao\Database\Result;
use Contao\Model\Collection;

use function array_merge;
use function is_callable;
use function str_repeat;

/**
 * Class OptionsBuilder is designed to transfer data to the requested format for options.
 */
final class OptionsBuilder
{
    /**
     * The options.
     */
    private Options $options;

    /**
     * Get Options builder for collection.
     *
     * @param Collection      $collection  Model collection.
     * @param string|callable $labelColumn Label column or callback.
     * @param string          $valueColumn Value column.
     *
     * @return OptionsBuilder
     */
    public static function fromCollection(
        ?Collection $collection = null,
        $labelColumn = null,
        string $valueColumn = 'id'
    ): self {
        if ($collection === null) {
            return new static(new ArrayListOptions([], $labelColumn, $valueColumn));
        }

        $options = new CollectionOptions($collection, $labelColumn, $valueColumn);

        return new static($options);
    }

    /**
     * Get Options builder for collection.
     *
     * @param Result|null     $result      Database result.
     * @param string|callable $labelColumn Label column or callback.
     * @param string          $valueColumn Value column.
     *
     * @return OptionsBuilder
     */
    public static function fromResult(?Result $result = null, $labelColumn = null, string $valueColumn = 'id'): self
    {
        if (! $result) {
            return static::fromArrayList([], $labelColumn, $valueColumn);
        }

        return static::fromArrayList($result->fetchAllAssoc(), $labelColumn, $valueColumn);
    }

    /**
     * Create options from array list.
     *
     * It expects an array which is a list of associative arrays where the value column is part of the associative
     * array and has to be extracted.
     *
     * @param list<array<string,mixed>> $data     Raw data list.
     * @param string|callable           $labelKey Label key or callback.
     * @param string                    $valueKey Value key.
     *
     * @return OptionsBuilder
     */
    public static function fromArrayList(array $data, $labelKey = null, string $valueKey = 'id'): self
    {
        $options = new ArrayListOptions($data, $labelKey, $valueKey);

        return new static($options);
    }

    /**
     * Construct.
     *
     * @param Options $options The options.
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Group options by a specific column.
     *
     * @param string $column   Column name.
     * @param null   $callback Optional callback.
     *
     * @return $this
     */
    public function groupBy(string $column, $callback = null): self
    {
        $options = [];

        foreach ($this->options as $key => $value) {
            $row   = $this->options->row();
            $group = $this->groupValue($row[$column], $callback, $row);

            $options[$group][$key] = $value;
        }

        $this->options = new ArrayOptions($options);

        return $this;
    }

    /**
     * Get options as tree.
     *
     * @param string $parent   Column which stores parent value.
     * @param string $indentBy Indent entry by this value.
     *
     * @return $this
     */
    public function asTree(string $parent = 'pid', string $indentBy = '-- '): self
    {
        $options = [];
        $values  = [];

        foreach ($this->options as $key => $value) {
            $pid = $this->options[$key][$parent];

            $values[$pid][$key] = array_merge($this->options[$key], ['__label__' => $value]);
        }

        $this->buildTree($values, $options, 0, $indentBy);

        $this->options = new ArrayOptions($options);

        return $this;
    }

    /**
     * Get the build options.
     *
     * @return array<string,string|array<string,string>>
     */
    public function getOptions(): array
    {
        return $this->options->getArrayCopy();
    }

    /**
     * Get the group value.
     *
     * @param mixed               $value    Raw group value.
     * @param callable|null       $callback Optional callback.
     * @param array<string,mixed> $row      Current data row.
     *
     * @return mixed
     */
    private function groupValue($value, $callback, array $row)
    {
        if (is_callable($callback)) {
            return $callback($value, $row);
        }

        return $value;
    }

    /**
     * Build options tree.
     *
     * @param array<int,array<int,mixed>> $values   The values.
     * @param array<int|string, string>   $options  The created options.
     * @param int                         $index    The current index.
     * @param string                      $indentBy The indent characters.
     * @param int                         $depth    The current depth.
     *
     * @return array<int|string, string>
     */
    private function buildTree(array &$values, array &$options, int $index, string $indentBy, int $depth = 0): array
    {
        if (empty($values[$index])) {
            return $options;
        }

        foreach ($values[$index] as $key => $value) {
            $options[$key] = str_repeat($indentBy, $depth) . ' ' . $value['__label__'];
            $this->buildTree($values, $options, $key, $indentBy, $depth + 1);
        }

        return $options;
    }
}
