<?php


namespace Zoha\Meta\Tests;

use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;

class TestFactories extends TestingHelpers
{
    public function test_example_model_factory()
    {
        ExampleModel::factory()->create([
            'title' => 'test title'
        ]);
        $model = ExampleModel::first();
        $this->assertEquals('test title', $model->title);

        $this->truncate();
    }

    public function test_meta_model_factory()
    {
        Meta::factory()->create([
            'key' => 'example key',
            'value' => 'example value',
            'owner_type' => 'owner type',
            'owner_id'   => 10,
        ]);
        $meta = Meta::first();
        // assert key and value
        $this->assertEquals('example key', $meta->key);
        $this->assertEquals('example value', $meta->value);

        // assert type and status
        $this->assertEquals(\Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING, $meta->type);
        $this->assertTrue((bool) $meta->status);

        //assert owner type and owner key
        $this->assertEquals('owner type', $meta->owner_type);
        $this->assertEquals(10, $meta->owner_id);

        $this->truncate();
    }
}
