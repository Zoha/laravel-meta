<?php


namespace Zoha\Meta\Tests\Clauses;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestWhereMetaDoesntHaveMethod extends TestingHelpers
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
        $results = ExampleModel::whereMetaDoesntHave();
        $this->assertEquals(0 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key7');
        $this->assertEquals(4 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key6');
        $this->assertEquals(5 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key6' , true );
        $this->assertEquals(4 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key8' , false , MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(5 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key8' , false , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(4 , $results->count());
    }

    public function test_meta_has_method_multiple()
    {

        $results = ExampleModel::whereMetaDoesntHave('key7')->orWhereMetaDoesntHave();
        $this->assertEquals(4 , $results->count());

        $results = ExampleModel::whereMetaDoesntHave('key7')->orWhereMetaDoesntHave('key6',true);
        $this->assertEquals(4 , $results->count());
    }
}