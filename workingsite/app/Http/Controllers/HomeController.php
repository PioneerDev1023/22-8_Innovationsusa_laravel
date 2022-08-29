<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\WebDistribution;

use App\Rep;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // from app\Http\Controllers\auth\LoginController.php
        //print_r(222); die();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //print_r(333); die();
        // logic in
        // /app/Http/Middleware/RedirectIfAuthenticated.php

        // https://laravel.com/docs/7.x/authentication#included-authenticating

        // BASIC LOGIN USER DATA

        // README!! $user_id = client wd_id No!!!
        $is_client = false;
        $user = Auth::user();

        if($user->role->name == "Client" || $user->role->name == "client") {
          $is_client = True;
        }

        $userID = $user->id;
        $clientID = $user->wd_id;
        $userName = $user->name;

        $companyName = $user->client->company_name;

        $PDFendPoint = config('constants.value.PDFendPoint');
        $PDFapiKey = config('constants.value.PDFapiKey');
        //$desktop = config('constants.value.desktop');
        $wd = new WebDistribution($PDFendPoint, $PDFapiKey); // , $desktop

        $cust = $wd->pdfCustomerID($clientID);
        $custID = $cust[0];

        if ($custID == NULL) {
          return view('home', [
            'orderHistoryObj' => array(),
            'companyName' => $companyName,
            'userName' => $userName,
            'repName' => "sales@innovationsusa.com",
            'repEmail' => "Mercedes Coleman",
            'repPhoneNumber' => '',
            'client' => false,
            'is_client' => $is_client,
          ]);
        }

        // ALL SESSION IS CREATED:
        // app\Http\Controllers\auth\LoginController.php

        // ORDER HISTORY
        // $request-> comes from : \app\Http\Middleware\RedirectIfAuthenticated.php -- $request->session()->get('customerId')
        $PDFurl = $PDFendPoint . 'transaction?company=2&customer=' . $custID . '&count=20&with[]=transactionPayments&with[]=items&with[]=services';
        $data = $wd->curlPDFapi($PDFurl);
        $orderHistoryObj = json_decode($data, true);

        foreach ($orderHistoryObj as $index => $order) {

            if ($order['invoice_number'] == null) {
                $orderHistoryObj[$index]['invoice_number'] = "View";
            }

            $href2 = "class='inactive'";
            $href1 = "";
            $amountDue = (float)$order['total_amount'] - (float)$order['amount_paid'];
            if ($amountDue > 0) {
                $href2 = "href='/invoice/?" . $order['id'] . "&stat=notification' target='_blank' class='active orderBtn'";
                $now = time();
                // $tt1 = $now - 3600*24;
                $tt1 = $now;// for testing now
                // print_r(strtotime($order['firm_order_date'])) .' '. $tt1); die();

                if (strtotime($order['firm_order_date']) > $tt1) {
                    $href = "javascript:void(0)";
                    $aClass = 'inactive';
                } else {

                    //$href = " href='/home/pay/". $order['full_transaction_number'] ."'";
                    $aClass = 'active';
                    $href = 'pay/' . $order['id'];
                }

                $payStr = "PAY";
                $linkStr = "";
                $href1 = "export-invoice/notification/" . $order['id'];
            } else {
                $href = "";
                $payStr = "PAID";
                $href3 = "href='https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=" . $order['transaction_number'] . "'";
                if (isset($order) && $order['date_shipped'] != '')
                    $microtime = abs(strtotime(now()) - strtotime($order['date_shipped']));
                else {
                    $microtime = 0;
                }
                $state = "notification";
                if ($microtime > 30 * 24 * 60 * 60) {
                    $linkStr = "<a class='payBtn inactive' target='_blank' {$href3}>Delivered</a>";
                    $state = "invoice";

                } else {
                    $linkStr = "<a class='payBtn inactive' target='_blank' {$href3}>Link</a>";
                }
                //$href1 = "href=".$state."/".$order['invoice_number']." target=_blank class=invoiceBtn";
                $href1 = "export-invoice/" . $state . "/" . $order['id'];
            }

            $orderHistoryObj[$index]['amountDue'] = $amountDue;
            $orderHistoryObj[$index]['href'] = $href;
            $orderHistoryObj[$index]['href1'] = $href1;
            $orderHistoryObj[$index]['href2'] = $href2;
            $orderHistoryObj[$index]['payStr'] = $payStr;
            $orderHistoryObj[$index]['linkStr'] = $linkStr;

        }


        $PDFurl = $PDFendPoint . 'sample-transaction?company=2&customer='.$custID.'&count=1';
        $data = $wd->curlPDFapi($PDFurl);
        //dd(json_decode($data));

        $sampleOrders = json_decode($data);

        $rep_id = 0;

        if (isset($cust) && is_array($cust) && is_array($cust[1])) {
            $rep_id = $cust[1][0]['rep_id'];
        }

        $repEmail = 'sales@innovationsusa.com';
        $repName = 'Mercedes Coleman';
        $phone_number = '866.498.0515';

        if(isset($orderHistoryObj) && count($orderHistoryObj) > 0) {
          //$rep_id = $orderHistoryObj[0]['rep1_id'];
          if($rep_id != 409) {
            $sales_data = $wd->pdfRep($rep_id);
          //dd($sales_data);
          if(count($sales_data['phone_numbers']) > 0) {
            $phone_number = $sales_data['phone_numbers'][0]['phone_number'];
          }

          if ($sales_data['name'] != "")
            $repName = $sales_data['name'];

          if(array_key_exists('id', $sales_data['employees'])) {
            $repEmail = $sales_data['employees']['email_address'];
          }
          else {
            $repEmail = $sales_data['employees'][0]['email_address'];
          }

          $request->session()->put('rep_id', $sales_data['id']);
          $request->session()->put('rep_email', $repEmail);
          }
        }
        else {
          $request->session()->put('rep_id', $rep_id);
          $request->session()->put('rep_email', $repEmail);
        }



        //dd($sales_data);

        // if(array_key_exists('primary_phone_number', $sales_data) && isset($sales_data['primary_phone_number'])) {

        //   $phone = $sales_data['primary_phone_number'];
        //   if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $sales_data['primary_phone_number'],  $matches ) )
        //   {
        //     $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
        //   }
        //   else if (  preg_match( '/^\((\d{3})\) (\d{3})\-(\d{4})$/', $sales_data['primary_phone_number'],  $matches ) )
        //   {
        //     $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
        //   }
        //   else if ( preg_match( '/^\+\(\d(\d{3})\)(\d{3})\-(\d{4})$/', $sales_data['primary_phone_number'],  $matches ))
        //   {
        //     $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
        //   }

        //   else if ( preg_match( '/^\+\((\d{3})\)(\d{3})\-(\d{4})$/', $sales_data['primary_phone_number'],  $matches ))
        //   {
        //     $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
        //   }
        // } else {
        //   $phone = '866.498.0515';
        // }


        // //dd($phone);
        // $rep_row = Rep::where('phone', 'LIKE', $phone)->first();

        // // get the rep email from the rep table
        // $repEmail = "";
        // $repName = trim($sales_data['name']);

        // if(isset($rep_row)) {
        //   if($rep_row->sales_rep_outside1_email != "")
        //     {
        //       $repEmail = $rep_row->sales_rep_outside1_email;
        //       $repName = $rep_row->sales_rep_outside1;
        //     }
        //   else {
        //     $repEmail = $rep_row->sales_rep_outside2_email;
        //     $repName = $rep_row->sales_rep_outside2;
        //   }
        // } else {
        //   $repEmail = 'sales@innovationsusa.com';
        //   $repName = 'Mercedes Coleman';
        // }

        // $nameStr = $sales_data['name'];
        // $nameArr = explode('-', $nameStr);

        return view('home', [
          'orderHistoryObj' => $orderHistoryObj,
          'sampleOrders' => $sampleOrders,
          'companyName' => $companyName,
          'userName' => $userName,
          'repName' => trim($repName),
          'repEmail' => $repEmail,
          'repPhoneNumber' => $phone_number,
          'client' => true,
          'is_client' => $is_client,
        ]);
    }

    public function recent_orders(Request $request)
    {
        //print_r(333); die();
        // logic in
        // /app/Http/Middleware/RedirectIfAuthenticated.php

        // https://laravel.com/docs/7.x/authentication#included-authenticating

        // BASIC LOGIN USER DATA

        // README!! $user_id = client wd_id No!!!
        $user = Auth::user();

        $userID = $user->id;
        $clientID = $user->client->wd_id;
        // $clientID = '47720';
        $companyName = $user->client->company_name;

        $PDFendPoint = config('constants.value.PDFendPoint');
        $PDFapiKey = config('constants.value.PDFapiKey');
        //$desktop = config('constants.value.desktop');
        $wd = new WebDistribution($PDFendPoint, $PDFapiKey); // , $desktop

        $cust = $wd->pdfCustomerID($clientID);

        $custID -= $cust[0];

        // ALL SESSION IS CREATED:
        // app\Http\Controllers\auth\LoginController.php

        // ORDER HISTORY
        // $request-> comes from : \app\Http\Middleware\RedirectIfAuthenticated.php -- $request->session()->get('customerId')
        $PDFurl = $PDFendPoint . 'transaction?company=2&page=1&count=20&customer_id=' . $custID . '&with[]=transactionPayments&with[]=items&with[]=services';
        $data = $wd->curlPDFapi($PDFurl);
        $orderHistoryObj = json_decode($data, true);

        // dd($orderHistoryObj);

        return view('recent-orders', [
            'orderHistoryObj' => $orderHistoryObj,
            'companyName' => $companyName
        ]);
    }

    public function invoice($status, $order_id)
    {

        $PDFendPoint = config('constants.value.PDFendPoint');
        $PDFapiKey = config('constants.value.PDFapiKey');
        //$desktop = config('constants.value.desktop');
        $wd = new WebDistribution($PDFendPoint, $PDFapiKey); // , $desktop
        $result = $wd->pdfInvoice($status, $order_id);

        header('Cache-Control: public');
        header('Content-type: application/pdf');
        header('Content-Disposition: inline');
        header('Content-Length: ' . strlen($result));

        echo $result;

    }

    public function myAccount()
    {

        $user = Auth::user();
        $clientInfo = $user->client;
        $filename = explode('/', $clientInfo);
        if (count($filename) > 1)
            $client_cert = $filename[count($filename) - 1];
        else {
            $client_cert = "File limit 10MB";
        }

        return view('my-account', [
            'user' => $user,
            'client' => $clientInfo,
            'client_cert' => $client_cert
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'wd_id' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:21'],
            'country' => ['required', 'string', 'max:50'],
            'zip_code' => ['required', 'string', 'max:21'],
            'certificate' => ['required', 'mimes:pdf, docx,jpg,png', 'max:1024'],
        ]);


    }

    public function updateAccount(Request $request)
    {
        $data = $request->all();

        $client = Client::where('wd_id', '=', $data['wd_id'])->first();

        if ($this->validator($request->all())->validate()) {

            $file_path = NULL;
            if ($client->cert_file != NULL && $client->cert_file != "")
                Storage::delete($client->cert_file);

            if ($request->hasFile('certificate')) {
                $file = $request->file('certificate');
                $ext = $request->file('certificate')->extension();
                $filename = hash('ripemd160', $request->company_name);
                $filename = $filename . '-' . $request->wd_id . '.' . $ext;
                $file_path = Storage::putFileAs('public/clients/certs', $file, $filename);
            }

            $client->company_name = $data['company_name'];
            $client->city = $data['city'];
            $client->state = $data['state'];
            $client->country = $data['country'];
            $client->zip_code = $data['zip_code'];
            $client->cert_file = $file_path;
            $client->comments = $data['comments'];
            $client->save();
        }


        return back()->withInput()->withSuccess('Account updated successfully.');
    }

    public function priceList()
    {
        //storage/SP22 Pricelist (US).pdf
    }
}


