<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestIncreaseMetaMethod extends TestingHelpers
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

    public function test_increase_meta_method()
    {
        $this->model->setMeta('test' , 1);
        $this->model->increaseMeta('test');
        $this->assertEquals(2 , $this->model->meta->test);

        $this->model->increaseMeta('test');
        $this->assertEquals(3 , $this->model->meta->test);

        $this->model->increaseMeta('test',4);
        $this->assertEquals(7 , $this->model->meta->test);

        $this->model->setMeta('test' , 0);
        $this->model->increaseMeta('test');
        $this->assertEquals(1 , $this->model->meta->test);

        $this->model->setMeta('test' , 'testvalue');
        $this->model->increaseMeta('test');
        $this->assertEquals('testvalue' , $this->model->meta->test);
    }
}