<?php

namespace Dyc\Exception;

class ServiceNotFoundException extends \Exception
{
    public static function forServiceId(string $serviceId): self
    {
        return new self(sprintf(
            'The service with id %s was not registered',
            $serviceId
        ));
    }
}
