<?php

namespace App\Controllers;

class Controller
{
    protected $app;

    protected $varName;
    
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function __get($property)
    {
        if ($this->app->{$property}) {
            return $this->app->{$property};
        }
    }
}
