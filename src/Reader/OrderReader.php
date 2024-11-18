<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;


use Spatie\SchemaOrg\OrderStatus;
use Sportic\OmniEvent\Models\Orders\RegistrationOrder;

class OrderReader
{
    protected ?RegistrationOrder $order = null;

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

    public static function fromArray(array $data): ?RegistrationOrder
    {
        $reader = new self();
        $reader->readFromArray($data);
        return $reader->result();
    }

    public function readFromArray(array $data): ?self
    {
        $this->order = new RegistrationOrder();
        $this->order->orderNumber($data['id']);
        $this->order->orderDate($data['created']);

        $this->readStatus($data['type']);
        return $this;
    }

    public function result(): ?RegistrationOrder
    {
        return $this->order;
    }

    protected function readStatus($rawStatus = null)
    {
        $status = null;
        switch ($rawStatus) {
            case 'pending':
                $status = 'pending';
                break;
            case 'completed':
                $status = 'completed';
                break;
            case 'order.successful':
                $status = OrderStatus::OrderDelivered;
                break;
        }
        $this->order->orderStatus($status);
    }
}