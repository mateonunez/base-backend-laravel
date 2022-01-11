<?php

namespace App\Traits;

trait HasRelationships
{
    /**
     * Retrive the relationships from the model.
     *
     * @return array
     */
    public function getRelationships()
    {
        $model = new static;

        $relationships = [];

        foreach ((new \ReflectionClass($model))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (
                $method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__
            ) {
                continue;
            }

            try {
                $return = $method->invoke($model);

                if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $relationships[$method->getName()] = [
                        'type' => (new \ReflectionClass($return))->getShortName(),
                        'model' => (new \ReflectionClass($return->getRelated()))->getName()
                    ];
                }
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return $relationships;
    }
}
