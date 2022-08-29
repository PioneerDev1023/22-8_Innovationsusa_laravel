<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductList;
use App\Catalogs;
use App\Video;
use App\Collection;

class SitemapXmlController extends Controller
{
  public function index() {
    $products = ProductList::all();
    $catalogs = Catalogs::all();
    $videos   = Video::all();
    $collections = Collection::all();

    return response()->view('sitemap', [
      'products' => $products,
      'catalogs' => $catalogs,
      'videos'   => $videos,
      'collections' => $collections
      ])->header('Content-Type', 'text/xml');
    }
}
