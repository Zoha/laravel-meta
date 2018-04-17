<?php


namespace Zoha\Meta\Tests\Clauses;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestWhereMetaHasMethod extends TestingHelpers
{
    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    //------------------------------------------ Methods --------------------------------------------//

    public function setUp()
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
    }

    public function test_meta_has_method()
    {
        $results = ExampleModel::whereMetaHas();
        $this->assertEquals(5 , $results->count());

        $results = ExampleModel::whereMetaHas('key7');
        $this->assertEquals(1 , $results->count());
        $this->assertEquals(5 , $results->first()->id);

        $results = ExampleModel::whereMetaHas('key6');
        $this->assertEquals(0 , $results->count());

        $results = ExampleModel::whereMetaHas('key6' , true );
        $this->assertEquals(1 , $results->count());
        $this->assertEquals(5 , $results->first()->id);

        $results = ExampleModel::whereMetaHas('key8' , false , MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(0 , $results->count());

        $results = ExampleModel::whereMetaHas('key8' , false , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1 , $results->count());
        $this->assertEquals(5 , $results->first()->id);
    }

    public function test_meta_has_method_multiple()
    {

        $results = ExampleModel::whereMetaHas('key7')->orWhereMetaHas();
        $this->assertEquals(5 , $results->count());

        $results = ExampleModel::whereMetaHas('key7')->orWhereMetaHas('key6',true);
        $this->assertEquals(1 , $results->count());
        $this->assertEquals(5 , $results->first()->id);

        $results = ExampleModel::whereMetaHas('key6' , true )->whereMetaHas('key14');
        $this->assertEquals(0 , $results->count());
    }
}