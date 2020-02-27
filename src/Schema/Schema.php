<?php

namespace CloudCreativity\LaravelJsonApi\Schema;

class Schema
{

    /**
     * @var string
     */
    private $resourceType;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var FieldList
     */
    private $fields;

    /**
     * Schema constructor.
     *
     * @param string $resourceType
     * @param string $baseUri
     * @param FieldList $fields
     */
    public function __construct(string $resourceType, string $baseUri, FieldList $fields)
    {
        $this->resourceType = $resourceType;
        $this->baseUri = $baseUri;
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function selfLink(): string
    {
        return "{$this->baseUri}/{$this->resourceType}";
    }
}
