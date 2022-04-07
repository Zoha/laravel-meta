<?php


namespace Zoha\Meta\Models;

use Illuminate\Database\Eloquent\Model;
use Zoha\Meta\Database\Factories\ExampleModelFactory;
use Zoha\Metable;

class ExampleModel extends Model
{
    protected $table = 'model';
    protected $fillable = ['title'];

    use Metable;

    public static function factory()
    {
        return ExampleModelFactory::new();
    }
}
