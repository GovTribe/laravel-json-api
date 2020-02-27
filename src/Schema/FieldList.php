<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

use CloudCreativity\LaravelJsonApi\Contracts\Schema\FieldInterface;
use Countable;
use Generator;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use JsonSerializable;
use OutOfBoundsException;
use function collect;

class FieldList implements IteratorAggregate, Countable, Arrayable, JsonSerializable
{

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var array
     */
    private $relations;

    /**
     * FieldList constructor.
     *
     * @param FieldInterface ...$fields
     */
    public function __construct(FieldInterface ...$fields)
    {
        $this->attributes = [];
        $this->relations = [];

        foreach ($fields as $field) {
            if ($field instanceof Relation) {
                $this->relations[$field->name()] = $field;
                continue;
            }

            $this->attributes[$field->name()] = $field;
        }

        ksort($this->attributes);
        ksort($this->relations);
    }

    /**
     * Get a field by name.
     *
     * @param string $name
     * @return FieldInterface
     */
    public function field(string $name): FieldInterface
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        if (isset($this->relations[$name])) {
            return $this->relations[$name];
        }

        throw new OutOfBoundsException("Field {$name} does not exist.");
    }

    /**
     * Return a generator to iterate over attributes.
     *
     * @return Generator
     */
    public function attributes(): Generator
    {
        yield from $this->attributes;
    }

    /**
     * Return a generator to iterate over relationships.
     *
     * @return Generator
     */
    public function relations(): Generator
    {
        yield from $this->relations;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        yield from $this->attributes();
        yield from $this->relations();
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->attributes) + count($this->relations);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return collect($this)->toArray();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return collect($this)->jsonSerialize();
    }

}
