<?php


namespace Zoha\Meta\Tests\SetAndGet;


use Illuminate\Support\Collection;
use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;
use Zoha\Meta\Helpers\MetaHelper as MetaFacade;

class TestGetMetaMethod extends TestingHelpers
{
    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    //------------------------------------------ Methods --------------------------------------------//

    public function setUp() : void
    {
        parent::setUp();
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
        $this->model = ExampleModel::get()->last();
    }

    public function test_get_meta_using_get_meta_method()
    {
        $this->assertEquals(5 , $this->model->id);

        $value = $this->model->getMeta('key3');
        $this->assertEquals('test', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key1');
        $this->assertEquals(1, $value);
        $this->assertTrue(is_int($value));

        $value = $this->model->getMeta('key6');
        $this->assertEquals(null, $value);
        $this->assertTrue(is_null($value));

        $value = $this->model->getMeta('key8');
        $this->assertEquals(['test1', 'test2'], $value);
        $this->assertTrue(is_array($value));

        $value = $this->model->getMeta('key5');
        $this->assertEquals(collect(['test1', 'test2']), $value);
        $this->assertTrue($value instanceof Collection);

        $value = $this->model->getMeta('key7');
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(
            is_string($value) &&
            (is_object(json_decode($value)) ||
                is_array(json_decode($value))));

        $value = $this->model->getMeta('key4');
        $this->assertEquals(true, $value);
        $this->assertTrue(is_bool($value));
    }

    public function test_get_meta_using_get_meta_method_with_default_values()
    {
        $value = $this->model->getMeta('notExistsKey' , 'default');
        $this->assertEquals('default', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('notExistsKey' , true);
        $this->assertEquals(true, $value);
        $this->assertTrue(is_bool($value));

        $value = $this->model->getMeta('notExistsKey' , 123);
        $this->assertEquals(123, $value);
        $this->assertTrue(is_int($value));

        $value = $this->model->getMeta('notExistsKey' , collect([1,2,3]));
        $this->assertEquals(collect([1,2,3]) , $value);
        $this->assertTrue($value instanceof Collection);

        $value = $this->model->getMeta('notExistsKey' , []);
        $this->assertEquals([], $value);
        $this->assertTrue(is_array($value));

        $value = $this->model->getMeta('notExistsKey' , '[1,2,3]');
        $this->assertEquals('[1,2,3]', $value);

        $value = $this->model->getMeta('notExistsKey' , null);
        $this->assertEquals(null, $value);
        $this->assertTrue(is_null($value));
    }

    public function test_get_meta_using_get_meta_method_with_custom_type_for_string()
    {
        $value = $this->model->getMeta('key3' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('test', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key1' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('1', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key6' , 'default' , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('default', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key8' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key5' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key7' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('["test1","test2"]', $value);
        $this->assertTrue(is_string($value));

        $value = $this->model->getMeta('key4' , null , MetaFacade::META_TYPE_STRING);
        $this->assertEquals('1', $value);
        $this->assertTrue(is_string($value));
    }

    public function test_get_meta_using_get_meta_method_after_change_database_results_by_other_methods()
    {
        $this->model->meta->test = 'testvalue';
        $this->assertEquals(null , $this->model->getMeta('test'));
        $this->metaTruncate();

        $this->model->createMeta('test' , 'testvalue');
        $this->assertEquals('testvalue' , $this->model->getMeta('test'));

        $this->model->updateMeta('test' , 'testvalue2');
        $this->assertEquals('testvalue2' , $this->model->getMeta('test'));
        $this->metaTruncate();
    }

    public function test_get_meta_method_for_other_cases()
    {
        $this->model->setMeta('test',0);
        $meta = Meta::where('key' , 'test')->first();
        $this->assertEqualsMeta($meta ,'test' , 0 , MetaFacade::META_TYPE_INTEGER);

        $this->model->setMeta('test',0);
        $this->assertEquals(0 , $this->model->getMeta('test' , 'no'));
        $this->model->deleteMeta('test');

    }
}