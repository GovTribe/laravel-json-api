<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

class BelongsTo extends Relation
{

    /**
     * @inheritDoc
     */
    public function toOne(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function toMany(): bool
    {
        return false;
    }

}
