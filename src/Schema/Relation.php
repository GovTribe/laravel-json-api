<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

use CloudCreativity\LaravelJsonApi\Contracts\Schema\RelationInterface;
use InvalidArgumentException;

abstract class Relation extends Field implements RelationInterface
{

    public const INVERSE = 'inverse';
    public const INCLUDE_PATH = 'include_path';
    public const DEFAULT_INCLUDE_PATH = 'include_path_default';
    public const SELF_LINK = 'link_self';
    public const RELATED_LINK = 'link_related';

    /**
     * @var string
     */
    private $inverse;

    /**
     * @var bool
     */
    private $include;

    /**
     * @var bool
     */
    private $defaultInclude;

    /**
     * @var bool
     */
    private $self;

    /**
     * @var bool
     */
    private $related;

    /**
     * Relation constructor.
     *
     * @param string $name
     * @param string $inverse
     */
    public function __construct(string $name, string $inverse)
    {
        if (empty($inverse)) {
            throw new InvalidArgumentException('Expecting a non-empty inverse resource type.');
        }

        parent::__construct($name);
        $this->inverse = $inverse;
        $this->include = false;
        $this->defaultInclude = false;
        $this->self = false;
        $this->related = false;
    }

    /**
     * Get the inverse resource type.
     *
     * @return string
     */
    public function inverse(): string
    {
        return $this->inverse;
    }

    /**
     * Mark the field as an allowed include path.
     *
     * @param bool $include
     * @return $this
     */
    public function includePath(bool $include = true): Relation
    {
        $this->include = $include;

        return $this;
    }

    /**
     * Mark the field as a disallowed include path.
     *
     * @param bool $doNotInclude
     * @return Relation
     */
    public function notIncludePath(bool $doNotInclude = true): Relation
    {
        return $this->includePath(!$doNotInclude);
    }

    /**
     * Mark the field as a default include path.
     *
     * @param bool $default
     * @return $this
     */
    public function defaultIncludePath(bool $default = true): Relation
    {
        if (true === $default) {
            $this->include = true;
        }

        $this->defaultInclude = $default;

        return $this;
    }

    /**
     * Mark the relation as having a self link.
     *
     * @param bool $self
     * @return $this
     */
    public function withSelf(bool $self = true): Relation
    {
        $this->self = $self;

        return $this;
    }

    /**
     * Mark the relation as not having a self link.
     *
     * @param bool $withoutSelf
     * @return $this
     */
    public function withoutSelf(bool $withoutSelf = true): Relation
    {
        return $this->withSelf(false === $withoutSelf);
    }

    /**
     * Mark the relation as having a related link.
     *
     * @param bool $related
     * @return $this
     */
    public function withRelated(bool $related = true): Relation
    {
        $this->related = $related;

        return $this;
    }

    /**
     * Mark the relation as not having a related link.
     *
     * @param bool $withoutRelated
     * @return $this
     */
    public function withoutRelated(bool $withoutRelated = true): Relation
    {
        return $this->withRelated(false === $withoutRelated);
    }

    /**
     * @return bool
     */
    public function isIncludePath(): bool
    {
        return $this->include;
    }

    /**
     * @return bool
     */
    public function isDefaultIncludePath(): bool
    {
        return $this->defaultInclude;
    }

    /**
     * @return bool
     */
    public function hasSelfLink(): bool
    {
        return $this->self;
    }

    /**
     * @return bool
     */
    public function hasRelatedLink(): bool
    {
        return $this->related;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = array_merge(parent::toArray(), [
            self::INVERSE => $this->inverse(),
            self::INCLUDE_PATH => $this->isIncludePath(),
            self::DEFAULT_INCLUDE_PATH => $this->isDefaultIncludePath(),
            self::SELF_LINK => $this->hasSelfLink(),
            self::RELATED_LINK => $this->hasRelatedLink(),
        ]);

        ksort($arr);

        return $arr;
    }
}
