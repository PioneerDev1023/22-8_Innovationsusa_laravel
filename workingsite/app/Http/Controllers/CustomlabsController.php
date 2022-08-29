<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\PostView;
use App\Http\Utils\Namefix;

use Jenssegers\Agent\Agent;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class CustomlabsController extends Controller{

  public function getCustomLabs(PostView $postView, Product $product/*, $id*/){
      
     /*   $id='product';

        $nameFix = new Namefix();

        $productTitle = 'ALL PRODUCTS';



        // http://localhost/innovations/_wip/product/

        $columnArr = array('item_name');

        // Method Chaining based on condition
        //$query = \App\Collection::query();
        //$product = new Product();
        $prodObj = $product->index($columnArr);


        $post = $product;
        $post->post_id = 'product';
        $post->titleslug = $id;
        $postView->createViewLog($post);


        $filterObj = array('2020 Summer');

        print_r($prodObj); die();
        

        $productArr = array();
        foreach ($prodObj as $key => $value) {
            $productArr[]= array( 
              'jpgName1' => $nameFix->jpgName($value->item_name),
              'urlName1' => $nameFix->urlName($value->item_name),
              'displayName1' => $nameFix->displayName($value->item_name),
              'dbName' => $nameFix->dbName($value->item_name),
            );
        }

        $agent = new Agent();
        $mobile = $agent->isMobile();
    */
    $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'customlabs', 'flipbook');

    SEOMeta::setTitle('Custom Wallcovering Design Lab');
    SEOMeta::setDescription("customlabs innovationsusa wallcovering");
    SEOMeta::addKeyword($seoKeywords);

    OpenGraph::setDescription("customlabs innovationsusa wallcovering");
    OpenGraph::setTitle('Customlabs');
    OpenGraph::setUrl(url()->current());
    OpenGraph::addProperty('type', 'product');
    OpenGraph::addProperty('locale', 'en-US');

    Twitter::setTitle('Customlabs');
    Twitter::setSite('@InnovationsUSA');

    JsonLd::setTitle('Customlabs');
    JsonLd::setDescription("customlabs innovationsusa wallcovering");
    JsonLd::setType('Flipbook');


    return view('customlabs'/*, [ 
        'pageId' => $id,
        'productTitle'=>$productTitle, 
        'productArr'=>$productArr, 
        'filterObj'=>$filterObj, 
        'mobile'=>$mobile, 
    ]*/);

		/*return view('item', [ 
      'pageType' => 'item',
      'mainImg' => 'Innovations_' . $nameFix->jpgName($id),
			'itemArr'=>$itemArr,
      'itemGallery'=>$itemObj['gallery'],
		]);*/
		
	}

  public function startYourProject(){

    $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'customlabs', 'flipbook');

    SEOMeta::setTitle('Start a Custom Wallcovering Project');
    SEOMeta::setDescription("customlabs innovationsusa wallcovering");
    SEOMeta::addKeyword($seoKeywords);

    OpenGraph::setDescription("customlabs innovationsusa wallcovering");
    OpenGraph::setTitle('Customlabs');
    OpenGraph::setUrl(url()->current());
    OpenGraph::addProperty('type', 'product');
    OpenGraph::addProperty('locale', 'en-US');

    Twitter::setTitle('Customlabs');
    Twitter::setSite('@InnovationsUSA');

    JsonLd::setTitle('Customlabs');
    JsonLd::setDescription("customlabs innovationsusa wallcovering");
    JsonLd::setType('Flipbook');
    
   return view('customlabs-startyourproject');

  }

  /*public function getItemAjax($id){
    $item = new Item();
    $itemObj = $item->getItem($id);
    return $itemObj;
  }
  public function getItemFilterAjax($id){
    $item = new Item();
    $itemObj = $item->getItemFilter($id);
    return $itemObj;
  }*/
}
