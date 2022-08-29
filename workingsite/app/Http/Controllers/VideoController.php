<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

use \DB;
use App\Http\Utils\Namefix;
use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class VideoController extends Controller
{
    protected function listVideos()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->role->name == "admin" || $user->role->name == "Staff") {
                $videos = DB::table('videos')
                    ->select('title', 'thumb_url', 'meta_desc', 'pub_date', 'slug', 'status')
                    ->orderBy('pub_date', 'desc')
                    ->get();

                return $videos;
            }
        }

        $videos = DB::table('videos')
            ->select('title', 'thumb_url', 'meta_desc', 'pub_date', 'slug', 'status')
            ->where('status', 1)
            ->orderBy('pub_date', 'desc')
            ->get();

        return $videos;
    }

    public function getVideos()
    {

        $videos = $this->listVideos();

        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'video', 'flipbook');

        SEOMeta::setTitle('Wallcovering Collection Videos');
        SEOMeta::setDescription("video innovations wallcovering");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("video innovationsusa wallcovering");
        OpenGraph::setTitle('Video');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Video');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Video');
        JsonLd::setDescription("video innovationsusa wallcovering");
        JsonLd::setType('Flipbook');

        return view('content-types.video-list', compact('videos'));
    }

    public function getVideo(Request $request)
    {
        $slug = $request->segment(2);

        $video = Video::where('slug', $slug)->get()->first();

        if ($video == NULL) {
            return back();
        }
        if ($video->status == '0') {
            if (\Auth::check()) {
                $user = \Auth::user();
                if ($user->role->name != "admin" || $user->role->name != "Staff") {
                    return back();
                }
            } else {
                return back();
            }
        }

        $embed = $video->embed_code;
        $status = $video->status;

        if (!empty($video->meta_keywords)) {
            $keywordArray = array();
            $seoKeywords = explode(',', $video->meta_keywords);
            // $seoKeywords = call_user_func_array('array_merge', $keywordArray);
        } else {
            $seoKeywords = ['video', 'wallcovering', 'interior design', 'InnovationsUSA', 'luxury'];
        }

        //  $seoKeywords = !empty($video->meta_keywords) ? call_user_func_array('array_merge', explode(',', $video->meta_keywords)) : array('wallcoverings', 'interior design', 'luxury', 'video', 'flipbook');

        SEOMeta::setTitle($video->title . ' ' . 'Video');
        SEOMeta::setDescription($video->meta_desc);
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription($video->meta_desc);
        OpenGraph::setTitle($video->title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($video->title);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($video->title);
        JsonLd::setDescription($video->meta_desc);
        JsonLd::setType('Flipbook');

        return view('content-types.video', ['embed' => $embed, 'status' => $status]);
    }

    public function getVideoModal(Request $request)
    {
        $slug = $request->segment(2);

        $video = Video::where('slug', $slug)->first();

        if ($video == NULL) {
            $embed = "";
        } else {
            $embed = $video->embed_code;
        }
        $message = "";

        if ($video->status == '0') {
            $message = "<div class=\"alert alert-danger\">Attention! This catalog is not published</div>";
        }

        SEOMeta::setTitle('Video Flipbooks');
        SEOMeta::setDescription('Beautiful flipbook videos of luxury wallcoverings');
        SEOMeta::addKeyword('InnovationsUSA', 'Wallcoverings', 'interior design', 'flipbook', 'video', 'vinyl', 'textile', 'natural', 'woven', 'NYC designer');

        OpenGraph::setDescription('Beautiful flipbook videos of luxury wallcoverings');
        OpenGraph::setTitle('Video Flipbooks');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Video Flipbooks');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Luxury Wallcovering Video Flipbooks');
        JsonLd::setDescription($video->meta_desc);
        JsonLd::setType('Flipbook');

        $embed = $video->embed_code;

        return view('content-types.video-modal', ['embed' => $embed, 'message' => $message]);
    }
}
