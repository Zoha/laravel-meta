<?php


namespace Zoha\Meta\Tests\StaticMethods;


use Zoha\Meta\Helpers\MetaHelper as Meta;
use Zoha\Meta\Tests\TestingHelpers;

class TestGuessType extends TestingHelpers
{
    public function test_guess_null()
    {
        $this->assertEquals(Meta::META_TYPE_NULL , Meta::guessType(null));
        $this->assertNotEquals(Meta::META_TYPE_NULL , Meta::guessType('null'));
    }

    public function test_guess_array()
    {
        $this->assertEquals(Meta::META_TYPE_ARRAY , Meta::guessType([1,2,3]));
        $this->assertEquals(Meta::META_TYPE_ARRAY , Meta::guessType([]));
        $this->assertNotEquals(Meta::META_TYPE_ARRAY ,Meta::guessType( collect([1,2,3])));
        $this->assertNotEquals(Meta::META_TYPE_ARRAY , Meta::guessType('[1,2,3]'));
        $this->assertNotEquals(Meta::META_TYPE_ARRAY , Meta::guessType('array'));
    }

    public function test_guess_string()
    {
        $this->assertEquals(Meta::META_TYPE_STRING , Meta::guessType('test'));
        $this->assertEquals(Meta::META_TYPE_STRING , Meta::guessType('123s4'));
        $this->assertEquals(Meta::META_TYPE_STRING , Meta::guessType(''));
        $this->assertEquals(Meta::META_TYPE_STRING , Meta::guessType('true'));
        $this->assertNotEquals(Meta::META_TYPE_STRING , Meta::guessType('123'));
        $this->assertNotEquals(Meta::META_TYPE_STRING , Meta::guessType('[1,2,3]'));
        $this->assertNotEquals(Meta::META_TYPE_STRING , Meta::guessType('[]'));
        $this->assertNotEquals(Meta::META_TYPE_STRING , Meta::guessType('{}'));
    }

    public function test_guess_json()
    {
        $this->assertEquals(Meta::META_TYPE_JSON , Meta::guessType('[1,2,3]'));
        $this->assertEquals(Meta::META_TYPE_JSON , Meta::guessType('[]'));
        $this->assertEquals(Meta::META_TYPE_JSON , Meta::guessType('{}'));
        $this->assertNotEquals(Meta::META_TYPE_JSON , Meta::guessType('test'));
        $this->assertNotEquals(Meta::META_TYPE_JSON , Meta::guessType([1,2,3]));
    }

    public function test_guess_collection()
    {
        $this->assertEquals(Meta::META_TYPE_COLLECTION , Meta::guessType(collect([1,2,3])));
        $this->assertEquals(Meta::META_TYPE_COLLECTION ,Meta::guessType( collect([])));
        $this->assertNotEquals(Meta::META_TYPE_COLLECTION , Meta::guessType([]));
        $this->assertNotEquals(Meta::META_TYPE_COLLECTION , Meta::guessType(collect([1,2,3])->toArray()));
    }

    public function test_guess_boolean()
    {
        $this->assertEquals(Meta::META_TYPE_BOOLEAN , Meta::guessType(true));
        $this->assertEquals(Meta::META_TYPE_BOOLEAN , Meta::guessType(false));
        $this->assertNotEquals(Meta::META_TYPE_BOOLEAN , Meta::guessType(1));
        $this->assertNotEquals(Meta::META_TYPE_BOOLEAN , Meta::guessType(0));
        $this->assertNotEquals(Meta::META_TYPE_BOOLEAN , Meta::guessType('true'));
    }

    public function test_guess_integer()
    {
        $this->assertEquals(Meta::META_TYPE_INTEGER , Meta::guessType(123));
        $this->assertEquals(Meta::META_TYPE_INTEGER , Meta::guessType('123'));
        $this->assertEquals(Meta::META_TYPE_FLOAT, Meta::guessType(1.8));
        $this->assertEquals(Meta::META_TYPE_FLOAT, Meta::guessType('12.34'));
        $this->assertNotEquals(Meta::META_TYPE_FLOAT, Meta::guessType('12.34a'));
        $this->assertNotEquals(Meta::META_TYPE_INTEGER , Meta::guessType('123s'));
    }
}
