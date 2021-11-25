<?php

declare(strict_types=1);

namespace Jdecool\Filesystem\Tests;

/**
 * @template TKey
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements \IteratorAggregate<TKey, TValue>
 */
final class ArrayObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /** @var array<TKey, TValue> */
    private array $data;

    /**
     * @param array<TKey, TValue> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function map(callable $fn): self
    {
        return new self(array_map($fn, $this->data));
    }

    public function flip(): self
    {
        return new self(array_flip($this->data));
    }

    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param TKey $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param TKey $offset
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * @param TKey $offset
     * @param TValue $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function count(): int
    {
        return \count($this->data);
    }

    /**
     * @return \ArrayIterator<int|string, TValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }
}
