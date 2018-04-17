<?php


namespace Zoha;


use Zoha\Meta\Traits\DeleteMeta;
use Zoha\Meta\Traits\GetMeta;
use Zoha\Meta\Traits\MetableBase;
use Zoha\Meta\Traits\MetaClauses;
use Zoha\Meta\Traits\SetMeta;

trait Metable
{
    use MetableBase , GetMeta , SetMeta , DeleteMeta , MetaClauses;
}