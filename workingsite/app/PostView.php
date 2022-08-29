<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;
use App\Post;


class PostView extends Model
{
    protected $table = 'post_views';

    public function postView(){
        return $this->belongsTo(Post::class);
    }

    public static function createViewLog($post) {
        $postViews= new PostView();
        $postViews->post_id = $post->post_id;
        $postViews->titleslug = $post->titleslug;
        $postViews->url = request()->url();
        $postViews->session_id = request()->getSession()->getId();
         //this check will either put the user id or null, no need to use \Auth()->user()->id as we have an inbuild function to get auth id
        $postViews->user_id = (auth()->check()) ? auth()->id() : null; 
        $postViews->ip = request()->ip();
        $postViews->agent = request()->header('User-Agent');
        
        $postViews->save();
    }
}
