<?php
namespace yykweb\yuming;
use houdunwang\framework\build\Provider;

class YumingProvider extends Provider
{
    //延迟加载
    public $defer = true;

    public function boot() {
    }

    public function register() {
        $this->app->single( 'Yuming', function (  ) {
            return new Yuming();
        } );
    }
}