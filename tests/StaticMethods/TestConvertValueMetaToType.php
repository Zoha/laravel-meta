<?php


namespace Zoha\Meta\Tests\StaticMethods;


use Zoha\Meta\Helpers\MetaHelper as Meta;
use Zoha\Meta\Tests\TestingHelpers;

class TestConvertValueMetaToType extends TestingHelpers
{

    public function test_convert_string_to_string()
    {
        $this->assertEquals('test', Meta::convertMetaValueToType('test', Meta::META_TYPE_STRING));
    }

    public function test_convert_json_to_string()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_STRING));
    }

    public function test_convert_collection_to_string()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_STRING));
    }

    public function test_convert_array_to_string()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_STRING));
    }

    public function test_convert_boolean_to_string()
    {
        $this->assertEquals('true', Meta::convertMetaValueToType(true, Meta::META_TYPE_STRING));
    }

    public function test_convert_integer_to_string()
    {
        $this->assertEquals('44', Meta::convertMetaValueToType(44, Meta::META_TYPE_STRING));
    }

    public function test_convert_null_to_string()
    {
        $this->assertEquals(null, Meta::convertMetaValueToType(null, Meta::META_TYPE_STRING));
    }

    public function test_convert_string_to_integer()
    {
        $this->assertEquals(0, Meta::convertMetaValueToType('test', Meta::META_TYPE_INTEGER));
    }

    public function test_convert_json_to_integer()
    {
        $this->assertEquals(0, Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_INTEGER));
    }

    public function test_convert_collection_to_integer()
    {
        $this->assertEquals(0, Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_INTEGER));
    }

    public function test_convert_array_to_integer()
    {
        $this->assertEquals(0, Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_INTEGER));
    }

    public function test_convert_boolean_to_integer()
    {
        $this->assertEquals(1, Meta::convertMetaValueToType(true, Meta::META_TYPE_INTEGER));
        $this->assertEquals(0, Meta::convertMetaValueToType(false, Meta::META_TYPE_INTEGER));
    }

    public function test_convert_integer_to_integer()
    {
        $this->assertEquals(44, Meta::convertMetaValueToType(44, Meta::META_TYPE_INTEGER));
        $this->assertEquals(4.4, Meta::convertMetaValueToType(4.4, Meta::META_TYPE_INTEGER));
    }

    public function test_convert_null_to_integer()
    {
        $this->assertEquals(0, Meta::convertMetaValueToType(null, Meta::META_TYPE_INTEGER));
    }

    public function test_convert_string_to_collection()
    {
        $this->assertEquals(collect([]), Meta::convertMetaValueToType('test', Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_json_to_collection()
    {
        $this->assertEquals(collect([1,2,3]), Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_collection_to_collection()
    {
        $this->assertEquals(collect([1,2,3]), Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_array_to_collection()
    {
        $this->assertEquals(collect([1,2,3]), Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_boolean_to_collection()
    {
        $this->assertEquals(collect([]), Meta::convertMetaValueToType(true, Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_integer_to_collection()
    {
        $this->assertEquals(collect([]), Meta::convertMetaValueToType(44, Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_null_to_collection()
    {
        $this->assertEquals(collect([]), Meta::convertMetaValueToType(null, Meta::META_TYPE_COLLECTION));
    }

    public function test_convert_string_to_json()
    {
        $this->assertEquals('{}', Meta::convertMetaValueToType('test', Meta::META_TYPE_JSON));
    }

    public function test_convert_json_to_json()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_JSON));
    }

    public function test_convert_collection_to_json()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_JSON));
    }

    public function test_convert_array_to_json()
    {
        $this->assertEquals('[1,2,3]', Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_JSON));
    }

    public function test_convert_boolean_to_json()
    {
        $this->assertEquals('{}', Meta::convertMetaValueToType(true, Meta::META_TYPE_JSON));
    }

    public function test_convert_integer_to_json()
    {
        $this->assertEquals('{}', Meta::convertMetaValueToType(44, Meta::META_TYPE_JSON));
    }

    public function test_convert_null_to_json()
    {
        $this->assertEquals('{}', Meta::convertMetaValueToType(null, Meta::META_TYPE_JSON));
    }

    public function test_convert_string_to_array()
    {
        $this->assertEquals([], Meta::convertMetaValueToType('test', Meta::META_TYPE_ARRAY));
    }

    public function test_convert_json_to_array()
    {
        $this->assertEquals([1, 2, 3], Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_ARRAY));
    }

    public function test_convert_collection_to_array()
    {
        $this->assertEquals([1, 2, 3], Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_ARRAY));
    }

    public function test_convert_array_to_array()
    {
        $this->assertEquals([1, 2, 3], Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_ARRAY));
    }

    public function test_convert_boolean_to_array()
    {
        $this->assertEquals([], Meta::convertMetaValueToType(true, Meta::META_TYPE_ARRAY));
    }

    public function test_convert_integer_to_array()
    {
        $this->assertEquals([], Meta::convertMetaValueToType(44, Meta::META_TYPE_ARRAY));
    }

    public function test_convert_null_to_array()
    {
        $this->assertEquals([], Meta::convertMetaValueToType(null, Meta::META_TYPE_ARRAY));
    }

    public function test_convert_string_to_boolean()
    {
        $this->assertEquals(true, Meta::convertMetaValueToType('test', Meta::META_TYPE_BOOLEAN));
        $this->assertEquals(false, Meta::convertMetaValueToType('0', Meta::META_TYPE_BOOLEAN));
        $this->assertEquals(true, Meta::convertMetaValueToType('1', Meta::META_TYPE_BOOLEAN));
        $this->assertEquals(false, Meta::convertMetaValueToType('false', Meta::META_TYPE_BOOLEAN));
        $this->assertEquals(true, Meta::convertMetaValueToType('true', Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_json_to_boolean()
    {
        $this->assertEquals(true, Meta::convertMetaValueToType('[1,2,3]', Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_collection_to_boolean()
    {
        $this->assertEquals(true, Meta::convertMetaValueToType(collect([1, 2, 3]), Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_array_to_boolean()
    {
        $this->assertEquals(true, Meta::convertMetaValueToType([1, 2, 3], Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_boolean_to_boolean()
    {
        $this->assertFalse(false, Meta::convertMetaValueToType(false, Meta::META_TYPE_BOOLEAN));
        $this->assertTrue(true, Meta::convertMetaValueToType(true, Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_integer_to_boolean()
    {
        $this->assertEquals(true, Meta::convertMetaValueToType(44, Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_null_to_boolean()
    {
        $this->assertEquals(false, Meta::convertMetaValueToType(null, Meta::META_TYPE_BOOLEAN));
    }

    public function test_convert_to_null()
    {
        $this->assertEquals(null, Meta::convertMetaValueToType('null', Meta::META_TYPE_NULL));
    }
}