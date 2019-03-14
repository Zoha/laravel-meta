<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestSetMetaUsingProperty extends TestingHelpers
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

    public function test_add_single_meta_using_property()
    {
        $this->model->meta->test = 'testvalue';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->metaTruncate();
    }

    public function test_if_meta_with_same_key_already_exists_set_property_update_it()
    {
        $this->model->meta->test = 'testvalue';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->assertEquals(1, Meta::all()->count());
        $this->model->meta->test = 'testvalue1';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEquals(1, Meta::all()->count());
        $this->assertEqualsMeta($meta, 'test', 'testvalue1');
        $this->metaTruncate();
    }

    public function test_add_single_meta_with_best_guess_of_type()
    {
        $this->metaTruncate();

        // string
        $this->model->meta->test = 'testvalue';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta);
        $this->metaTruncate();

        //boolean
        $this->model->meta->test = true;
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '1', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        $this->model->meta->test = false;
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        // array json and collection _> to collection
        $this->model->meta->test = [1, 2, 3];
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        $this->model->meta->test = collect([1, 2, 3]);
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        $this->model->meta->test = '[1,2,3]';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        // integer
        $this->model->meta->test = '123';
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->model->meta->test = 123;
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        // null
        $this->model->meta->test = null;
        $this->model->meta->save();
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_add_single_meta_using_property_fail_if_save_method_not_called()
    {
        $this->model->meta->test = 'testvalue';
        $this->assertEquals(0,Meta::count());
        $this->model->createMeta('key1' , 'testvalue');
        $this->assertEquals(1,Meta::count());
        $meta = $this->model->meta->save();
        $this->assertFalse($meta);
        $this->assertEquals(1 , Meta::count());
    }
}