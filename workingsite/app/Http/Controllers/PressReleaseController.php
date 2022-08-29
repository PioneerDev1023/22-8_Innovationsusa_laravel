<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collection;
use \DB;
use App\PressRelease;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class PressReleaseController extends Controller
{

    public function showReleases()
    {
        $Releases = PressRelease::where('status', 'PUBLISHED')->orderBy('updated_at', 'desc')->get(); // $this->getCollections();

        if (\Auth::check()) {
            $user = \Auth::user();
            if($user->role->name == "admin" ||  $user->role->name == "Staff") {
                $Releases = PressRelease::orderBy('updated_at', 'desc')->get(); // $this->getCollections();
            }
        }

        SEOMeta::setTitle('Wallcovering Press Release');
        SEOMeta::setDescription('List of wallcovering press release');
        SEOMeta::addKeyword('wallcovering, design, Innovations USA, press release, New York, NYC');

        OpenGraph::setDescription('List of wallcovering Release');
        OpenGraph::setTitle('Wallcovering Collections');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'press release');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Wallcovering Press Release');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Wallcovering Press Release');
        JsonLd::setDescription('List of wallcovering press release');
        JsonLd::setType('press release');
        return view('content-types.press-release-list', [
            'list' => $Releases
        ]);
    }

    public function showRelease(Request $request)
    {
        $slug = $request->segment(2);

        $release = PressRelease::where('slug', $slug)->first();

        if (!empty($release)) {
            SEOMeta::setTitle($release->title);
            SEOMeta::setDescription($release->description);
            SEOMeta::addKeyword($release->meta_kwds);

            OpenGraph::setDescription($release->description);
            OpenGraph::setTitle($release->title);
            Twitter::setTitle($release->title);
            JsonLd::setTitle($release->title);
            JsonLd::setDescription($release->description);
        }


        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'collection');
        OpenGraph::addProperty('locale', 'en-US');
        Twitter::setSite('@InnovationsUSA');
        JsonLd::setType('collection');

        $status = $release->status;
        if($status != "PUBLISHED") {
            if (\Auth::check()) {
                $user = \Auth::user();
                if($user->role->name != "admin" &&  $user->role->name != "Staff") {
                    return redirect()->back();
                }
            } else {
                return redirect()->back();
            }
        }

        $beforeText = "";
        $afterText = "";
        $body = $release->body;
        $length = strlen($body);
        $firstStringCharacter = substr("hello", 0, 1);

        // get the position of footer image

        $bodyArr = explode("<p>", $body);

        $parCount = count($bodyArr);

        $oneThird = $parCount / 3;

        $i=0;

        if($body <> NULL && $length > 300) {
          for($i = 0; $i < $oneThird; $i++) {
            $beforeText .= $bodyArr[$i];
          }
          for($i = $oneThird + 1; $i < $parCount; $i++) {
            $afterText .= $bodyArr[$i];
          }
        } else {
            $beforeText = $body;
        }

        //get the date
        $date = date_format($release->updated_at, "M Y");

        //dd($beforeText, $afterText, $body);

        return view('content-types.press-release', [
            'release' => $release,
            'beforeText' => $beforeText,
            'afterText' => $afterText,
            'date' => $date,
            'status' => $status
        ]);
    }


}
