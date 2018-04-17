<?php


namespace Zoha\Meta\Tests\Clauses;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestWhereMetaNull extends TestingHelpers
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

    public function test_filter_meta_using_where_meta_null_method()
    {
        $this->assertEquals(5, ExampleModel::count());

        $result = ExampleModel::whereMetaNull('key3');
        $this->assertEquals(1, $result->count());
        $this->assertEquals([4], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotNull('key3');
        $this->assertEquals(4, $result->count());
        $this->assertEquals([1,2,3,5], $result->pluck('id')->toArray());
    }

    public function test_filter_meta_using_or_where_meta_null_method()
    {
        $result = ExampleModel::whereMetaNull('key3')->orWhereMetaNull('key6');
        $this->assertEquals(2, $result->count());
        $this->assertEquals([4,5], $result->pluck('id')->toArray());

        $result = ExampleModel::whereMetaNotNull('key1', [1, 3])->orWhereMetaNotNull('key6');
        $this->assertEquals(5, $result->count());
        $this->assertEquals([1,2,3,4,5], $result->pluck('id')->toArray());
    }
}