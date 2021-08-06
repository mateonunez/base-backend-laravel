<?php

namespace App\Core\Utils;

class ModelUtils
{
    /**
     * Returns a boolean value indicating whether the class uses some trait
     *
     * @param object $class
     * @param object $trait
     *
     * @return bool
     */
    public static function usesTrait(object $class, object $trait): bool
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
     * @param object $class
     *
     * @return array
     */
    public static function relations(object $class): array
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
