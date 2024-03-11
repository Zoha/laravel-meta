<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Models\Meta;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestCreateMetaMethod extends TestingHelpers
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

    public function test_add_single_meta_using_create_meta_method()
    {
        $this->model->createMeta('test', 'testvalue');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->metaTruncate();
    }

    public function test_add_single_meta_with_custom_type_using_create_meta_method()
    {
        //string

        $this->model->createMeta('test', 'testvalue', MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', 'testvalue', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->model->createMeta('test', '[1,2,3]', MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->model->createMeta('test', true, MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', 'true', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->model->createMeta('test', null, MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->model->createMeta('test', [1, 2, 3], MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->model->createMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->model->createMeta('test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->model->createMeta('test', true, MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '{}', MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        //json

        $this->model->createMeta('test', '[1,2,3]', MetaFacade::META_TYPE_JSON);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_JSON);
        $this->metaTruncate();

        //collection

        $this->model->createMeta('test', collect([1, 2, 3]), MetaFacade::META_TYPE_COLLECTION);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        $this->model->createMeta('test', [1, 2, 3], MetaFacade::META_TYPE_COLLECTION);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->model->createMeta('test', 123, MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->model->createMeta('test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //float

        $this->model->createMeta('test', 12.34, MetaFacade::META_TYPE_FLOAT);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '12.34', MetaFacade::META_TYPE_FLOAT);
        $this->metaTruncate();

        $this->model->createMeta('test', '12.34', MetaFacade::META_TYPE_FLOAT);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '12.34', MetaFacade::META_TYPE_FLOAT);
        $this->metaTruncate();

        $this->model->createMeta('test', '12.34');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '12.34', MetaFacade::META_TYPE_FLOAT);
        $this->metaTruncate();

        //boolean

        $this->model->createMeta('test', 'false', MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        $this->model->createMeta('test', 1, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '1', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        $this->model->createMeta('test', false, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->model->createMeta('test', 'testvalue', MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_add_single_meta_with_best_guess_for_type_using_create_meta_method()
    {
        //string

        $this->model->createMeta('test', 'testvalue');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', 'testvalue', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->model->createMeta('test', [1, 2, 3]);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //json

        $this->model->createMeta('test', '[1,2,3]');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //collection

        $this->model->createMeta('test', collect([1, 2, 3]));
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->model->createMeta('test', 123);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->model->createMeta('test', '123');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //boolean

        $this->model->createMeta('test', false, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->model->createMeta('test', 'testvalue', MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_add_multiple_meta_using_create_meta_method()
    {
        $this->model->createMeta([
            'test1' => 'testvalue1',
            'test2' => 'testvalue2'
        ]);
        $this->assertEquals(2, Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0], 'test1', 'testvalue1', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1], 'test2', 'testvalue2', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }

    public function test_add_multiple_meta_with_custom_type_using_create_meta_method()
    {
        $this->model->createMeta([
            'test1' => 'testvalue1',
            'test2' => true,
            'test3' => [1, 2, 3],
            'test4' => 123
        ], MetaFacade::META_TYPE_STRING);
        $this->assertEquals(4, Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0], 'test1', 'testvalue1', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1], 'test2', 'true', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[2], 'test3', '[1,2,3]', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[3], 'test4', '123', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }

    public function test_add_multiple_meta_with_best_guess_using_create_meta_method()
    {
        $this->model->createMeta([
            'test1' => 'testvalue1',
            'test2' => true,
            'test3' => [1, 2, 3],
            'test4' => 123
        ]);
        $this->assertEquals(4, Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0], 'test1', 'testvalue1', MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1], 'test2', '1', MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta[2], 'test3', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta[3], 'test4', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();
    }

    public function test_fail_single_meta_if_key_is_invalid()
    {
        $meta = $this->model->createMeta(collect([1, 2, 3]), 'testvalue');
        $this->assertFalse($meta);
        $this->assertEquals(0, Meta::count());
        $this->metaTruncate();

        $meta = $this->model->createMeta(false, 'testvalue');
        $this->assertFalse($meta);
        $this->assertEquals(0, Meta::count());
        $this->metaTruncate();
    }

    public function test_fail_multiple_meta_if_key_is_invalid()
    {
        $meta = $this->model->createMeta([
            'test1' => 'testvalue1',
            true => true,
            'test3' => [1, 2, 3],
            'test4' => 123
        ]);
        $this->assertFalse($meta);
        $this->assertEquals(0, Meta::count());
        $this->metaTruncate();
    }

    public function test_fail_if_meta_already_exists_using_create_meta()
    {
        $this->model->createMeta('test', 'testvalue');
        $this->assertEquals(1, Meta::count());
        $meta = $this->model->createMeta('test', 'testvalue2');
        $this->assertFalse($meta);
        $this->assertEquals(1, Meta::count());
        $this->assertEqualsMeta(Meta::first(), 'test', 'testvalue');
        $this->metaTruncate();
    }

    public function test_fail_if_meta_already_exists_using_create_meta_multiple_create()
    {
        $this->model->createMeta('test2', 'testvalue2');
        $meta = $this->model->createMeta([
            'test1' => false,
            'test2' => true,
            'test3' => [1, 2, 3],
            'test4' => 123
        ]);
        $this->assertFalse($meta);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0], 'test2', 'testvalue2', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }
}
