<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Spatie\SchemaOrg\BaseType;
use Sportic\OmniEvent\Models\Base\TypeCollection;

abstract class AbstractReader
{
    protected null|BaseType|TypeCollection $object = null;

    public static function from($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            return self::fromArray($data);
        }

        throw new \Exception('Invalid data');
    }

    public static function fromArray(array $data): null|BaseType|TypeCollection
    {
        $reader = new static();
        $reader->resultInstance();
        $reader->readFromArray($data);
        return $reader->result();
    }

    public function result(): null|BaseType|TypeCollection
    {
        return $this->object;
    }

    protected function resultInstance(): void
    {
        $class = $this->resultObjectClass();
        $this->object = new $class();
    }

    abstract public function readFromArray(array $data): ?self;

    abstract protected function resultObjectClass(): string;

}
