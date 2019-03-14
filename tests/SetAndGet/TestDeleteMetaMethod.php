<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestDeleteMetaMethod extends TestingHelpers
{

    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    //------------------------------------------ Methods --------------------------------------------//

    public function setUp() : void
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->model = factory(ExampleModel::class)->create();
    }

    public function test_delete_meta_method()
    {
        $this->model->createMeta('test' , 'testvalue');
        $this->assertEquals('testvalue' , $this->model->meta->test);

        $this->model->deleteMeta('test');
        $this->assertEquals(0 , $this->model->getLoadedMeta()->count());
        $this->assertEquals(0 , Meta::count());
    }

    public function test_truncate_meta_using_delete_meta_method()
    {
        $this->model->createMeta([
            'test' => 'testvalue',
            'test2' => 'testvalue2' ,
            'test3' => 'testvalue3'
        ]);
        $this->assertEquals(3 , Meta::count());

        $this->model->deleteMeta();

        $this->assertEquals(0 , $this->model->getLoadedMeta()->count());
        $this->assertEquals(0 , Meta::count());
    }
}