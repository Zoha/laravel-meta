<?php


namespace Zoha\Meta\Models;


use Illuminate\Database\Eloquent\Model;
use Zoha\Metable;

class CustomMetaTableModel extends Model
{
    use Metable;

    protected $metaTable = 'tests_meta';
    protected $table = 'model';
    protected $fillable = ['title'];
}