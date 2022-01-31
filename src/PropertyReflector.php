<?php

namespace Natsumeaurlia\Reflection;

use \ReflectionClass;
use \Throwable;
use \ReflectionProperty;

abstract class PropertyReflector
{
    /**
     * Automatically reflect properties
     * @param object|json|array $vars
     */
    public function __construct($vars)
    {
        $vars = is_string($vars) ? json_decode($vars) : $vars;

        $reflection = new ReflectionClass(get_class($this));
        $properties = array_map(function ($rp) {
            return $rp->getName();
        }, $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED));

        foreach ((array)$vars as $key => $value) {
            if (!in_array($key, $properties)) {
                continue;
            }
            $reflectionType = $reflection->getProperty($key)->getType();

            if (!$reflectionType) {
                $this->$key = $value;
                continue;
            }

            $propertyType = $reflectionType->getName();
            if ($reflectionType->isBuiltin()) {
                $tmp_value = $value;
                try {
                    settype($tmp_value, $propertyType);
                    $this->$key = $tmp_value;
                    continue;
                } catch (Throwable $t) {}
            }

            try {
                $this->$key = new $propertyType($value);
            } catch (Throwable $t) {
                $this->$key = $value;
            }
        }
    }
}
