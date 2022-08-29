<?php
namespace App;

use App\PostView;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function postView(){
        return $this->hasMany(PostView::class);
    }

    public function showPost(){
        if(auth()->id()==null){
            return $this->postView()
            ->where('ip', '=',  request()->ip())->exists();
        }

        return $this->postView()
        ->where(function($postViewsQuery) { $postViewsQuery
            ->where('session_id', '=', request()->getSession()->getId())
            ->orWhere('user_id', '=', (auth()->check()));})->exists();  
    }
}
