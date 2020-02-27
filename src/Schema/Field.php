<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

use CloudCreativity\LaravelJsonApi\Contracts\Schema\FieldInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;
use function class_basename;

abstract class Field implements FieldInterface
{

    public const TYPE = 'type';
    public const NAME = 'name';
    public const FILLABLE = 'fillable';
    public const FILTER = 'filter';
    public const SPARSE_FIELD = 'sparse_field';
    public const SORTABLE = 'sortable';

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $fillable;

    /**
     * @var bool
     */
    private $filter;

    /**
     * @var bool
     */
    private $sparseField;

    /**
     * @var bool
     */
    private $sortable;

    /**
     * Field constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Expecting a non-empty field name.');
        }

        $this->name = $name;
        $this->fillable = false;
        $this->filter = false;
        $this->sparseField = false;
        $this->sortable = false;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Mark the field as mass-assignable.
     *
     * @param bool $fillable
     * @return $this
     */
    public function fillable(bool $fillable = true): Field
    {
        $this->fillable = $fillable;

        return $this;
    }

    /**
     * Mark the field as not mass-assignable.
     *
     * @param bool $guarded
     * @return Field
     */
    public function guarded(bool $guarded = true): Field
    {
        return $this->fillable(false === $guarded);
    }

    /**
     * Mark the field as an allowed sparse fieldset.
     *
     * @param bool $sparse
     * @return $this
     */
    public function sparseFieldset(bool $sparse = true): Field
    {
        $this->sparseField = $sparse;

        return $this;
    }

    /**
     * Mark the field as not allowed in sparse fieldsets.
     *
     * @param bool $notSparse
     * @return $this
     */
    public function notSparseFieldset(bool $notSparse = true): Field
    {
        return $this->sparseFieldset(false === $notSparse);
    }

    /**
     * Mark the field as sortable.
     *
     * @param bool $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true): Field
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Mark the field as not sortable.
     *
     * @param bool $notSortable
     * @return $this
     */
    public function notSortable(bool $notSortable = true): Field
    {
        return $this->sortable(false === $notSortable);
    }

    /**
     * Mark the field as an allowed filter field.
     *
     * @param bool $filter
     * @return $this
     */
    public function filter(bool $filter = true): Field
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Mark the field as not allowed in filters.
     *
     * @param bool $notFilter
     * @return $this
     */
    public function notFilter(bool $notFilter = true): Field
    {
        return $this->filter(false === $notFilter);
    }

    /**
     * @inheritDoc
     */
    public function isFillable(): bool
    {
        return $this->fillable;
    }

    /**
     * @inheritDoc
     */
    public function isGuarded(): bool
    {
        return !$this->isFillable();
    }

    /**
     * @inheritDoc
     */
    public function isSparseField(): bool
    {
        return $this->sparseField;
    }

    /**
     * @inheritDoc
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @inheritDoc
     */
    public function isFilter(): bool
    {
        return $this->filter;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            self::FILLABLE => $this->isFillable(),
            self::FILTER => $this->isFilter(),
            self::NAME => $this->name(),
            self::SORTABLE => $this->isSortable(),
            self::SPARSE_FIELD => $this->isSparseField(),
            self::TYPE => $this->type(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    private function type(): string
    {
        return Str::slug(class_basename($this));
    }

}
