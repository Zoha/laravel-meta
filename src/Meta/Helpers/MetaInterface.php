<?php


namespace Zoha\Meta\Helpers;


class MetaInterface
{

    //----------------------------------------- Properties ------------------------------------------//

    protected $configs = [
        'env' => 'product'
    ];

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * return specify config value
     * 
     * @param string $key
     * @return mixed
     */
    public function config($key)
    {
        return isset($this->configs[$key])? $this->configs[$key] : null;
    }
    
    /**
     * check package in on develop evnironment or not
     * 
     * @return bool
     */
    public function isOnDevelop()
    {
        return $this->configs['env'] === 'develop' ;
    }
}