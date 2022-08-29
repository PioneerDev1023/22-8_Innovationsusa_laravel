<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collection;

class RssFeedController extends Controller
{
    public function generateRssFeed(Request $request, $type = "collection") {

        $data = NULL;
        if($type == "collection") {
            $data = Collection::orderBy('id', 'desc')->get();
        }
        else if ($type == "press-release") {
            $data = Collection::orderBy('id', 'desc')->get();
        }

        return response()->view('feed', compact('data'))->header('Content-Type', 'application/xml');
    }
}
