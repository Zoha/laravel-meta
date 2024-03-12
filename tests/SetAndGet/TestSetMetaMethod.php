<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestSetMetaMethod extends TestingHelpers
{
    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    //------------------------------------------ Methods --------------------------------------------//

    public function setUp(): void
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->model = ExampleModel::factory()->create();
    }

    public function test_add_single_meta_using_set_meta_method()
    {
        $this->model->setMeta('test', 'testvalue');
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->metaTruncate();
    }

    public function test_add_multiple_meta_using_set_meta_method()
    {
        $this->model->setMeta([
            'test1' => 'testvalue1',
            'test2' => 'testvalue2',
            'test3' => 'testvalue3'
        ]);
        $allMeta = Meta::all();
        foreach ($allMeta as $meta) {
            $this->assertEqualsMeta($meta, 'test' . $meta->id, 'testvalue' . $meta->id);
        }
        $this->metaTruncate();
    }

    public function test_that_set_meta_method_return_false_if_key_is_not_array_or_string()
    {
        $this->assertFalse($this->model->setMeta(true, 'testvalue'));
        $this->metaTruncate();
    }

    public function test_if_meta_with_same_key_already_exists_set_meta_method_update_it()
    {
        $this->model->setMeta('test', 'testvalue');
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->assertEquals(1, Meta::all()->count());
        $this->model->setMeta('test', 'testvalue1');
        $meta = Meta::first();
        $this->assertEquals(1, Meta::all()->count());
        $this->assertEqualsMeta($meta, 'test', 'testvalue1');
        $this->metaTruncate();
    }

    public function test_in_multiple_setting_meta_if_one_of_theme_already_exists_will_be_updated()
    {
        $this->model->setMeta('test1', 'testvalue1000');
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test1', 'testvalue1000');

        $this->model->setMeta([
            'test1' => 'testvalue1',
            'test2' => 'testvalue2',
            'test3' => 'testvalue3'
        ]);
        $allMeta = Meta::all();
        foreach ($allMeta as $meta) {
            $this->assertEqualsMeta($meta, 'test' . $meta->id, 'testvalue' . $meta->id);
        }
        $this->assertEquals(3, $allMeta->count());
        $this->metaTruncate();
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__null()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_NULL);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);

        $meta = $this->fastCreateMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_NULL);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);

        $meta = $this->fastCreateMeta('test', '[1,2,3]', MetaFacade::META_TYPE_NULL);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);

        $meta = $this->fastCreateMeta('test', 'null', MetaFacade::META_TYPE_NULL);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_NULL);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__json()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_JSON);

        $meta = $this->fastCreateMeta('test', '[1,2,3]', MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_JSON);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_JSON);

        $meta = $this->fastCreateMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_JSON);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_JSON);

        $meta = $this->fastCreateMeta('test', 44, MetaFacade::META_TYPE_JSON);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_JSON);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__array()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', null, MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', 123, MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_ARRAY);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__collection()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', null, MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', 123, MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__integer()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 123, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', null, MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', true, MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 1, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', 12.34, MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 12, MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', '12.34', MetaFacade::META_TYPE_INTEGER);
        $this->assertEqualsMeta($meta, 'test', 12, MetaFacade::META_TYPE_INTEGER);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__float()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 0.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 0.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', '123', MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 123.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', null, MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 0.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 0.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', true, MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 1.0, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', 12.34, MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 12.34, MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', '12.34', MetaFacade::META_TYPE_FLOAT);
        $this->assertEqualsMeta($meta, 'test', 12.34, MetaFacade::META_TYPE_FLOAT);
    }

    public function test_using_set_meta_with_custom_type_will_force_meta_type_to_convert__boolean()
    {
        $meta = $this->fastCreateMeta('test', 'testvalue', MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 1, MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', [1, 2, 3], MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 1, MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', '123', MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 1, MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', null, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', false, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 0, MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', true, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta, 'test', 1, MetaFacade::META_TYPE_BOOLEAN);
    }

    public function test_add_single_meta_with_best_guess_of_type()
    {
        // string
        $meta = $this->fastCreateMeta('test', 'testvalue');
        $this->assertEqualsMeta($meta);

        //boolean
        $meta = $this->fastCreateMeta('test', true);
        $this->assertEqualsMeta($meta, 'test', '1', MetaFacade::META_TYPE_BOOLEAN);

        $meta = $this->fastCreateMeta('test', false);
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);

        // array json and collection _> to collection
        $meta = $this->fastCreateMeta('test', [1, 2, 3]);
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', collect([1, 2, 3]));
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        $meta = $this->fastCreateMeta('test', '[1,2,3]');
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        // integer
        $meta = $this->fastCreateMeta('test', '123');
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);

        $meta = $this->fastCreateMeta('test', 123);
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);

        // float
        $meta = $this->fastCreateMeta('test', '123.45');
        $this->assertEqualsMeta($meta, 'test', '123.45', MetaFacade::META_TYPE_FLOAT);

        $meta = $this->fastCreateMeta('test', 123.45);
        $this->assertEqualsMeta($meta, 'test', '123.45', MetaFacade::META_TYPE_FLOAT);

        // null
        $meta = $this->fastCreateMeta('test', null);
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
    }

    public function test_that_false_returned_if_one_of_key_in_multiple_set_meta_was_invalid()
    {
        $settingMultipleMeta = $this->model->setMeta([
            'test1' => 'testvalue1',
            true    => 'testvalue2',
            'test3' => 'testvalue3'
        ]);
        $this->assertFalse($settingMultipleMeta);
        $allMeta = Meta::all();
        $this->assertEquals(2, $allMeta->count());
        $this->metaTruncate();
    }

    public function test_set_multiple_meta_with_custom_type_using_set_meta_method()
    {
        $this->model->setMeta([
            'test1' => 'testvalue1',
            'test2' => 'testvalue2',
            'test3' => 'testvalue3'
        ], MetaFacade::META_TYPE_NULL);
        $allMeta = Meta::all();
        foreach ($allMeta as $meta) {
            $this->assertEqualsMeta($meta, 'test' . $meta->id, null, MetaFacade::META_TYPE_NULL);
        }
        $this->metaTruncate();

        $this->model->setMeta([
            'test1' => 'testvalue1',
            'test2' => [1, 2, 3],
            'test3' => 123
        ], MetaFacade::META_TYPE_BOOLEAN);
        $allMeta = Meta::all();
        foreach ($allMeta as $meta) {
            $this->assertEqualsMeta($meta, 'test' . $meta->id, true, MetaFacade::META_TYPE_BOOLEAN);
        }
        $this->metaTruncate();
    }

    public function test_update_multiple_meta_with_custom_type_using_set_meta_method()
    {
        $this->model->setMeta([
            'test1' => 'testvalue1',
            'test2' => false,
            'test3' => [1, 2, 3]
        ]);
        $allMeta = Meta::all();
        $this->assertEqualsMeta($allMeta[0], 'test' . $allMeta[0]->id, 'testvalue1', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($allMeta[1], 'test' . $allMeta[1]->id, '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($allMeta[2], 'test' . $allMeta[2]->id, '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);

        $this->model->setMeta([
            'test1' => 'testvalue2',
            'test2' => true,
            'test3' => [1, 2, 3, 4, 5]
        ], MetaFacade::META_TYPE_BOOLEAN);
        $allMeta = Meta::all();
        foreach ($allMeta as $meta) {
            $this->assertEqualsMeta($meta, 'test' . $meta->id, true, MetaFacade::META_TYPE_BOOLEAN);
        }
        $this->metaTruncate();
    }
}
