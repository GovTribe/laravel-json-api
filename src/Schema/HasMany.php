<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

class HasMany extends Relation
{

    /**
     * @inheritDoc
     */
    public function toOne(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function toMany(): bool
    {
        return true;
    }

}
