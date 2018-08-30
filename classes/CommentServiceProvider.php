<?php

namespace Ecjia\App\Comment;

use Royalcms\Component\App\AppParentServiceProvider;

class CommentServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-comment', null, dirname(__DIR__));
    }
    
    public function register()
    {
        
    }
    
    
    
}