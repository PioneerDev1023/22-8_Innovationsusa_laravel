<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Utils\FormHelper;
use App\Http\Utils\Namefix;
use App\DatabaseStorageModel;
use Illuminate\Support\Facades\Mail;
// use App\WebDistribution;
//use Symfony\Component\Console\Input\Input;

use App\Cart;
use App\WebDistribution;

class CartController extends Controller
{

  private function sortArr( $arr ) {

    usort($arr, function($a, $b){
      return strtotime($b["date"]) - strtotime($a["date"]);
    });
    return $arr;
  }

  public function itemAdd(Request $request){

    //!! FROM API

    // $_POST = json_decode(file_get_contents("php://input"),true);
    // $itemnum = $_POST['itemnum'];
    // $itemname = $_POST['itemname'];

    // $obj = json_decode( file_get_contents("php://input") );
    $obj = json_decode( $request->getContent() );
    $itemnum = $obj->itemnum;
    $itemPdfId = $obj->itempdfid;
    $itemUnit = $obj->itemunit;
    $itemname = $obj->itemname;
    $itemcolor = $obj->itemcolor;
    $price = $obj->price;
    $qty = $obj->qty;
    $cartType = $obj->type;
    //$session_id = $obj->sessionid;

    $id = $itemnum . '_' . $cartType;

    // from web route; post
    $session_id = \Session::getId();

    $loggedInName = session()->get('addressObj.company_name') ? '' : $session_id;

    \Cart::session($session_id);

    $user = \Auth::user();
    $is_guest = $user->role->name == "guest" || $user->role->name == "Guest";

    if($cartType == "sample" && $is_guest) {
      $sampleOrders=0;
      $cartObj = \Cart::session($session_id)->getContent();
      if(isset($cartObj) && $cartObj != NULL) {
        foreach($cartObj as $item){
          if($item->attributes->cartType=='sample'){
            $sampleOrders++;
          }
        }
      }
      if($sampleOrders > 2) {
        return \Response::json(array('error' => 'sampleOrder'));
      }
    }

    \Cart::add(array(
      'id' => $id, // inique row ID
      'name' => $itemname,
      'price' => $price,
      'quantity' => $qty,
      'attributes' => array(
        'itemnum' => $itemnum,
        'itemPdfId' => $itemPdfId,
        'itemUnit' => $itemUnit,
        'color' => $itemcolor,
        'cartType' => $cartType,
        'date' => date("Y-m-d H:i:s"),
      )
    ));

    $cartTotalQuantity = \Cart::session($session_id)->getTotalQuantity();

    return $cartTotalQuantity;
  }

  public function delete(Request $request){

    $obj = json_decode( $request->getContent() );
    $itemnum = $obj->itemnum;
    $type = $obj->type;
    //$itemname = $obj->itemname;
    //$session_id = $obj->sessionid;

    $id = $itemnum . '_' . $type;

    $session_id = \Session::getId();

    // delete an item on cart
    \Cart::session($session_id)->remove($id);

    $cartTotalQuantity = \Cart::session($session_id)->getTotalQuantity();

    return $cartTotalQuantity;
  }

  public function update(Request $request){

    $obj = json_decode( $request->getContent() );
    $itemnum = $obj->itemnum;
    $qty = $obj->qty;
    $type = $obj->type;
    //$session_id = $obj->sessionid;

    $id = $itemnum . '_' . $type;

    $session_id = \Session::getId();

    \Cart::session($session_id)->update($id, array(
      'quantity' => array(
          'relative' => false,
          'value' => $qty
      ),
    ));

     /* \Cart::session($session_id)->update($itemnum,[
        'quantity' => $qty,
      ]); */


    $cartTotalQuantity = \Cart::session($session_id)->getTotalQuantity();

    return $cartTotalQuantity;
  }

