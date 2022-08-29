<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collection;
use \DB;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class CollectionController extends Controller
{

    public function showCollections()
    {
        $collections = Collection::orderBy('launch_date', 'desc')->where('status', '1')->get(); // $this->getCollections();

        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->role->name == "admin" || $user->role->name == "Staff") {
                $collections = Collection::orderBy('launch_date', 'desc')->get();
            }
        }

        SEOMeta::setTitle('Wallcovering Collections');
        SEOMeta::setDescription('List of wallcovering collections');
        SEOMeta::addKeyword('wallcovering, design, Innovations USA, collections, New York, NYC');

        OpenGraph::setDescription('List of wallcovering collections');
        OpenGraph::setTitle('Wallcovering Collections');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'collection');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Wallcovering Collections');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Wallcovering Collections');
        JsonLd::setDescription('List of wallcovering collections');
        JsonLd::setType('collection');
        return view('content-types.collection-list', [
            'list' => $collections
        ]);
    }

    public function showCollection(Request $request)
    {
        $slug = $request->segment(2);

        $collection = Collection::where('slug', $slug)->first();

        if ($collection == NULL) {
            return back();
        }

        $status = $collection->status;

        if ($collection->status == '0') {
            if (\Auth::check()) {
                $user = \Auth::user();
                if ($user->role->name != "admin" || $user->role->name != "Staff") {
                    return back();
                }
            } else {
                return back();
            }
        }

        $desc = html_entity_decode(strip_tags($collection->description));

        $desc = (strlen($desc) > 13) ? substr($desc,0,140).'...' : $desc;

        if (!empty($collection)) {
            SEOMeta::setTitle($collection->title . ' ' . 'Collection');
            SEOMeta::setDescription($desc);
            SEOMeta::addKeyword($collection->meta_kwds);

            OpenGraph::setDescription(html_entity_decode(strip_tags($collection->description)));
            OpenGraph::setTitle($collection->title);
            Twitter::setTitle($collection->title);
            JsonLd::setTitle($collection->title);
            JsonLd::setDescription($collection->description);
        }


        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'collection');
        OpenGraph::addProperty('locale', 'en-US');
        Twitter::setSite('@InnovationsUSA');
        JsonLd::setType('collection');

        return view('content-types.collection', [
            'collection' => $collection,
            'status' => $status
        ]);
    }


}
