<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class BlogController extends Controller
{
    public function showBlogs()
    {
        $blogs = Blog::where('status', '1')->orderBy('updated_at', 'desc')->get(); // $this->getCollections();

        
        if (\Auth::check()) {
            $user = \Auth::user();
            if($user->role->name == "admin" ||  $user->role->name == "Staff") {
                $blogs = Blog::orderBy('updated_at', 'desc')->get(); // $this->getCollections();
            }
        }

        SEOMeta::setTitle('Wallcovering Blog Post');
        SEOMeta::setDescription('List of wallcovering blog post');
        SEOMeta::addKeyword('wallcovering, design, Innovations USA, blog post, New York, NYC');

        OpenGraph::setDescription('List of wallcovering Blog');
        OpenGraph::setTitle('Wallcovering Blog Post');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'blog post');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Wallcovering Blog Post');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Wallcovering Blog Post');
        JsonLd::setDescription('List of wallcovering blog post');
        JsonLd::setType('blog post');

        return view('content-types.blog-list', [
            'pageType' => "all",
            'list' => $blogs
        ]);
    }

    public function showCategory($category)
    {

        $categories = BlogCategory::where('slug', $category)->orderBy('updated_at', 'desc')->first(); // $this->getCollections();        
        
        if ($categories == NULL) {
            return redirect('/blog');
        }
        //dd($categories);

        SEOMeta::setTitle('Wallcovering Blog Post');
        SEOMeta::setDescription('List of wallcovering blog post');
        SEOMeta::addKeyword('wallcovering, design, Innovations USA, blog post, New York, NYC');

        OpenGraph::setDescription('List of wallcovering Blog');
        OpenGraph::setTitle('Wallcovering Blog Post');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'blog post');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Wallcovering Blog Post');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Wallcovering Blog Post');
        JsonLd::setDescription('List of wallcovering blog post');
        JsonLd::setType('blog post');

        if (\Auth::check()) {
            $user = \Auth::user();
            if($user->role->name == "admin" ||  $user->role->name == "Staff") {
                return view('content-types.blog-list', [
                    'pageType' => "category",
                    'list' => $categories->blogs,
                    'category' => $categories
                ]); 
            }
        }

        return view('content-types.blog-list', [
            'pageType' => "category",
            'list' => $categories->blogs->where('status', '1'),
            'category' => $categories
        ]);
    }

    public function showBlog($category, $blog)
    {        

        $blogContent = Blog::where('slug', $blog)->first();

        if ($blogContent == NULL) {
            return redirect('/blog');
        }
        if (!empty($blogContent)) {
            SEOMeta::setTitle($blogContent->title);
            SEOMeta::setDescription($blogContent->description);
            SEOMeta::addKeyword($blogContent->meta_kwds);

            OpenGraph::setDescription($blogContent->description);
            OpenGraph::setTitle($blogContent->title);
            Twitter::setTitle($blogContent->title);
            JsonLd::setTitle($blogContent->title);
            JsonLd::setDescription($blogContent->description);
        }

        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'collection');
        OpenGraph::addProperty('locale', 'en-US');
        Twitter::setSite('@InnovationsUSA');
        JsonLd::setType('collection');

        $beforeText = "";
        $middleText = "";
        $afterText = "";
        $body = $blogContent->body;
        $length = strlen($body);
        $firstStringCharacter = substr("hello", 0, 1);
        $status = $blogContent->status;

        if(!$status) {
            if (\Auth::check()) {
                $user = \Auth::user();
                if($user->role->name != "admin" &&  $user->role->name != "Staff") {
                    return redirect()->back();    
                }
            } else {
                return redirect()->back();
            }
        }      

        // get the position of footer image

        if ($body <> NULL && $length > 300) {
            $cursor = $length / 3;
            if (substr($body, $cursor, 1) != " ") {
                for ($i = 0; $i < (2 * $length) / 3; $i++) {
                    if (substr($body, $cursor + $i, 1) == " ") {
                        $cursor += $i;
                        break;
                    }
                }
            }
            $beforeText = substr($body, 0, $cursor);
            $content = substr($body, $cursor, $length);
            $length = strlen($content);
            if ($content <> NULL && $length > 300) {
                $cursor = $length / 2;
                if (substr($content, $cursor, 1) != " ") {
                    for ($i = 0; $i < $length / 2; $i++) {
                        if (substr($content, $cursor + $i, 1) == " ") {
                            $cursor += $i;
                            break;
                        }
                    }
                }
                $middleText = substr($content, 0, $cursor);
                $afterText = substr($content, $cursor, $length);
            } else {
                $middleText = $content;
            }

        } else {
            $beforeText = $body;
        }
        //dd($blogContent->body, $beforeText, $middleText, $afterText);

        //get the date
        $date = date_format($blogContent->updated_at, "M Y");

        return view('content-types.blog', [
            'category' => $category,
            'blogContent' => $blogContent,
            'beforeText' => $beforeText,
            'middleText' => $middleText,
            'afterText' => $afterText,
            'date' => $date,
            'status' => $status
        ]);

    }
}
