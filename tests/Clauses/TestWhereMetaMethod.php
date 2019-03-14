<?php


namespace Zoha\Meta\Tests\Clauses;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestWhereMetaMethod extends TestingHelpers
{

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * set up this class testing
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
    }

    public function test_where_meta_clause_with_all_types()
    {
        $this->assertEquals(5 , ExampleModel::count());

        $result = ExampleModel::whereMeta('key3' , 'test2');
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([3] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key1' , 1);
        $this->assertEquals(2 , $result->count());
        $this->assertEquals([1,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key6' , null);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals(5 , $result->first()->id);

        $result = ExampleModel::whereMeta('key7' , ['test1' , 'test2']);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals(5 , $result->first()->id);

        $result = ExampleModel::whereMeta('key4' , true);
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,2,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key4' , null);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([4] , $result->pluck('id')->toArray());
    }

    public function test_where_meta_clause_with_all_types_using_operator()
    {
        $result = ExampleModel::whereMeta('key3' , '!=' ,  'test2');
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,2,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key3' , '<>' ,  'test2');
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,2,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key1' , '>' ,  2);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([4] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta('key3' , 'like' , 'test%');
        $this->assertEquals(4 , $result->count());
        $this->assertEquals([1,2,3,5] , $result->pluck('id')->toArray());
    }

    public function test_branched_filter_meta_with_where_meta_clause()
    {
        $result = ExampleModel::where(function ($query){
            $query->whereMeta('key1' , 2);
            $query->whereMeta('key2' , 1);
        });
        $this->assertEquals(1 , $result->count());
        $this->assertEquals(2 , $result->first()->id);
    }

    public function test_or_where_meta_clause_with_all_types()
    {

        $result = ExampleModel::whereMeta('key3' , '!=' ,  'test2')->orWhereMeta('key3' , null);
        $this->assertEquals(4 , $result->count());
        $this->assertEquals([1,2,4,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::orWhereMeta('key3' , null);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([4] , $result->pluck('id')->toArray());
    }

    public function test_branched_filter_meta_with_or_where_meta_clause()
    {
        $result = ExampleModel::where(function ($query){
            $query->where('id' , 1);
            $query->orWhereMeta('key3' , null);
            $query->orwhereMeta('key3' , 'test2');
        });
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,3,4] , $result->pluck('id')->toArray());
    }

    public function test_multiple_clause_condition_in_one_where_meta_method()
    {
        $result = ExampleModel::whereMeta([
            'key1' => 2,
            'key3' => 'test2'
        ]);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([3] , $result->pluck('id')->toArray());

        $result = ExampleModel::whereMeta([
            ['key1' , '=' , 2,],
            'key3' => 'test2'
        ]);
        $this->assertEquals(1 , $result->count());
        $this->assertEquals([3] , $result->pluck('id')->toArray());
    }

    public function test_multiple_clause_condition_in_one_or_where_meta_method()
    {
        $result = ExampleModel::orWhereMeta([
            'key1' => 1,
            'key4' => false
        ]);
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,3,5] , $result->pluck('id')->toArray());

        $result = ExampleModel::orWhereMeta([
            ['key1' , '=' ,  1],
            ['key4' , '=' ,  false],
        ]);
        $this->assertEquals(3 , $result->count());
        $this->assertEquals([1,3,5] , $result->pluck('id')->toArray());
    }
}