<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Spatie\SchemaOrg\BaseType;

abstract class AbstractReader
{
    protected ?BaseType $object = null;

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

    public static function fromArray(array $data): ?BaseType
    {
        $reader = new static();
        $reader->resultInstance();
        $reader->readFromArray($data);
        return $reader->result();
    }

    public function result(): ?BaseType
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
