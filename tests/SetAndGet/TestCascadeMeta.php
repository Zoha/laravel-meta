<?php


namespace Zoha\Meta\Tests\SetAndGet;

use Zoha\Meta\Models\Meta;
use Zoha\Meta\Tests\TestingHelpers;

class TestCascadeMeta extends TestingHelpers
{

    public function test_that_if_an_model_will_be_deleted_all_associated_meta_will_be_deleted_too()
    {
        $model = factory(\Zoha\Meta\Models\ExampleModel::class)->create();
        $model->setMeta([
            'key1' => 'value1' ,
            'key2' => 'value2',
        ]);
        $this->assertEquals(1 , \Zoha\Meta\Models\ExampleModel::count());
        $this->assertEquals(2 , Meta::count());
        $model->delete();
        $this->assertEquals(0 , \Zoha\Meta\Models\ExampleModel::count());
        $this->assertEquals(0 , Meta::count());
    }

    public function if_a_model_item_was_deleted_other_items_model_will_not_be_deleted()
    {
        $model = factory(\Zoha\Meta\Models\ExampleModel::class)->create();
        $model2 = factory(\Zoha\Meta\Models\ExampleModel::class)->create();
        $model2->setMeta([
            'key1' => 'value1' ,
            'key2' => 'value2',
        ]);
        $this->assertEquals(1 , \Zoha\Meta\Models\ExampleModel::count());
        $this->assertEquals(2 , Meta::count());
        $model->delete();
        $this->assertEquals(0 , \Zoha\Meta\Models\ExampleModel::count());
        $this->assertEquals(2 , Meta::count());
    }

}