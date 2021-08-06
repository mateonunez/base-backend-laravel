<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Core\Utils\ControllerUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ControllerUtilsTest extends TestCase
{
    /**
     * @group controller_utils
     */
    public function test_get_request_filters()
    {
        $query = [
            'filters' => 'user_id:1,post_id!1,name~Name,description|Description'
        ];
        $requestFilters = ControllerUtils::getRequestFilters($query);

        $result = [
            ['user_id', '=', '1',],
            ['post_id', '!=', '1'],
            ['name', 'LIKE', 'Name'],
            ['description', 'NOT LIKE', 'Description'],
        ];

        $this->assertEquals($result, $requestFilters);
    }

    /**
     * @group controller_utils
     */
    public function test_get_request_relationships()
    {
        $query = [
            'relations' => 'user,posts,posts:image'
        ];
        $requestRelationships = ControllerUtils::getRequestRelationships($query);

        $result = [
            'user',
            'posts',
            'posts:image'
        ];

        $this->assertEquals($result, $requestRelationships);
    }

    /**
     * @group controller_utils
     */
    public function test_get_request_order_by()
    {
        $query = [
            '!created_at',
            'updated_at'
        ];

        $requestOrderBy = ControllerUtils::getOrderByArray($query);

        $result = [
            ['created_at', 'DESC'],
            ['updated_at', 'ASC'],
        ];

        $this->assertEquals($result, $requestOrderBy);
    }
}
