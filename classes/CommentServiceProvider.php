<?php

namespace Ecjia\App\Comment;

use Royalcms\Component\App\AppServiceProvider;

class CommentServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-comment');
    }
    
    public function register()
    {
        
    }
    
    
    
}