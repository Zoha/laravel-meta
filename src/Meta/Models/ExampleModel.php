<?php


namespace Zoha\Meta\Models;


use Illuminate\Database\Eloquent\Model;
use Zoha\Metable;
use Zoha\MetableModel;

class ExampleModel extends Model
{
    protected $table = 'model';
    protected $fillable = ['title'];

    use Metable;
}