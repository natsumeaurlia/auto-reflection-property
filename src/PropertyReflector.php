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
            $reflectionType = $reflection->getProperty($key);

            if (!$reflectionType->getName()) {
                $this->$key = $value;
                continue;
            }
            $propertyType = $reflectionType->getName();

            try {
                if ($reflectionType->isBuiltin()) {
                    $tmp_value = $vars;
                    if (settype($tmp_value, $propertyType)) {
                        $this->$key = $tmp_value;
                        continue;
                    }
                    $this->$key = $value;
                    continue;
                }
                $this->$key = new $propertyType($value);
            } catch (Throwable $t) {
                $this->$key = $value;
            }
        }
    }
}