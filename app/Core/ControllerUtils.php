<?php

namespace App\Core;

class ControllerUtils
{
    /**
     * Parse query parameters fiters
     *
     * @param array $query
     *
     * @return array
     */
    public static function getRequestFilters(array $query): array
    {
        try {
            if (!isset($query['filters'])) {
                return [];
            }

            $filters = self::getExplodedParams($query['filters']);
            $filtersArray = self::getFiltersArray($filters);

            return $filtersArray;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Parse query parameters relationships
     *
     * @param array $query
     *
     * @return array
     */
    public static function getRequestRelationships(array $query): array
    {
        try {
            if (!isset($query['relations'])) {
                return [];
            }

            $relations = self::getExplodedParams($query['relations']);

            return $relations;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Paring query orderBy
     *
     * @param array $query
     *
     * @return array
     */
    public static function getRequestOrderBy(array $query): array
    {

        try {
            if (!isset($query['orderBy'])) {
                return [];
            }

            $orderByParams = self::getExplodedParams($query['orderBy']);

            $orderBy = self::getOrderByArray($orderByParams);

            return $orderBy;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Determinates if pagination is active
     *
     * @param array $query
     *
     * @return boolean
     */
    public static function getPaginate(array $query): bool
    {
        try {
            if (!isset($query['paginate'])) {
                return false;
            }

            return filter_var($query['paginate'], FILTER_VALIDATE_BOOLEAN);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $params
     * @return array
     */
    public static function getExplodedParams(String $params): array
    {
        $params = str_replace('[', '', $params);
        $params = str_replace(']', '', $params);
        $params = str_replace(' ', '', $params);
        $params = explode(',', $params);

        return $params;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    public static function getFiltersArray(array $filters): array
    {
        $operators = self::getOperators();

        $filtersArray = [];
        foreach ($filters as $filter) {
            foreach ($operators as $k => $v) {
                $params = explode($k, $filter);
                if (count($params) > 1) {
                    if ($params[1] == 'null') {
                        $params[1] = null;
                    }
                    $column = $params[0];
                    $operator = $v;
                    $value = $params[1];

                    $filtersArray[] = [$column, $operator, $value];
                }
            }
        }

        return $filtersArray;
    }


    /**
     * This method, This method creates an associative array
     * for ascending or descending sort order for the entity
     * '' => 'ASC'
     * '!' => 'DESC'
     *
     * @param array $ordersParams
     *
     * @return array
     */
    public static function getOrderByArray(array $orders): array
    {
        $ordersArray = [];
        foreach ($orders as $filter) {
            if (strpos($filter, '!') !== false) {
                $value = str_replace("!", "", $filter);
                $operator = 'DESC';
            } else {
                $value = $filter;
                $operator = 'ASC';
            }

            $ordersArray[] = [$value, $operator];
        }

        return $ordersArray;
    }

    /**
     * Returns filters operators
     *
     * @return array
     */
    private static function getOperators(): array
    {
        return [
            ':' => '=',
            '!' => '!=',
            '>' => '>',
            '<' => '<',
            '~' => 'LIKE',
            '|' => 'NOT LIKE'
        ];
    }
}
