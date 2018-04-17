<?php


namespace Zoha\Meta\Models;


use Zoha\MetableModel;

class ExampleModel extends MetableModel
{
    protected $table = 'model';
    protected $fillable = ['title'];
}