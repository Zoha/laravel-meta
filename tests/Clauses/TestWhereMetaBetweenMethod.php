<?php


namespace Zoha\Meta\Tests\Clauses;

use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestWhereMetaBetweenMethod extends TestingHelpers
{

    /**
     * set up this class testing
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
    }

    public function test_filter_meta_using_where_meta_between_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaBetween('key1', [1, 2]);
        $this->assertEquals(4, $result->count());
        $this->assertEquals([1, 2, 3, 5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaBetween('key1', [2, 3]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([2, 3, 4], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_or_where_meta_between_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaBetween('key1', [1, 2])->orWhereMetaBetween('key1', [2, 3]);
        $this->assertEquals(5, $result->count());
        $this->assertEquals([1, 2, 3, 4, 5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaBetween('key1', [2, 3])->orWhereMetaBetween('key1', [5, 8]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([2, 3, 4], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_where_meta_not_between_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaNotBetween('key1', [1, 2]);
        $this->assertEquals(1, $result->count());
        $this->assertEquals([4], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotBetween('key1', [2, 3]);
        $this->assertEquals(2, $result->count());
        $this->assertEquals([1, 5], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_or_where_meta_not_between_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaNotBetween('key1', [1, 2])->orWhereMetaNotBetween('key1', [2, 3]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([1, 4, 5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotBetween('key1', [2, 3])->orWhereMetaNotBetween('key1', [5, 8]);
        $this->assertEquals(5, $result->count());
        $this->assertEquals([1, 2, 3, 4, 5], $result->pluck('id')->toArray());
    }
}