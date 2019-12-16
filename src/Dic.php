<?php

namespace Dyc;

class Dic
{
    private $definitions = [];

    public function autowire(array $classes): void
    {
        foreach ($classes as $fqcn) {
            $this->definitions[$fqcn] = function(Dic $dic) use ($fqcn) {
                $arguments = array_map(function($typeHint) {
                    return $this->get($typeHint);
                }, $this->getTypeHintedParameters($fqcn));

                return new $fqcn(...$arguments);
            };
        }
    }

    public function set(string $serviceId, callable $serviceInstanciation): void
    {
        $this->definitions[$serviceId] = $serviceInstanciation;
    }

    public function get(string $serviceId)
    {
        if (!array_key_exists($serviceId, $this->definitions)) {
            throw new \Exception(sprintf('No service with id %s', $serviceId));
        }

        return $this->definitions[$serviceId]($this);
    }

    private function getTypeHintedParameters($fqcn): array
    {
        $typeHintedParameters = [];

        $reflClass = new \ReflectionClass($fqcn);
        $constructor = $reflClass->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $parameters = $constructor->getParameters();
        foreach ($parameters as $parameter) {
            $typehint = $parameter->getClass();
            $typeHintedParameters[] = $typehint ? $typehint->getName() : $parameter->getType();
        }

        return $typeHintedParameters;
    }
}
