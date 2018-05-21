
# Laravel Meta
a laravel package for working with meta !

[![Packagist License](https://img.shields.io/apm/l/vim-mode.svg)](http://choosealicense.com/licenses/mit/)
[![Test Version](https://img.shields.io/php-eye/symfony/symfony.svg)](http://choosealicense.com/licenses/mit/)
[![Stable Version](https://img.shields.io/badge/stable-v1.3-green.svg)](http://choosealicense.com/licenses/mit/)
[![Laravel Package](https://img.shields.io/badge/package-Laravel-red.svg)](http://choosealicense.com/licenses/mit/)

# Page Contents
1. [Introduction](#introduction)
2. [Features](#features)
3. [Installation](#installation)
4. [Basic Methods](#basic-methods)
    * [Create New Meta](#create-new-meta)
    * [Update Meta](#update-meta)
    * [Create Or Update Meta](#create-or-update-meta)
    * [Get Meta](#get-meta)
    * [Delete Meta](#delete-meta)
    * [Check Meta Exists Or Not](#check-meta-exists-or-not)
    * [Increase Meta](#increase-meta)
    * [Decrease Meta](#decrease-meta)
5. [Clauses](#clauses)
    * [Where Meta Clause](#where-meta-clause)
    * [Where Meta In Clause](#where-meta-in-clause)
    * [Where Meta Between Clause](#where-meta-between-clause)
    * [Where Meta Null Clause](#where-meta-null-clause)
    * [Where Meta Has Clause](#where-meta-has-clause)
    * [Order By Meta](#order-by-meta)
    * [Eager Loading](#eager-loading)
6. [Other Methods And Features](#other-methods-and-features)
    * [Notes](#notes)
    * [Data Type](#data-type)
    * [Custom Meta Table](#custom-meta-table)
    * [Meta Model](#meta-model)
    * [Meta Table](#meta-table)
7. [License](#license)
# Introduction
![gif-gile](https://raw.githubusercontent.com/ZohaBanam/laravel-meta/master/meta-package-gif.gif)
Sometimes our models in laravel needs a lot of information that should be stored in the database.
For example, Suppose you want to create a blog and add a model for posts on this blog.
The most important information that this model will need is its title and content.
But this model may also have more information, Such as the number of likes, download links, thumbnails, views, and more.
When the amount of this information is high, We need to create more columns for the model table, Which if there are too many, Will be difficult.
Additionally, Each of these posts may have their own unique information that the rest of the posts do not need.
In such cases, This package will help. If we want to explain this package simply:
Laravel Meta allows you to store information for each of your models and easily access them without having to create new columns for this information in the database tables.
If you still do not notice it, Just look at the examples below.

# Features
   - It is easy to store and receive meta for each model
   - An alternative to creating many columns in the database table
   - Minimum number of queries and complete optimization
   - Ability to filter database records by meta
# Installation
First , Require this package with composer
```
 $ composer require zoha/laravel-meta
```
If you Use Laravel <= 5.4 add the ServiceProvider and Alias in config/app.php 
```PHP
'providers' => [
    ...
    Zoha\Meta\MetaServiceProvider::class,
    ...
]
'aliases' => [
    ...
    'Meta' => Zoha\Meta\Facades\MetaFacade::class,
    ...
]

```
And execute migrate command to migrate meta table 
```
 $ php artisan migrate
```
And then to add meta functionality to each of your models, You just need to extends it from MetableModel instead of the Model.
```PHP
use Zoha\MetableModel;

class Post extends MetableModel
{
    ...
}
```
Or you can use Metable trait instead :
```PHP
use Zoha\Metable;

class Post extends Model
{
    use Metable;
    ...
}
```
##### Optional Section

You can publish meta config file using this command :
```
 $ php artisan vendor:publish --provider="Zoha\Meta\MetaServiceProvider" --tag=config
```
In this file you can change default meta table ( default: meta )
Or you can [customize meta table for a specific model](#custom-meta-table)

That's it! Now you can use all of the Laravel meta features
> In all of the examples below, we assume that the $post is set to Post::first() which Post model is an example model

# Basic Methods
### Create New Meta
For create a meta you can use `createMeta` on Model : 
```PHP
$post->createMeta('key' , 'value');
```
Or you can create multiple meta like this : 
```PHP
$post->createMeta([
    'key1' => 'value1',
    'key2' => 'value2'
]);
```
@return : `createMeta` method returns **true** if the meta creation is performed successfully and **false** otherwise
> `addMeta` method is alias of `createMeta` method
### Update Meta
For update a meta you can use `updateMeta` on Model : 
```PHP
$post->updateMeta('key' , 'new value');
```
Or you can update multiple meta like this : 
```PHP
$post->updateMeta([
    'key1' => 'new value 1',
    'key2' => 'new value 2'
]);
```
@return : If the update was successful `updateMeta` return **true** . but if meta already not exists or updating was faild **false** will be returned
### Create Or Update Meta
For create or update meta use `setMeta` method
this method will update meta or create a new one if not exists yet
```PHP
$post->setMeta('key' , 'value'); // create meta
$post->setMeta('key' , 'new value'); // update meta
```
Or you can set multiple meta like this
```PHP
$post->setMeta([
    'key1' => 'value 1',
    'key2' => 'value 2'
]);
```
@return : `setMeta` returns **true** if it is successful. Otherwise it will return **false**

Or instead of this method, You can set meta using **meta property** like this :
```PHP
$post->meta->key1 = 'value';
$post->meta->key2 = 'value2';
$post->meta->save();
```
### Get Meta
`getMeta` method will return meta value : 
```PHP
//return meta value or null if meta not exists
$post->getMeta('key'); 

// return value or 'default value ' if not exists or value is null
$post->getMeta('key' , 'default value') ; 
```
Also you can get meta values using **meta property** : 
```PHP
$post->meta->key; // return meta value 
```
### Delete Meta
For delete meta use `deleteMeta`  method 
```PHP
$post->deleteMeta(); // delete all meta of this model

$post->deleteMeta('key'); // delete a specific meta
```
Or you can use `unsetMeta` method  instead . both of methods are the same.
`truncateMeta` also delete all meta of specific model :
```PHP
$post->truncateMeta(); // delete all model meta
```
### Check Meta Exists Or Not
You can use `hasMeta` method if you want to check there is a particular meta or not ( if meta exists but value equals to null false will be returned )
```PHP
$post->hasMeta(); // return true if this model has at least one meta
$post->hasMeta('key'); // return true or false
```
The second argument specifies whether or not to accept null values . If you pass **true** for second argument , and meta exists even if value equals to null true will be returned
```PHP
$post->setMeta('key' , null); // set key to null 
$post->hasMeta('key'); // return false
$post->hasMeta('key' , true); // return true
```
If you want to specify that a particular post has a specific meta, even if its value is null , use the `existsMeta` method.
```PHP
$post->setMeta('key' , null); // set key to null 
$post->existsMeta('key'); // return true
```
### Increase Meta
You can simply increase meta value using `increaseMeta` method
```PHP
$post->setMeta('key' , 3);
$post->increaseMeta('key'); // meta value will change to 4

$post->setMeta('key2' , 'not integer value');
$post->increaseMeta('key2'); // meta value will not change
```
You can pass second argument for detemine increase step 
```PHP
$post->setMeta('key' , 3);
$post->increaseMeta('key',3); // meta value will change to 6
```
### Decrease Meta
You can simply decrease meta value using `decreaseMeta` method
```PHP
$post->setMeta('key' , 3);
$post->decreaseMeta('key'); // meta value will change to 2

$post->setMeta('key2' , 'not integer value');
$post->decreaseMeta('key2'); // meta value will not change
```
And you can pass second argument for detemine decrease step 
```PHP
$post->setMeta('key' , 3);
$post->decreaseMeta('key',3); // meta value will change to 0
```
# Clauses
### Where Meta Clause
Filter items by meta value : 
```PHP
$result = Post::whereMeta('key','value');
// you can use operator :
$result = Post::whereMeta('key', '>' , 100);

// you can use multiple whereMeta Clause
$result = Post::whereMeta('key', '>' , 100)
                            ->whereMeta('key' , '<' , 200);
//orWhereMeta clause
$result = Post::whereMeta('key', '>' , 100)
                            ->orWhereMeta('key' , '<' , 50);
//branched clauses
$result = Post::where(function($query){
    $query->where(function($query){
        $query->whereMeta('key1' , 'value1');
        $query->orWhereMeta('key1' , 'value2');
    });
    $query->whereMeta('key2' , 'like' , '%value%');
    $query->WhereMeta('key3' , '>' , 100);
});
```
You can also pass a array for filter result :
```PHP
// all of below conditions will converted to 'AND'
$result = Post::whereMeta([
    'key1' => 'value1',
    [ 'key2' , '!=' , 'value2' ],
    [ 'key3' , 'value3' ]
]);
```
You can aslso use orWhere for mulitple or where clause :
```PHP
$result = Post::orWhereMeta([
    'key1' => 'value1',
    [ 'key2' , '!=' , 'value2' ],
    [ 'key3' , 'value3' ]
]);
```
> **You can use branched filters for all meta clauses**
### Where Meta In Clause

`whereMetaIn` and `whereMetaNotIn` clauses : 
```PHP
$result = Post::whereMetaIn('key', ['value1' , 'value2']);
$result = Post::whereMetaNotIn('key', ['value1' , 'value2']);

// multiple clauses 
$result = Post::whereMetaIn('key', [1,2])
                            ->whereMetaIn('key2' , [1,2]);
                            
// 'orWhere' clauses
$result = Post::whereMetaNotIn('key', [1,2,3])
                            ->orWhereMetaIn('key2', [1,2])
                            ->orWhereMetaNotIn('key3', [1,2]);
```
### Where Meta Between Clause

`whereMetaBetween` and `whereMetaNotBetween` clauses : 
```PHP
$result = Post::whereMetaBetween('key', [0,100]);
$result = Post::whereMetaNotBetween('key', [0,100]);

// multiple clauses 
$result = Post::whereMetaBetween('key', [100,200])
                            ->whereMetaBetween('key2' , [100,200]);
                            
// 'orWhere' clauses
$result = Post::whereMetaNotBetween('key', [1000,8000])
                            ->orWhereMetaBetween('key2', [1,5])
                            ->orWhereMetaNotBetween('key3', [0,100]);
```
### Where Meta Null Clause

`whereMetaNull` and `whereMetaNotNull` clauses : 
```PHP
$result = Post::whereMetaNull('key');
$result = Post::whereMetaNotNull('key');

// multiple clauses 
$result = Post::whereMetaNull('key')
                            ->whereMetaNull('key2');
                            
// 'orWhere' clauses
$result = Post::whereMetaNotNull('key')
                            ->orWhereMetaNull('key2')
                            ->orWhereMetaNotNull('key3');
```
### Where Meta Has Clause

`whereMetaHas` and `whereMetaDoesntHave` clauses : 
```PHP
//filter records that has at least one meta
$result = Post::whereMetaHas();

$result = Post::whereMetaHas('key'); 
$result = Post::whereMetaHas('key' , true); //count null values

$result = Post::whereMetaDoesntHave('key');
$result = Post::whereMetaDoesntHave('key' , true); //count null values

// multiple clauses 
$result = Post::whereMetaHas('key')
                            ->whereMetaDoesntHave('key2');
                            
// 'orWhere' clauses
$result = Post::whereMetaDoesntHave('key')
                            ->orWhereMetaHas('key2')
                            ->orWhereMetaDoesntHave('key3');
```
### Order By Meta
You Can Sort Database Results Using  `orderByMeta` clause : 
```PHP
Post::orderByMeta('price')->get();

Post::orderByMeta('price' , 'desc')->get();

Post::orderByMeta('price')->orderByMeta('likes' , 'desc')->get();
```
### Eager Loading

If you call `$post->getMeta('key')` for the first time all of this model meta will be loaded ( once ) and if you try to get another meta from this model another query will not be executed and meta value will loaded from previous query. 
But if you try to get meta in another model, Another query will be executed for new model . If you want get all meta of all models in one query you can use eager loading of meta . you just need to call `withMeta` scope like this :
```PHP

$posts = Post::withMeta()->get(); // will return all posts results with their meta values
```
Note that `with('meta')` will not work
# Other Methods And Features
### Notes
 - by default all collections , arrrays and json values will convert to collection when you try to get them. 
 - all `[]` , `"{}"` , `"[]"` and `null` values will be considered null.
 - If one of the items in the metable model is deleted, All the meta related to that item will be deleted too.
### Data Type

All of `setMeta` , `getMeta` , `updateMeta` And `createMeta` methods accept a third argument that determine meta data type .  there are some examples of this feature :
>Available data types : `string` , `integer` , `null` , `collection` , `json` `array` , `boolean`.

In `setMeta` method 
```PHP
$post->setMeta('key' , '123' , 'integer');
$post->meta->key; // 123 ( integer )
//----------------------------------
$post->setMeta('key' , [1,2,3] , 'json');
$post->meta->key; // "[1,2,3]" ( string - json )
//----------------------------------
$post->setMeta([
    'key1' => 'value',
    'key2' => 2
    'key3' => [1,2,3] 
],'string' ); // all values will converted to string when you try to get them
$post->meta->key2; // "2" ( string )
$post->meta->key3; // "[1,2,3]" ( string json )
```
Third argument in `createMeta` and `updateMeta` methods is the same of `setMeta` method

In `getMeta` method 
```PHP
$post->setMeta('key' , 123);
$post->getMeta('key' , 'null' , 'string'); // "123" (string)
//----------------------------------
$post->setMeta('key' , [1,2,3] , 'string');
$post->getMeta('key' , 'null'); // "[1,2,3]" (string)
$post->getMeta('key' , 'null' , 'array'); // [1,2,3] (array)
$post->getMeta('key' , 'null' , 'boolean'); // true (boolean)
```
### Custom Meta Table
By default, all meta-data for all models will be stored in the `meta` database table.
But if you want, you can use a separate table for a particular model.

For example, you have `Post`, `Comment` and `User` models
You want to store all users and comments meta in default table but the user's meta tags are in a special table
To do this you have to take four steps :

First Step : you should publish package config file using this command : 
```
$ php artisan vendor:publish --provider="Zoha\Meta\MetaServiceProvider" --tag=config
```
This command will place a file named `meta.php` in your config folder

Second Step : open config file in `tables` array you find a custom array . in this array you can add new tables name . 
for our example we add `users_meta` in this array to handle users meta : 
```PHP
'tables' => [
    'default' => 'meta',
    'custom'  => [
        'users_meta'
    ],
]
```

Third Step : you need to run migrate command to create new table .
```
$ php artisan migrate 
```
If you have already migrated Meta migration, you should rollback this migrations and migrate it again ( to create new tables ) 

Final Step : now that the new table is made, you can introduce it to the `User` model (or any model you want) to handle its meta with this table . like this :
```PHP
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use \Zoha\Metable;
    
    protected $metaTable = 'users_meta';
}

```


### Meta Model 
you are free to use meta model in your project
```PHP
use Zoha\Meta\Models\Meta;
$count = Meta::count();
```
 Note : If you change meta values using meta model ( change database directly ) meta valuse that was loaded before will not be updated . If you want to update them you should call `refreshLoadedMeta` metahod :

```PHP
use Zoha\Meta\Models\Meta;

$post->meta->key1; // exmaple : return 'test'
Meta::truncate();
$post->meta->key1; // still return 'test'

$post->refreshLoadeMeta();
$post->meta->key1; // will return null
```
### Meta Table
The structure of the meta table is this : 
```PHP
Schema::create('meta', function (Blueprint $table) {
    $table->increments('id');
    $table->string('key',110);
    $table->text('value')->nullable();
    $table->string('type')->default(Meta::META_TYPE_STRING);
    $table->boolean('status')->default(true);
    $table->string('owner_type',80);
    $table->integer('owner_id');
    $table->unique(['key','owner_type','owner_id']);
    $table->timestamps();
});
```
# License 
[![Packagist License](https://img.shields.io/apm/l/vim-mode.svg)](http://choosealicense.com/licenses/mit/)
