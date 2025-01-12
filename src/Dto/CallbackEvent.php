<?php

namespace Sportic\OmniEvent\Worldsmarathons\Dto;

use Spatie\SchemaOrg\BaseType;
use Sportic\OmniEvent\Models\Orders\RegistrationOrder;

class CallbackEvent extends BaseType
{
    public ?string $id = null;

    public ?string $created = null;

    public ?string $type = null;

    public ?RegistrationOrder $order = null;

    public function getOrder(): ?RegistrationOrder
    {
        return $this->order;
    }
}