  public function cartPage($id){
    if($id=='sample'||$id=='shopping'){

      $nameFix = new Namefix();

      $session_id = \Session::getId();

      $cartObj = \Cart::session($session_id)->getContent();

      $arr = array();

      foreach($cartObj as $item){

         //print_r($item->quantity); die();

        if( $item->attributes->cartType==$id && $item->quantity > 0 ){
          $arr[] = array(
            'id' => $item->attributes->itemnum,
            'name' => $item->name,
            'urlname' => $nameFix->urlName($item->name),
            'quantity' => $item->quantity,
            'price' => $item->price,
            'color' => $item->attributes->color,
            'date' => $item->attributes->date
          );
        }
      }

      $sortedArr = $this->sortArr($arr);
      // latest by time on top
     /* usort($arr, function($a, $b){
        return strtotime($b["date"]) - strtotime($a["date"]);
      });*/

      //print_r($sortedArr); die();
      $company_name = NULL;
      $street = NULL;
      $city = NULL;
      $state = NULL;
      $zip_code = NULL;
      $is_client = False;
      if(\Auth::check()) {
        $user = \Auth::user();
        if($user->role->name == "Client" ||  $user->role->name == "client")
        {
          $is_client = true;
          $company_name = $user->client->company_name;
          $street = $user->client->street_address;
          $city = $user->client->city;
          $state = $user->client->state;
          $zip_code = $user->client->zip_code;
        }
      }

      return view('cart', [
        'carttype' => $id,
        'cartObj' => $sortedArr,
        'is_client' => $is_client,
        'data' => array(
          'company_name' => $company_name,
          'street' => $street,
          'city' => $city,
          'state' => $state,
          'zip_code' => $zip_code,
        )
      ]);
    } else {
      return abort(404);
    }
  }

  public function checkout(Request $request, $id){

    $data = array();
    $is_client = False;
    $is_guest = False;
    $user = NULL;
    if(\Auth::check()) {
      $user = \Auth::user();
      if($user->role->name == "Client" || $user->role->name == "client") {
        $is_client = True;
        $data['company_name'] = $request->get('company_name');
        $data['street'] = $request->get('street');
        $data['city'] = $request->get('city');
        $data['state'] = $request->get('state');
        $data['zip_code'] = $request->get('zip_code');
      }
      else if($user->role->name == "Guest" || $user->role->name == "guest") {
        $is_guest = true;
        // from web route; post
        $session_id = \Session::getId();
        $cartTotalQuantity = \Cart::session($session_id)->getTotalQuantity();
        if($cartTotalQuantity > 3) {
          return redirect()->back()->with('orderLimitError', 'Total sample quantity is limited to 3 for guest accounts.');
        }
        //get guest's company name
        $userEmail = $user->email;
        $userCompany = null;
        $row = DatabaseStorageModel::where('id', '=', $userEmail)->first();
        if($row != null) {
          $userCompany = $row->cart_data['company'];
        }
        $data['company_name'] = $userCompany;
        //dd($userCompany);
      }
      else {

      }
    }

    $session_id = \Session::getId();
    $cartObj = \Cart::session($session_id)->getContent();

    $arr = array();

    foreach($cartObj as $item){
      if($item->attributes->cartType==$id){
        $arr[] = array(
          'id' => $item->attributes->itemnum,
          'name' => $item->name,
          'price' => $item->price,
          'quantity' => $item->quantity,
          'color' => $item->attributes->color,
          'date' => $item->attributes->date,
        );
      }
    }

    $sortedArr = $this->sortArr($arr);

    $formHelp = new FormHelper();
    $countryArr = $formHelp->countryArr();
    $statesArr = $formHelp->statesArr();

    return view('cart-checkout', [
      'is_client' => $is_client,
      'carttype' => $id,
      'cartObj' => $sortedArr,
      'countryArr' => $countryArr,
      'statesArr' => $statesArr,
      'loginUser' => $user,
      'data' => $data,
      'is_guest' => $is_guest
    ]);
  }

