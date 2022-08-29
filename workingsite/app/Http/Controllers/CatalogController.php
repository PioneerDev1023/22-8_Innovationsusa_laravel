<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Catalogs;
use \DB;
use App\Http\Utils\Namefix;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class CatalogController extends Controller
{

    protected function listCatalogs()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->role->name == "admin" || $user->role->name == "Staff") {
                $catalogs = DB::table('catalogs')
                    ->select('title', 'thumb_url', 'meta_desc', 'pub_date', 'slug', 'status')
                    ->orderBy('pub_date', 'desc')
                    ->get();

                return $catalogs;
            }
        }

        $catalogs = DB::table('catalogs')
            ->select('title', 'thumb_url', 'meta_desc', 'pub_date', 'slug', 'status')
            ->where('status', '1')
            ->orderBy('pub_date', 'desc')
            ->get();

        return $catalogs;
    }

    public function getCatalogs()
    {

        $catalogs = $this->listCatalogs();

        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'catalog', 'flipbook');

        SEOMeta::setTitle('Wallcovering Catalogs and Lookbooks');
        SEOMeta::setDescription("Beautiful catalogs and lookbooks of our wallcovering interior design product lines.");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("catalog innovationsusa wallcovering");
        OpenGraph::setTitle('Catalog');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Catalog');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Catalog');
        JsonLd::setDescription("catalog innovationsusa wallcovering");
        JsonLd::setType('Flipbook');

        return view('content-types.catalog-list', compact('catalogs'));
    }

    public function getCatalog(Request $request)
    {
        $slug = $request->segment(2);

        $catalog = Catalogs::where('slug', $slug)->get()->first();
        if ($catalog == NULL) {
            return back();
        }
        if ($catalog->status == '0') {
            if (\Auth::check()) {
                $user = \Auth::user();
                if ($user->role->name != "admin" || $user->role->name != "Staff") {
                    return back();
                }
            } else {
                return back();
            }
        }

        $embed = $catalog->embed_code;
        $status = $catalog->status;

        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'catalog', 'flipbook');

        $title = $catalog->title;
        if($slug == "focal-point-spring-2022") {
            $title = "Focal Point | Spring 2022 catalog";
        }

        SEOMeta::setTitle($catalog->title. ' '. 'Catalog');
        SEOMeta::setDescription($catalog->meta_desc);
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription($catalog->meta_desc);
        OpenGraph::setTitle($catalog->title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($catalog->title);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($catalog->title);
        JsonLd::setDescription($catalog->meta_desc);
        JsonLd::setType('Flipbook');

        return view('content-types.catalog', ['embed' => $embed, 'status' => $status, 'title' => $title]);
    }

    public function getCatalogModal(Request $request)
    {
        $slug = $request->segment(2);

        $catalog = Catalogs::where('slug', $slug)->get()->first();

        if ($catalog == NULL) {
            $embed = "";
        } else {
            $embed = $catalog->embed_code;
        }

        if ($catalog->status == '0') {
            $embed = "<div class=\"alert alert-danger\">Attention! This catalog is not published</div>" . $embed;

        }

        SEOMeta::setTitle('Catalog Flipbooks');
        SEOMeta::setDescription('Beautiful flipbook catalogs of luxury wallcoverings');
        SEOMeta::addKeyword('Innovations', 'Wallcoverings', 'interior design', 'flipbook', 'catalog', 'vinyl', 'textile', 'natural', 'woven', 'NYC designer');

        OpenGraph::setDescription('Beautiful flipbook catalogs of luxury wallcoverings');
        OpenGraph::setTitle('Catalog Flipbooks');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Catalog Flipbooks');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Luxury Wallcovering Catalog Flipbooks');
        JsonLd::setDescription($catalog->meta_desc);
        JsonLd::setType('Flipbook');

        return view('content-types.catalog-modal', ['embed' => $embed]);
    }

}
