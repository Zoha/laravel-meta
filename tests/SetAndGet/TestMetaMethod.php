<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Models\Meta;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestMetaMethod extends TestingHelpers
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

    public function test_that_meta_method_return_morphMany_relation_on_model()
    {
        $meta = $this->model->meta();
        $this->assertTrue($meta instanceof MorphMany);
    }

    public function test_get_meta_using_meta_method()
    {
        $value = $this->model->meta('key3');
        $this->assertEquals('test', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->meta('key1');
        $this->assertEquals(1, $value);
        $this->assertTrue(is_int($value));

        $value = $this->model->meta('key6');
        $this->assertEquals(null, $value);
        $this->assertTrue(is_null($value));

        $value = $this->model->meta('key8');
        $this->assertEquals(['test1', 'test2'], $value);
        $this->assertTrue(is_array($value));

        $value = $this->model->meta('key5');
        $this->assertEquals(collect(['test1', 'test2']), $value);
        $this->assertTrue($value instanceof Collection);

        $value = $this->model->meta('key7');
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(
            is_string($value) &&
            (is_object(json_decode($value)) ||
                is_array(json_decode($value))));

        $value = $this->model->meta('key4');
        $this->assertEquals(true, $value);
        $this->assertTrue(is_bool($value));
    }

    public function test_add_single_meta_with_best_guess_for_type_using_meta_method()
    {
        $this->metaTruncate();
        //string

        $this->model->meta('test', 'testvalue');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', 'testvalue', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->model->meta('test', [1, 2, 3]);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //json

        $this->model->meta('test', '[1,2,3]');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //collection

        $this->model->meta('test', collect([1, 2, 3]));
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->model->meta('test', 123);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->model->meta('test', '123');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //boolean

        $this->model->meta('test', false, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->model->meta('test', 'testvalue', MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }

    public function test_update_single_meta_with_best_guess_for_type_using_meta_method()
    {
        $this->metaTruncate();
        //string

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', 'testvalue2');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', 'testvalue2', MetaFacade::META_TYPE_STRING);
        $this->metaTruncate();

        //array

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', [1, 2, 3]);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //json

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', '[1,2,3]');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //collection

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', collect([1, 2, 3]));
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '[1,2,3]', MetaFacade::META_TYPE_COLLECTION);
        $this->metaTruncate();

        //integer

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', 123);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', '123');
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '123', MetaFacade::META_TYPE_INTEGER);
        $this->metaTruncate();

        //boolean

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', false, MetaFacade::META_TYPE_BOOLEAN);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', '0', MetaFacade::META_TYPE_BOOLEAN);
        $this->metaTruncate();

        //null

        $this->model->setMeta('test' , 'testvalue');
        $this->model->meta('test', 'testvalue', MetaFacade::META_TYPE_NULL);
        $this->assertEquals(1, Meta::count());
        $meta = Meta::first();
        $this->assertEqualsMeta($meta, 'test', null, MetaFacade::META_TYPE_NULL);
        $this->metaTruncate();
    }
}