  public function ship(Request $request, $id) {

    $formObj = $request->all();

    $cartType = $formObj['carttype'];

    $formArr = array( 'form' => [
      'email' => $formObj['email'],
      'country' => $formObj['country'],
      'firstname' => $formObj['firstname'],
      'lastname' => $formObj['lastname'],
      'fullname' => $formObj['firstname']." ".$formObj['lastname'],
      'address' => $formObj['address'],
      'address2' => $formObj['address2'],
      'company' => $formObj['company'],
      'city' => $formObj['city'],
      'state' => $formObj['state'],
      'zip' => $formObj['zip'],
      'phone' => $formObj['phone'],
      'profession' => $formObj['profession']
    ]);


    $session_id = \Session::getId();
    $cartObj = \Cart::session($session_id)->getContent();

    $arr = array();
    foreach($cartObj as $item){
      if($item->attributes->cartType==$id){
        $arr[] = array(
          'id' => $item->attributes->itemnum,
          'name' => $item->name,
          'price' => $item->price,
          'quantity' => $item->quantity,
          'color' => $item->attributes->color,
          'date' => $item->attributes->date,
        );
      }
    }

    $sortedArr = $this->sortArr($arr);

    $freightOpts = NULL;
    $TrancId = NULL;

    // create the transaction for purchase items and add the items into it.

    $PDFendPoint = config('constants.value.PDFendPoint');
    $PDFapiKey = config('constants.value.PDFapiKey');
    $wd = new WebDistribution($PDFendPoint, $PDFapiKey);

    $user = Auth::user();

    if(\Auth::check() && $user->role->name == "Client") {

      $user_name = $user->name;
      $clientID = $user->client->wd_id;
      $customer = $wd->pdfCustomerID($clientID);
      $customer_id = $customer[0];
      $rep_id = $request->session()->get('rep_id');
      $rep_email = $request->session()->get('rep_email');
      $street = $user->client->street_address;
      $city = $user->client->city;
      $state_id = $user->client->state;
      $zipcode = $user->client->zip_code;
      $country_id = '228';
      $customer_email = $user->email;
      // override empty $formArr values since clients haven't filled out the form

      $formArr['form']['address'] = $street;
      $formArr['form']['city'] = $city;
      $formArr['form']['state'] = $state_id;
      $formArr['form']['zip'] = $zipcode;

      // processing the state NY=>id

      $statesJson = $wd->curlPDFapi($PDFendPoint.'state?country=228');
      $statesArray = json_decode($statesJson);
      //dd($statesArray);
      if ($state_id != "" && $state_id != NULL) {

        foreach($statesArray as $state) {
          if ($state->code === $state_id) {
            $state_id = $state->id;
          }
        }
      }
      else {
        $state_id = '3873'; // default: New York
      }


      $TrancData = $wd->createTransaction($user_name, $customer_id, $rep_id, $rep_email, $street, $city, $state_id, $country_id, $customer_email);
      //dd($request->session()->get('rep_id'));
      $TrancData = json_decode($TrancData);
      if(!is_object($TrancData)) {
        return redirect()->back();
      }
      $TrancId = $TrancData->id;

      //dd($TrancId);

      // add the cart-items

      $payloadData = array();

      //dd($cartObj);

      foreach($cartObj as $item){
        if($item->attributes->cartType==$id){
          $id_pdf = $item->attributes->itemPdfId;

          $unit = 1191;

          $unitName = $item->attributes->itemUnit;
          if ($unitName == "Yard") {
            $unit = 1191;
          }
          else if($unitName == "Roll") {
            $unit = 1187;
          }
          else {
            $response = $wd->curlPDFapi($PDFendPoint.'item/'.$id_pdf);
            $response = json_decode($response);
            $unit = $response->style->selling_unit_id;
          }

          $payloadData = array(
            "transaction"=> $TrancId,
            'item' => $item->attributes->itemPdfId,
            'price' => $item->price,
            'quantity' => $item->quantity,
            "unit"=> $unit,
            "auto_allocate"=> true,
          );

          $wd->addTrancItem($TrancId, $payloadData);
        }
      }

      $response = $wd->curlPDFapi($PDFendPoint.'transaction/'.$TrancId.'/freight-options');
      $response = json_decode($response);
      $freightOpts = $response->rates;
    }

    $formHelp = new FormHelper();
    $countryArr = $formHelp->countryArr();
    $statesArr = $formHelp->statesArr();

    return view('cart-ship', [
      'TrancId' => $TrancId,
      'carttype' => $id,
      'cartObj' => $sortedArr,
      'countryArr' => $countryArr,
      'statesArr' => $statesArr,
      'formArr' => $formArr,
      "freightOpts" => $freightOpts,
    ]);
  }

