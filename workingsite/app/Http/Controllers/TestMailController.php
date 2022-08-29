<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller {
   /*public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   
      \Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('ggalan@gmail.com', 'Tutorials Point')
                  ->subject('Laravel Basic Testing Mail');
         $message->from('hello@innovationsusa.com','admin');
      });
      echo "Basic Email Sent. Check your inbox.";
   }*/
   public function html_email() {
      $name = '<h1>Sung!!!</h1>';
      $html = '';
      $email = 'sung@2020project.com';
      $email2 = 'tech@demographx.com';

      $arr = array('name'=>$name);
      Mail::send('email.test', $arr, function ($message) use ($email, $email2, $html) {
      $message->to([$email, $email2], 'Title 2')
          ->subject('Laravel Basic Testing Mail')
          ->from('hello@innovationsusa.com','admin')
          ->setBody($html, 'text/html'); //html body
           //or
          //->setBody('Hi, welcome Sir!'); //for text body
      echo "HTML Email Sent. Check your inbox.";
   });

  }
   /*public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('ggalan@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('hello@innovationsusa.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }*/
}