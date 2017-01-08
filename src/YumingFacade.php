<?php
namespace yykweb\yuming;
use houdunwang\framework\build\Facade;

class YumingFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Yuming';
    }
}