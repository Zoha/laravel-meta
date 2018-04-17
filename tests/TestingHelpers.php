<?php


namespace Zoha\Meta\Tests;

use Zoha\Meta\Models\Meta;
use Zoha\Meta\Models\ExampleModel;

class TestingHelpers extends TestCase
{

    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    protected $tablesModels = [
        'model' => \Zoha\Meta\Models\ExampleModel::class,
        'meta'  => \Zoha\Meta\Models\Meta::class,
    ];

    private $fakeDataMeta = [
        [
            [
                'key'        => 'key1',
                'value'      => 1,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 1,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key2',
                'value'      => 1,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 1,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key3',
                'value'      => 'test',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING,
                'owner_id'   => 1,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key4',
                'value'      => true,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_BOOLEAN,
                'owner_id'   => 1,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key5',
                'value'      => '["test1","test2"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_COLLECTION,
                'owner_id'   => 1,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ]
        ],
        [
            [
                'key'        => 'key1',
                'value'      => 2,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 2,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key2',
                'value'      => 1,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 2,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key3',
                'value'      => 'test',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING,
                'owner_id'   => 2,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key4',
                'value'      => true,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_BOOLEAN,
                'owner_id'   => 2,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key5',
                'value'      => '["test1","test2"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_ARRAY,
                'owner_id'   => 2,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
        ],
        [
            [
                'key'        => 'key1',
                'value'      => 2,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 3,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key2',
                'value'      => 2,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 3,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key3',
                'value'      => 'test2',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING,
                'owner_id'   => 3,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key4',
                'value'      => false,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_BOOLEAN,
                'owner_id'   => 3,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key5',
                'value'      => '["test3","test4"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_JSON,
                'owner_id'   => 3,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
        ],
        [
            [
                'key'        => 'key1',
                'value'      => 3,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 4,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key2',
                'value'      => 4,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 4,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key3',
                'value'      => null,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING,
                'owner_id'   => 4,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key4',
                'value'      => null,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_BOOLEAN,
                'owner_id'   => 4,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key5',
                'value'      => '["test5","test6"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_ARRAY,
                'owner_id'   => 4,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
        ],
        [
            [
                'key'        => 'key1',
                'value'      => 1,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key2',
                'value'      => 2,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_INTEGER,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key3',
                'value'      => 'test',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key4',
                'value'      => true,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_BOOLEAN,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key5',
                'value'      => '["test1","test2"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_COLLECTION,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key6',
                'value'      => null,
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_NULL,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key7',
                'value'      => '["test1","test2"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_JSON,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key8',
                'value'      => '["test1","test2"]',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_ARRAY,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
            [
                'key'        => 'key9',
                'value'      => '{"test100":"test2","test2323":"test4"}',
                'type'       => \Zoha\Meta\Helpers\MetaHelper::META_TYPE_ARRAY,
                'owner_id'   => 5,
                'owner_type' => 'Zoha\Meta\Models\ExampleModel'
            ],
        ],
    ];

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * truncate all database tables or specific table
     *
     * @return void
     */
    protected function truncate($class = null)
    {
        if ($class == null) {
            foreach ($this->tablesModels as $class) {
                $this->truncate($class);
            }
            return;
        }

        $class::truncate();

        if($class = Meta::class){
            if($this->model != null){
                $this->model->truncateMeta();
            }
        }
    }

    /**
     * create fake data in tables for tests
     *
     * @return void
     */
    protected function seeding($class = null)
    {
        if ($class == null) {
            $this->truncate();
            foreach ($this->tablesModels as $class) {
                $this->seeding($class);
            }
            return;
        }
        if ($class == $this->tablesModels['model']) {
            foreach (range(0, 4) as $index) {
                $class::create([
                    'title' => 'model ' . $index
                ]);
            }
            return;
        } elseif ($class == $this->tablesModels['meta']) {
            $index = 0;
            foreach (ExampleModel::all() as $model) {
                foreach ($this->fakeDataMeta[$index] as $metaGroup) {
                    $class::create($metaGroup);
                }
                $index++;
            }
        }
    }

    /**
     * delete all meta data from db
     *
     * @return void
     */
    protected function metaTruncate()
    {
        $this->truncate(Meta::class);
    }

    /**
     * delete all model data from db
     *
     * @return void
     */
    protected function modelTruncate()
    {
        $this->truncate(ExampleModel::class);
    }

    /**
     * check assert columns of an specific Meta
     *
     *
     */
    protected function assertEqualsMeta(
        $meta,
        $key = 'test',
        $value = 'testvalue',
        $type = \Zoha\Meta\Helpers\MetaHelper::META_TYPE_STRING
    ) {
        $this->assertEquals($key, $meta->key);
        $this->assertEquals($value, $meta->value);
        $this->assertEquals($type, $meta->type);
        $this->assertEquals(get_class($this->model), $meta->owner_type);
        $this->assertEquals($this->model->id, $meta->owner_id);
    }

    /**
     * fast create a meta and return it
     *
     * @param string $type
     * @return Meta
     */
    public function fastCreateMeta($key = 'test', $value = 'testvalue', $type = null)
    {
        $this->truncate(Meta::class);
        $this->model->setMeta($key, $value, $type);
        return Meta::first();

    }

    //--------------------------------------- Test Methods -----------------------------------------//

    public function test_truncate_method_deletes_all_data_in_all_tables()
    {
        factory(ExampleModel::class)->create();
        factory(Meta::class)->create([
            'owner_type' => 'test',
            'owner_id'   => 1,
        ]);
        $this->assertNotEquals(0, Meta::all()->count());
        $this->assertNotEquals(0, ExampleModel::all()->count());
        $this->truncate();
        $this->assertEquals(0, Meta::all()->count());
        $this->assertEquals(0, ExampleModel::all()->count());
    }

    public function test_truncate_method_deletes_all_data_in_specific_tables()
    {
        factory(ExampleModel::class)->create();
        factory(Meta::class)->create([
            'owner_type' => 'test',
            'owner_id'   => 1,
        ]);
        $this->assertNotEquals(0, Meta::all()->count());
        $this->assertNotEquals(0, ExampleModel::all()->count());
        $this->truncate(Meta::class);
        $this->assertEquals(0, Meta::all()->count());
        $this->assertNotEquals(0, ExampleModel::all()->count());
    }

    public function test_seed_method_create_new_data_in_all_tables()
    {
        $this->truncate();
        $this->assertEquals(0, ExampleModel::all()->count());
        $this->assertEquals(0, Meta::all()->count());
        $this->seeding();
        $this->assertEquals(5, ExampleModel::all()->count());
        $this->assertEquals(29, Meta::all()->count());
        $this->assertEquals(Meta::find(10)->key, 'key5');
        $this->assertEquals(Meta::find(25)->key, 'key5');
        $this->assertEquals(Meta::find(6)->value, 2);
        $this->truncate();
    }
}