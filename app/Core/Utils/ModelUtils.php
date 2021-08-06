<?php

namespace App\Core\Utils;

class ModelUtils
{
    /**
     * Returns a boolean value indicating whether the class uses some trait
     *
     * @param mixed $class
     * @param mixed $trait
     *
     * @return bool
     */
    public static function usesTrait(mixed $class, mixed $trait): bool
    {
        return in_array(
            $trait,
            class_uses_recursive($class),
            true
        );
    }

    /**
     * Return all relationships belonging to the class
     *
     * @param mixed $class
     *
     * @return array
     */
    public static function relations(mixed $class): array
    {
        $reflector = new \ReflectionClass($class);

        $relations = [];
        foreach ($reflector->getMethods() as $reflectionMethod) {
            $returnType = $reflectionMethod->getReturnType();
            if ($returnType) {
                if (in_array(
                    class_basename($returnType->getName()),
                    ['HasOne', 'HasMany', 'BelongsTo', 'BelongsToMany', 'MorphToMany', 'MorphTo']
                )) {
                    $relations[] = $reflectionMethod;
                }
            }
        }

        return $relations;
    }
}