  public function review(Request $request, $id) {

    if($request->method()=='POST'){}
    else{
      return redirect()->route('cart-home', ['id' => 'shopping']);
      die();
    }

    $formObj = $request->all();

    $cartType = $formObj['carttype'];
    $TrancId = $formObj['TrancId'];
    $shipOption = $formObj['ship_option'];

    $formArr = array( 'form' => [
      'firstname' => $formObj['firstname'],
      'lastname' => $formObj['lastname'],
      'fullname' => $formObj['fullname'],
      'address' => $formObj['address'],
      'city' => $formObj['city'],
      'state' => $formObj['state'],
      'zip' => $formObj['zip'],
      'phone' => $formObj['phone'],
      'ship_option' => $formObj['ship_option'],
    ]);
    /*$formArr = array( 'form' => [
      'country' => $formObj['country'],
      'firstname' => $formObj['firstname'],
      'lastname' => $formObj['lastname'],
      'address' => $formObj['address'],
      'address2' => $formObj['address2'],
      'company' => $formObj['company'],
      'city' => $formObj['city'],
      'state' => $formObj['state'],
      'zip' => $formObj['zip'],
      'phone' => $formObj['phone'],
      'profession' => $formObj['profession']
    ]);*/


    $session_id = \Session::getId();
    $cartObj = \Cart::session($session_id)->getContent();

    $arr = array();
    foreach($cartObj as $item){
      if($item->attributes->cartType==$id){
        $arr[] = array(
          'id' => $item->attributes->itemnum,
          'name' => $item->name,
          'price' => $item->price,
          'quantity' => $item->quantity,
          'color' => $item->attributes->color,
          'date' => $item->attributes->date,
        );
      }
    }

    $sortedArr = $this->sortArr($arr);

    $formHelp = new FormHelper();
    $countryArr = $formHelp->countryArr();
    $statesArr = $formHelp->statesArr();

    $PDFendPoint = config('constants.value.PDFendPoint');
    $PDFapiKey = config('constants.value.PDFapiKey');
    $wd = new WebDistribution($PDFendPoint, $PDFapiKey);

    $user = Auth::user();

    if(\Auth::check() && $user->role->name == "Client") {
      $response = $wd->curlPDFapi($PDFendPoint.'transaction/'.$TrancId.'/weight');
      $response = json_decode($response);
      $payload = array(
        "weight"=> $response->weight,
        "packages"=> $response->packages,
        "packing_charge"=> $response->packing_charge,
        "shipping_service_id"=> $shipOption,
        "freight_amount"=> "52"
      );

      $wd->finalizeTrac($PDFendPoint."transaction/".$TrancId, $payload);
    }

    foreach($cartObj as $item){
      if($item->attributes->cartType==$id){
        \Cart::session($session_id)->remove($item->id);
      }
    }

    return view('cart-review', [
      'TrancId' => $TrancId,
      'carttype' => $id,
      'cartObj' => $sortedArr,
      'countryArr' => $countryArr,
      'statesArr' => $statesArr,
      'formArr' => $formArr
    ]);
  }

