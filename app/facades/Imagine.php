<?php namespace App\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class Imagine extends Facade {
 
    protected static function getFacadeAccessor()
    {
        return new \App\Services\Imagine;
    }
 
}