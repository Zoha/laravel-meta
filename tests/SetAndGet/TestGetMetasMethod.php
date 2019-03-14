<?php


namespace Zoha\Meta\Traits;

use Illuminate\Support\Collection;
use Zoha\Meta\Models\Meta;
use Zoha\Meta\Tests\TestingHelpers;

class TestGetMetasMethod extends TestingHelpers
{

    public function test_that_get_metas_method_will_return_all_metas()
    {
        $model = factory(\Zoha\Meta\Models\ExampleModel::class)->create();
        $model->setMeta([
            'key1' => 'value1' ,
            'key2' => 'value2',
        ]);

        $metas = $model->getMetas();

        $this->assertTrue($metas instanceof Collection);
        $this->assertCount(2 , $metas);
    }

    public function test_that_get_metas_method_will_return_an_empty_collection_if_no_metas_was_set_yet()
    {
        $model = factory(\Zoha\Meta\Models\ExampleModel::class)->create();

        $metas = $model->getMetas();

        $this->assertTrue($metas instanceof Collection);
        $this->assertCount(0 , $metas);
    }

}