  public function sampleConfirmation(Request $request){

    $formObj = $request->all();

    $cartType = $formObj['carttype'];

    $formArr = array( 'form' => [
        'email' => $formObj['email'],
        'country' => $formObj['country'],
        'firstname' => $formObj['firstname'],
        'lastname' => $formObj['lastname'],
        'address' => $formObj['address'],
        'address2' => $formObj['address2'],
        'company' => $formObj['company'],
        'city' => $formObj['city'],
        'state' => $formObj['state'],
        'zip' => $formObj['zip'],
        'phone' => $formObj['phone'],
        'profession' => $formObj['profession']
      ]);

    //print_r($formArr); die();

    $session_id = \Session::getId();
    $cartObj = \Cart::session($session_id)->getContent();

    $arr = array();
    foreach($cartObj as $item){
      if($item->attributes->cartType==$cartType){
        $arr[] = array(
          'id' => $item->attributes->itemnum,
          'id_pdf' => $item->attributes->itemPdfId,
          'name' => $item->name,
          'quantity' => $item->quantity,
          'color' => $item->attributes->color,
          'date' => $item->attributes->date
        );
      }
    }
    $cartArr = array('samples' => $this->sortArr($arr) );

    $fullArr = array_merge($formArr, $cartArr);

    $PDFendPoint = config('constants.value.PDFendPoint');
    $PDFapiKey = config('constants.value.PDFapiKey');
    $wd = new WebDistribution($PDFendPoint, $PDFapiKey);

    $user = Auth::user();

    if(\Auth::check() && $user->role->name == "Client") {
      // sample type code id: 31

      $user_name = $user->name;
      $clientID = $user->client->wd_id;
      $customer = $wd->pdfCustomerID($clientID);
      $customer_id = $customer[0];
      $rep_id = $request->session()->get('rep_id');
      $rep_email = $request->session()->get('rep_email');
      $attn = $customer[1][0]['addresses'][0]['attention'];
      $company_name = $customer[1][0]['addresses'][0]['company_name'];
      $street = $formObj['address'];
      $city = $formObj['city'];
      $state_id = $formObj['state'];
      $zip_code = $customer[1][0]['addresses'][0]['postal_code'];
      $country_id = '228';
      $customer_email = $user->email;

      // processing the state NY=>id

      $statesJson = $wd->curlPDFapi($PDFendPoint.'state?country=228');
      $statesArray = json_decode($statesJson);
      //dd($statesArray);
      if ($state_id != "" && $state_id != NULL) {

        foreach($statesArray as $state) {
          if ($state->code === $state_id) {
            $state_id = $state->id;
          }
        }
      }
      else {
        $state_id = '3873'; // default: New York
      }

      $sampleTrac = $wd->createSampleTransaction($user_name, $customer_id, $rep_id, $rep_email, $attn, $company_name, $street, $city, $state_id, $zip_code, $country_id, $customer_email);

      $sampleTrac = json_decode($sampleTrac);
      if(!is_object($sampleTrac)) {
        return redirect()->back();
      }
      $sampleTracId = $sampleTrac->id;
      $wd->addSampleItem($sampleTracId, $this->sortArr($arr));

      $result = $wd->finalizeTrac($PDFendPoint.'sample-transaction/'.$sampleTracId);
      //dd($result);
    }
    else {

        //print_r($fullArr['samples']); die();

      /* Array(
              [form] => Array
                  (
                      [email] => sung@2020project.com
                      [country] => USA
                      [fullname] => John Do
                      [address] => 123 Main st
                      [address2] => #2
                      [company] => Company Name
                      [city] => Falls Church
                      [state] => VA
                      [zip] => 22043
                      [phone] => 84663686237
                      [profession] => enduser
                  )

              [samples] => Array
                  (
                      [0] => Array
                          (
                              [id] => ELP-010
                              [name] => El Paso
                              [quantity] => 1
                              [date] => 2020-07-28 18:37:10
                          )

                      [1] => Array
                          (
                              [id] => ELP-003
                              [name] => El Paso
                              [quantity] => 2
                              [date] => 2020-07-28 18:37:07
                          )

                  )
                  'email' => $formObj['email'],
        'country' => $formObj['country'],
        'firstname' => $formObj['firstname'],
        'lastname' => $formObj['lastname'],
        'address' => $formObj['address'],
        'address2' => $formObj['address2'],
        'company' => $formObj['company'],
        'city' => $formObj['city'],
        'state' => $formObj['state'],
        'zip' => $formObj['zip'],
        'phone' => $formObj['phone'],
        'profession' => $formObj['profession']

          ) */

      //print_r($fullArr['form']['email']); die();
      //print_r($fullArr['samples'][0]['id']); die();
      //$firstname = $formObj['firstname'];
      //$lastname = $formObj['lastname'];

      //print_r( $formObj['email'] ); die();

      $html  = '';
      $email = $formObj['email'];
      $email_2 = \Config::get('constants.value.email_2');

      Mail::send('email.sample-confirmation', ['fullArr'=>$fullArr], function ($message) use ($email, $email_2, $html) {
      $message->to( [$email, $email_2], 'Recipients' )
          ->subject('Innovationsusa Sample Request')
          ->from('noreply@innovationsusa.com','noreply@innovationsusa.com')
          ->setBody($html, 'text/html'); //html body
          //or
          //->setBody('Hi, welcome Sir!'); //for text body
          //echo "HTML Email Sent. Check your inbox.";
      });

      // !! flush session id to new
      // this will remove all shopping items as well
      //$request->session()->regenerate();

    }


    // remove cart items
    foreach($cartObj as $item){
      if($item->attributes->cartType==$cartType){
        \Cart::session($session_id)->remove($item->id);
      }
    }


    return view('cart-confirmation', [
      //'response' => 'Thank you.'
    ]);
  }


  public function shoppingConfirmation(){
    //print_r($formArr); die();

    $session_id = \Session::getId();
    $cartObj = \Cart::session($session_id)->getContent();
    $arr = array();
    foreach($cartObj as $item){
      if($item->attributes->cartType=='shopping'){
        $arr[] = array(
          'id' => $item->id,
          'name' => $item->name,
          'quantity' => $item->quantity,
          'color' => $item->attributes->color,
          'date' => $item->attributes->date
        );
      }
    }


    // !! flush session id to new
    // this will remove all shopping items as well
    //$request->session()->regenerate();

    // remove cart items
    foreach($cartObj as $item){
      if($item->attributes->cartType=='shopping'){
        \Cart::session($session_id)->remove($item->id);
      }
    }


    return view('cart-confirmation', [
      //'response' => 'Thank you.'
    ]);
  }

  public function shoppingPage(){

    echo 'shopping cart';
  }

  public function payLink($id){

    $PDFendPoint = config('constants.value.PDFendPoint');
    $PDFapiKey = config('constants.value.PDFapiKey');
    $wd = new WebDistribution($PDFendPoint, $PDFapiKey);


    $pdfURL = $PDFendPoint."transaction/".$id."?force=false";
    $data4 = $wd->curlPDFapi($pdfURL);
    $data = json_decode($data4, true);

    echo('<script> location.href="https://distribution.pdfsystems.com/client/payment?transaction='.$id.'&key='.$data['client_auth_key'].'";</script>');

  }

}
