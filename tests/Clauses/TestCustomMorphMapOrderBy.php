<?php


namespace Zoha\Meta\Tests\Clauses;


use Illuminate\Database\Eloquent\Relations\Relation;
use Zoha\Meta\Models\ExampleModel;
use Zoha\Meta\Tests\TestingHelpers;

class TestCustomMorphMapOrderBy extends TestingHelpers
{
    protected function setUp(): void
    {
        parent::setUp();
        Relation::morphMap([
            'TESTING' => ExampleModel::class,
        ]);
        $this->replaceOwnerType('TESTING');
        $this->modelTruncate();
        $this->metaTruncate();
        $this->seeding();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Relation::$morphMap = [];
    }

    function test_order_by_meta_with_custom_morph_map()
    {
        $results = ExampleModel::orderByMeta('key1', 'desc')->pluck('id')->toArray();
        $this->assertEquals([4, 2, 3, 1, 5], $results);

        $results = ExampleModel::orderByMeta('key1', 'asc')->pluck('id')->toArray();
        $this->assertEquals([1, 5, 2, 3, 4], $results);

    }
}
