<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function newsletterInterstitial() {

      $lifetime = time() + 60 * 60 * 24 * 30;// 30 days
      $cookie = \Cookie::make('interstitial', 'interstitial_1', $lifetime, '/');

      return response()->json('interstitial_1')->withCookie($cookie);
    }

    public function homeIntAd()
    {

      if(\Cookie::get('userInterstitial') === "submit") {
        //$lifetime = 30;// 30 days
        $lifetime = 60 * 24 * 30;// 30 days
        $cookie = \Cookie::make('userInterstitial', 'submit', $lifetime, '/');

        return response()->json('interstitial_1')->withCookie($cookie);
      }

      $embed_code = '';

      $head_img = 'images/ui/hd-expo-modal.png';
      //$lifetime = 30;
      $lifetime = 60 * 24 * 7;
      $cookie = \Cookie::make('Interstitial', 'Interstitial_1', $lifetime, '/');
      return response()->view('newsletter-interstitial-content',
        ['embed_code'=>$embed_code, 'href' => "http://hdexpo22.nvytes.co/hdexpo22/register/154660.html", 'img_src' => $head_img, 'setCookie' => $cookie])->withCookie($cookie);
    }


}
