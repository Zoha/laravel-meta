<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestHasMetaMethod extends TestingHelpers
{

    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    //------------------------------------------ Methods --------------------------------------------//

    public function setUp() : void
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
        $this->model = ExampleModel::get()->last();
    }

    public function test_has_meta_method()
    {
        $result = $this->model->hasMeta('key8');
        $this->assertEquals(true , $result);

        $result = $this->model->hasMeta('key40');
        $this->assertEquals(false , $result);

        $result = $this->model->hasMeta('key6');
        $this->assertEquals(false , $result);

        $this->model->setMeta('key10' , 0);
        $result = $this->model->hasMeta('key10');
        $this->assertEquals(true , $result);
        $this->model->deleteMeta('key10');

        $result = $this->model->hasMeta('key6' , true);
        $this->assertEquals(true , $result);

        $result = $this->model->hasMeta('key8' , true , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(true , $result);

        $result = $this->model->hasMeta('key8' , false , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(true , $result);

        $result = $this->model->hasMeta('key8' , true , MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(false , $result);
    }
}