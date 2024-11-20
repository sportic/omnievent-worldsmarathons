<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Invoice;
use Spatie\SchemaOrg\MonetaryAmount;

class InvoiceReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $amount = new MonetaryAmount();
        $amount->value($data['amount']);
        $amount->currency($data['currency']);
        $this->object->totalPaymentDue($amount);
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return Invoice::class;
    }
}
