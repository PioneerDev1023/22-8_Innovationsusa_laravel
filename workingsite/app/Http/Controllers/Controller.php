<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\FrontCarousel;
use App\WebDistribution;
use App\Client;
use App\SeenIn;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;
use Jenssegers\Agent\Agent;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function getWelcome()
    {
        $slides = FrontCarousel::all()->sortBy('slide_number');
        $seoKeywords = array('wallcoverings', 'wallpaper', 'interior design', 'luxury', 'vinyl', 'cork', 'natural woven', 'textile');
        $is_mobile = false;
        $Agent = new Agent();
        if ($Agent->isMobile()) {
            $is_mobile = true;
        }

        SEOMeta::setTitle('Modern Luxury Wallcoverings');
        SEOMeta::setDescription("Innovations in Wallcovering delivers ecologically friendly, modern luxury to interior designers and decorators.");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("Innovations in Wallcovering delivers ecologically friendly, modern luxury.");
        OpenGraph::setTitle('Modern Luxury Wallcoverings');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Modern Luxury Wallcoverings');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Modern Luxury Wallcoverings');
        JsonLd::setDescription("Innovations in Wallcovering delivers ecologically friendly, modern luxury.");
        JsonLd::setType('Flipbook');

        return view('welcome')->with(['slides' => $slides, "isMobile" => $is_mobile]);
    }

    public function getTrending()
    {
        echo 'Trending';
    }

    public function getWhatsNew()
    {
        echo 'Whats New';
    }

    public function getNewcollection()
    {
        $meta_desc = 'Our latest wallcovering selection';
        $meta_keywords = 'Innovations in Wallcovering, wallcovering, wallpaper, luxury, vinyl, cork, natural woven, NYC, design, interior design';
        $meta_title = 'The latest wallcovering collection';

        SEOMeta::setTitle($meta_title);
        SEOMeta::setDescription($meta_desc);
        SEOMeta::addKeyword($meta_keywords);

        OpenGraph::setDescription($meta_desc);
        OpenGraph::setTitle($meta_title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'collection');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($meta_title);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($meta_title);
        JsonLd::setDescription($meta_desc);
        JsonLd::setType('Collection');
        return view('newcollection');
    }

    public function getLetUsShopForYou()
    {
        $meta_desc = 'Looking for the perfect wallcovering for your interior design job?  Let us help you find the perfect product for your project.';
        $meta_keywords = 'Innovations in Wallcovering, wallpaper, wallcovering, design, interior design, consulting';
        $meta_title = 'Let us Shop for Your Perfect Wallcovering';

        SEOMeta::setTitle($meta_title);
        SEOMeta::setDescription($meta_desc);
        SEOMeta::addKeyword($meta_keywords);

        OpenGraph::setDescription($meta_desc);
        OpenGraph::setTitle($meta_title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'text.form');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($meta_title);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($meta_title);
        JsonLd::setDescription($meta_desc);
        JsonLd::setType('form');
        return view('letusshopforyou', ['data' => 'Let Us Shop For You']);
    }

    public function getCustomerService()
    {
        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'customer service', 'flipbook');

        SEOMeta::setTitle('Customer Service');
        SEOMeta::setDescription("Customer service contact information for Innovations in Wallcovering");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("Customer service contact information for Innovations in Wallcovering");
        OpenGraph::setTitle('Innovations in Wallcoverings Customer Service');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'page');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Customer Service');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Innovations in Wallcoverings Customer Service');
        JsonLd::setDescription("Customer service contact information for Innovations in Wallcovering");
        JsonLd::setType('page');

        return view('customer-service', ['data' => 'Customer Service']);
    }

    public function messageCustomerService(Request $request) {

      $data = $request->all();
        $html  = '';

        $email = $data['email'];

        switch($data['topic']) {
          case 'order':
            $email_2 = 'customerservice@innovationsusa.com';
            break;
          case 'sales':
            $email_2 = 'sales@innovationsusa.com';
            break;
          case 'marketing':
            $email_2 = 'marketing@innovationsusa.com';
            break;
          case 'general':
            $email_2 = 'info@innovationsusa.com';
            break;
        }

        Mail::send('email.customer-service', ['data'=>$data], function ($message) use ($email, $email_2, $html) {
          $message->to( [$email, $email_2], 'Recipients' )
            ->subject('Innovations Customer Service Request')
            ->from('noreply@innovationsusa.com','noreply@innovationsusa.com')
            ->setBody($html, 'text/html'); //html body
          });


      return back()->withInput()->withSuccess('Thank you for your inquiry.  We will get back to you shortly.');

    }

    public function getShowrooms()
    {
        return view('services', ['data' => 'Showrooms']);
    }

    public function getFindARep()
    {
        return view('services', ['data' => 'Find A Rep']);
    }

    public function getOurStory()
    {

        $seenin = new Seenin();

        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->role->name == "admin" || $user->role->name == "Staff") {
              $seenins = $seenin->where('include_mentions', 1)
              ->orWhere('include_mentions', 0)
              ->orderBy('pub_date', 'desc')->get();
            }
        } else {

        $seenins = $seenin->where('include_mentions', 1)->orderBy('pub_date', 'desc')->get();

        }

        $seoKeywords = array('wallcoverings', 'wallpaper', 'history', 'interior design', 'luxury', 'Our Story');

        SEOMeta::setTitle('About Us - Innovations in Wallcoverings');
        SEOMeta::setDescription("The history of our New York City wallcovering design firm");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("The history of our New York City wallcovering design firm");
        OpenGraph::setTitle('About Us - Innovations in Wallcoverings History');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'page');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('About Us - Innovations in Wallcoverings');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('About Us - Innovations in Wallcoverings');
        JsonLd::setDescription("The history of our New York City wallcovering design firm");
        JsonLd::setType('page');

        return view('ourstory', ['data' => 'Our Story', 'seenins' => $seenins]);
    }

    public function getMentions()
    {

        $meta_desc = 'Recent press mentions about Innovations in Wallcoverings designs';
        $meta_keywords = 'Innovations USA, wallcovering, press coverage';
        $meta_title = 'Innovations in Wallcoverings Press Mentions';

        SEOMeta::setTitle($meta_title);
        SEOMeta::setDescription($meta_desc);
        SEOMeta::addKeyword($meta_keywords);

        OpenGraph::setDescription($meta_desc);
        OpenGraph::setTitle($meta_title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'mentions');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($meta_title);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($meta_title);
        JsonLd::setDescription($meta_desc);
        JsonLd::setType('mentions');

        $seenin = new Seenin();

        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->role->name == "admin" || $user->role->name == "Staff") {
                $seenins = $seenin->orderBy('pub_date', 'desc')->get();

                return view('content-types.mentions-list', ['data' => 'Our Story', 'seenins' => $seenins]);
            }
        }

        $seenins = $seenin->where('include_mentions', 1)->orderBy('pub_date', 'desc')->get();

        return view('content-types.mentions-list', ['data' => 'Our Story', 'seenins' => $seenins]);
    }

    public function getPresentation()
    {
      SEOMeta::setTitle('Presentation Request');
        return view('presentation-request', ['data' => 'Request Presentation of Spring 2022 Collection']);
    }

    public function getPrivacyPolicy()
    {
        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'customer service', 'flipbook');

        SEOMeta::setTitle('Privacy Policy');
        SEOMeta::setDescription("privacy policy innovationsusa wallcovering");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("privacy policy innovationsusa wallcovering");
        OpenGraph::setTitle('Privacy Policy');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Privacy Policy');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Privacy Policy');
        JsonLd::setDescription("privacy policy innovationsusa wallcovering");
        JsonLd::setType('Flipbook');

        return view('privacy', ['data' => 'Privacy Policy']);
    }

    public function getTermsConditions()
    {
        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'catalog', 'flipbook');

        SEOMeta::setTitle('Terms-and-Conditions');
        SEOMeta::setDescription("terms and conditions innovationsusa wallcovering");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("terms and conditions innovationsusa wallcovering");
        OpenGraph::setTitle('Review Our Terms and Conditions');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Review Our Terms and Conditions');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Review Our Terms and Conditions');
        JsonLd::setDescription("terms and conditions innovationsusa wallcovering");
        JsonLd::setType('Flipbook');

        return view('terms', ['data' => 'Terms & Condition']);
    }

    public function getPresFormSubmit()
    {
        return view('presformsubmit');
    }

    public function updateClients()
    {
        $PDFendPoint = config('constants.value.PDFendPoint');
        $PDFapiKey = config('constants.value.PDFapiKey');

        $wd = new WebDistribution($PDFendPoint, $PDFapiKey);

        $updateArr = $wd->getPdfClients();

        foreach ($updateArr as $update) {
            $client = Client::updateOrCreate(
                ['wd_id' => $update['wd_id']],
                ['company_name' => isset($update['company']) ? $update['company'] : '',
                    'street_address' => isset($update['street_address']) ? $update['street_address'] : '',
                    'city' => isset($update['city']) ? $update['city'] : '',
                    'state' => isset($update['state']) ? $update['state'] : '',
                    'zip_code' => isset($update['zip_code']) ? $update['zip_code'] : '',
                    'country' => isset($update['country']) ? $update['country'] : ''
                ]
            );

        }
    }
    public function socialMediaFeed() {

      $meta_desc = 'Our latest posts from Instagram.';
      $meta_keywords = 'Innovations USA, wallcovering, interior design, social media, Instagram';
      $meta_title = 'Innovations in Wallcoverings Instagram';

      SEOMeta::setTitle($meta_title);
      SEOMeta::setDescription($meta_desc);
      SEOMeta::addKeyword($meta_keywords);

      OpenGraph::setDescription($meta_desc);
      OpenGraph::setTitle($meta_title);
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'social-media');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle($meta_title);
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle($meta_title);
      JsonLd::setDescription($meta_desc);
      JsonLd::setType('social-media');

      return view('instagram');
    }

    public function pinterestFeed() {

      $meta_desc = 'Our latest Pinterest pins.';
      $meta_keywords = 'Innovations in Wallcoverings, wallcovering, social media, Pinterest, interior design';
      $meta_title = 'Pinterest Pins';

      SEOMeta::setTitle($meta_title);
      SEOMeta::setDescription($meta_desc);
      SEOMeta::addKeyword($meta_keywords);

      OpenGraph::setDescription($meta_desc);
      OpenGraph::setTitle($meta_title);
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'social-media');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle($meta_title);
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle($meta_title);
      JsonLd::setDescription($meta_desc);
      JsonLd::setType('social-media');

      return view('pinterest');
    }

    public function faqPage() {
      $meta_desc = 'Frequently Asked Questions about Innovations in Wallcoverings, NYC wallcovering desginer.';
      $meta_keywords = 'Innovations in Wallcoverings, wallcovering, FAQ';
      $meta_title = 'FAQ about Innovations in Wallcoverings';

      SEOMeta::setTitle($meta_title);
      SEOMeta::setDescription($meta_desc);
      SEOMeta::addKeyword($meta_keywords);

      OpenGraph::setDescription($meta_desc);
      OpenGraph::setTitle($meta_title);
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'social-media');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle($meta_title);
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle($meta_title);
      JsonLd::setDescription($meta_desc);
      JsonLd::setType('social-media');

      return view('faq');

    }
}
