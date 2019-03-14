<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestUpdateMetaMethod extends TestingHelpers
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

    public function createFakeMeta()
    {
        $this->model->setMeta('test' , 'testvalue');
    }

    public function test_update_single_meta_using_update_meta_method()
    {
        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'testvalue2');
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , 'testvalue2');
        $this->metaTruncate();
    }

    public function test_update_single_meta_with_custom_type_using_update_meta_method()
    {
        //string

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'testvalue2' , MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , 'testvalue2' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '[1,2,3]' , MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , true , MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , 'true' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->createFakeMeta();
        $this->model->updateMeta('test' , [1,2,3] , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , collect([1,2,3]) , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '[1,2,3]' , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , true , MetaFacade::META_TYPE_ARRAY);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '{}' , MetaFacade::META_TYPE_ARRAY);
        $this->metaTruncate();

        //json

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '[1,2,3]' , MetaFacade::META_TYPE_JSON);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_JSON);
        $this->metaTruncate();

        //collection

        $this->createFakeMeta();
        $this->model->updateMeta('test' , collect([1,2,3]) , MetaFacade::META_TYPE_COLLECTION);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , [1,2,3] , MetaFacade::META_TYPE_COLLECTION);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 123 , MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //boolean

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'false' , MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '0' , MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 1 , MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '1' , MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , false , MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '0' , MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'testvalue' , MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , null , MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_update_single_meta_with_best_guess_for_type_using_update_meta_method()
    {
        //string

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'testvalue2');
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , 'testvalue2' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->createFakeMeta();
        $this->model->updateMeta('test' , [1,2,3]);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //json

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '[1,2,3]');
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //collection

        $this->createFakeMeta();
        $this->model->updateMeta('test' , collect([1,2,3]));
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 123);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->createFakeMeta();
        $this->model->updateMeta('test' , '123');
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //boolean

        $this->createFakeMeta();
        $this->model->updateMeta('test' , false , MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , '0' , MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->createFakeMeta();
        $this->model->updateMeta('test' , 'testvalue' , MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test' , null , MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_update_multiple_meta_using_update_meta_method()
    {
        $this->model->createMeta('test1' , 'test');
        $this->model->createMeta('test2' , 'test');
        $this->model->updateMeta([
            'test1' => 'testvalue1',
            'test2' => 'testvalue2'
        ]);
        $this->assertEquals(2 ,Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0] , 'test1' , 'testvalue1' , MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1] , 'test2' , 'testvalue2' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }

    public function test_update_multiple_meta_with_custom_type_using_update_meta_method()
    {
        $this->model->createMeta('test1' , 'test');
        $this->model->createMeta('test2' , 'test');
        $this->model->createMeta('test3' , 'test');
        $this->model->createMeta('test4' , 'test');

        $this->model->updateMeta([
            'test1' => 'testvalue1',
            'test2' => true,
            'test3' => [1,2,3],
            'test4' => 123
        ], MetaFacade::META_TYPE_STRING);
        $this->assertEquals(4 ,Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0] , 'test1' , 'testvalue1' , MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1] , 'test2' , 'true' , MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[2] , 'test3' , '[1,2,3]' , MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[3] , 'test4' , '123' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }

    public function test_update_multiple_meta_with_best_guess_using_update_meta_method()
    {
        $this->model->createMeta('test1' , 'test');
        $this->model->createMeta('test2' , 'test');
        $this->model->createMeta('test3' , 'test');
        $this->model->createMeta('test4' , 'test');

        $this->model->updateMeta([
            'test1' => 'testvalue1',
            'test2' => true,
            'test3' => [1,2,3],
            'test4' => 123
        ]);
        $this->assertEquals(4 ,Meta::count());
        $meta = Meta::all();
        $this->assertEqualsMeta($meta[0] , 'test1' , 'testvalue1' , MetaFacade::META_TYPE_STRING);
        $this->assertEqualsMeta($meta[1] , 'test2' , '1' , MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEqualsMeta($meta[2] , 'test3' , '[1,2,3]' , MetaFacade::META_TYPE_COLLECTION);
        $this->assertEqualsMeta($meta[3] , 'test4' , '123' , MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();
    }

    public function test_fail_single_meta_if_key_is_invalid()
    {
        $meta = $this->model->updateMeta(collect([1,2,3]) , 'testvalue');
        $this->assertFalse($meta);
        $this->assertEquals(0 ,Meta::count());
        $this->metaTruncate();

        $meta = $this->model->updateMeta(false , 'testvalue');
        $this->assertFalse($meta);
        $this->assertEquals(0 ,Meta::count());
        $this->metaTruncate();
    }

    public function test_fail_multiple_meta_if_key_is_invalid()
    {
        $this->model->createMeta('test1' , 'test');
        $this->model->createMeta('test2' , 'test');
        $this->model->createMeta('test3' , 'test');

        $meta = $this->model->updateMeta([
            'test1' => 'testvalue1',
            true => true,
            'test3' => [1,2,3],
            'test4' => 123
        ]);
        $this->assertFalse($meta);
        $this->assertEquals(3 ,Meta::count());
        $this->metaTruncate();
    }

    public function test_fail_if_meta_already_not_exists_using_update_meta()
    {
        $meta = $this->model->updateMeta('test' , 'testvalue2');
        $this->assertFalse($meta);
        $this->assertEquals(0 , Meta::count());
        $this->metaTruncate();
    }

    public function test_fail_if_meta_already_exists_using_update_meta_multiple_create()
    {
        $this->model->createMeta('test1','testvalue2');
        $meta = $this->model->updateMeta([
            'test1' => false,
            'test2' => true,
            'test3' => [1,2,3],
            'test4' => 123
        ]);
        $this->assertFalse($meta);
        $this->assertEquals(1 ,Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta , 'test1' , 'testvalue2' , MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();
    }
}