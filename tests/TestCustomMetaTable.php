<?php


namespace Zoha\Meta\Tests;


use Illuminate\Support\Facades\Schema;
use Zoha\Meta\Models\CustomMetaTableModel;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Models\Meta;

class TestCustomMetaTable extends TestCase
{
    public function test_custom_table_exists()
    {
        $this->assertTrue(Schema::hasTable('tests_meta'));
    }

    public function test_tables_are_separated()
    {
        CustomMetaTableModel::create([
            'title' => 'custom'
        ]);
        $this->assertEquals(1, CustomMetaTableModel::count());
        $customTable = CustomMetaTableModel::first();

        $defaultModel = ExampleModel::factory()->create();
        $defaultModel->setMeta([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $customTable->setMeta('key3', 'value3');

        $this->assertEquals(2, Meta::count());

        $this->assertEquals(1, $customTable->meta->count());

        $customTable->setMeta('key3', 'value4');

        $this->assertEquals(2, Meta::count());
        $this->assertEquals(1, $customTable->meta->count());
    }
}
