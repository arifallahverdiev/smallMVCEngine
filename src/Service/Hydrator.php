<?php

namespace App\Service;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class Hydrator
{
    /**
     * @throws ReflectionException
     */
    public function loadFromArray(array $data, $object): void
    {
        $class = new ReflectionClass(get_class($object));
        $props = $class->getProperties();

        foreach ($props as $p) {
            if (isset($data[$p->getName()])) {
                $setterName = 'set' . ucfirst($p->getName());

                if (!method_exists($object, $setterName)) {
                    continue;
                }

                $reflectionMethod = new ReflectionMethod($object, $setterName);
                $reflectionMethod->invoke($object, $this->filterUserData($p, $data[$p->getName()]));
            }
        }
    }

    private function filterUserData(ReflectionProperty $property, string $data): string
    {
        if ($property->getType()->getName() === 'string') {
            return addslashes(htmlspecialchars($data));
        } elseif ($property->getType()->getName() === 'int') {
            return (int)$data;
        }
    }
}