<?php


namespace Zoha\Meta\Tests\Clauses;

use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestWhereMetaInMethod extends TestingHelpers
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

    public function test_filter_meta_using_where_meta_in_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaIn('key1', [1, 3]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([1, 4, 5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaIn('key1', [2,3]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([2, 3, 4], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_or_where_meta_in_method()
    {
        $result = ExampleModel::whereMetaIn('key1', [1, 2])->orWhereMetaIn('key1', [2, 3]);
        $this->assertEquals(5, $result->count());
        $this->assertEquals([1, 2, 3, 4, 5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaIn('key1', [1, 3])->orWhereMetaIn('key1', [5, 8]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([1, 4, 5], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_where_meta_not_in_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaNotIn('key1', [1, 3]);
        $this->assertEquals(2, $result->count());
        $this->assertEquals([2,3], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotIn('key1', [2, 3]);
        $this->assertEquals(2, $result->count());
        $this->assertEquals([1, 5], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_or_where_meta_not_in_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaNotIn('key1', [1, 2])->orWhereMetaNotIn('key1', [2, 3]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals([1,4,5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotIn('key1', [2, 3])->orWhereMetaNotIn('key1', [5, 8]);
        $this->assertEquals(5, $result->count());
        $this->assertEquals([1,2,3,4,5], $result->pluck('id')->toArray());
    }
}