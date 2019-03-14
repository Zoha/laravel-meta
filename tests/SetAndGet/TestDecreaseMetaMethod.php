<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestDecreaseMetaMethod extends TestingHelpers
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

    public function test_decrease_meta_method()
    {
        $this->model->setMeta('test' , 1);
        $this->model->decreaseMeta('test');
        $this->assertEquals(0 , $this->model->meta->test);

        $this->model->decreaseMeta('test');
        $this->assertEquals(-1 , $this->model->meta->test);

        $this->model->setMeta('test' , 10);
        $this->model->decreaseMeta('test',4);
        $this->assertEquals(6 , $this->model->meta->test);

        $this->model->setMeta('test' , 'testvalue');
        $this->model->decreaseMeta('test');
        $this->assertEquals('testvalue' , $this->model->meta->test);
    }
}