<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Illuminate\Support\Collection;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestGettingMetaWithProperty extends TestingHelpers
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
        $this->model = ExampleModel::get()->last();
    }

    public function test_get_meta_using_property()
    {
        $this->assertEquals(5 , $this->model->id);

        $value = $this->model->meta->key3;
        $this->assertEquals('test', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->meta->key1;
        $this->assertEquals(1, $value);
        $this->assertTrue(is_int($value));

        $value = $this->model->meta->key6;
        $this->assertEquals(null, $value);
        $this->assertTrue(is_null($value));

        $value = $this->model->meta->key8;
        $this->assertEquals(['test1', 'test2'], $value);
        $this->assertTrue(is_array($value));

        $value = $this->model->meta->key5;
        $this->assertEquals(collect(['test1', 'test2']), $value);
        $this->assertTrue($value instanceof Collection);

        $value = $this->model->meta->key7;
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(
            is_string($value) &&
            (is_object(json_decode($value)) ||
                is_array(json_decode($value))));

        $value = $this->model->meta->key4;
        $this->assertEquals(true, $value);
        $this->assertTrue(is_bool($value));
    }
}