/*
  repObj
    Array(
      [id] => 409
      [company_id] => 2
      [rep_code] => P
      [name] => Purchaser Accounts
      [fax] =>
      [country_id] => 228
      [master_id] =>
      [national_id] =>
      [territory_id] => 1198
      [pay_commission_on] => Invoice
      [standard_commission_percent] => 0
      [notes] => 2019 - Sales target (2) - $138,000 ($11,500/mo)
      [sales_target] => 138000
      [start_date] => 2016-12-02
      [end_date] => 1999-08-31
      [transaction_employee_id] =>
      [auto_email_transactions] => 1
      [bills_client] =>
      [bills_client_pricing] => 1
      [substitute_on_invoice] =>
      [buy_sell_discount_percent] =>
      [general_ledger_code] =>
      [created_at] => 2016-12-02T09:31:06-05:00
      [updated_at] => 2020-02-19T10:05:13-05:00
      [deleted_at] =>
      [phone_numbers] => Array
          (
          )

      [name_with_code] => Purchaser Accounts (P)
  )



  AddressObj
  Array (
    [id] => 620859
    [name] => Primary
    [description] =>
    [primary] => 1
    [company_name] => HR Paint Services, LLC
    [attention] => Christian Hernandez
    [street] => 6718 Parkwood street
    [street2] =>
    [city] => Hyattsville
    [state_id] => 3857
    [country_id] => 228
    [postal_code] => 20784
    [created_at] => 2020-03-09T16:23:15-04:00
    [updated_at] => 2020-03-09T16:23:15-04:00
    [deleted_at] =>
    [state] => Array
        (
            [id] => 3857
            [country_id] => 228
            [code] => MD
            [name] => Maryland
            [honor_home_state_resale] =>
            [enforce_resale_expiration] =>
            [state_tax_percent] =>
            [federal_tax_percent] =>
            [country] => Array
                (
                    [id] => 228
                    [priority] => 1
                    [code] => US
                    [code3] => USA
                    [name] => United States
                    [phone_format] =>
                    [tax_percent] =>
                    [metric] => 0
                    [default_currency_id] => 151
                )

        )

    [country] => Array
        (
            [id] => 228
            [priority] => 1
            [code] => US
            [code3] => USA
            [name] => United States
            [phone_format] =>
            [tax_percent] =>
            [metric] => 0
            [default_currency_id] => 151
        )

    [formatted] => 6718 Parkwood street
                   Hyattsville, MD 20784
                   United States
) */
