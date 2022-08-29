<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Role;
use App\DatabaseStorageModel;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Client;
use \DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Utils\FormHelper;
use App\WebDistribution;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /* don't need this function here, but keep around for reference
      File upload example.
    public function uploadTradeCert(Request $request)
    {
      if($request->hasFile('trade_cert')){
        $file = $request->file('trade_cert');
        $ext = $request->file('trade_cert')->extension();
        $filename = hash('ripemd160', $request->company_name);
        $filename = $filename . '-' . $request->wd_id . '.' . $ext;
        $path = Storage::putFileAs('public/client/trade-certs', $file, $filename);

        // get client

        $client = Client::where('wd_id', '=', $request->wd_id)->first();
        $client->trade_cert = $path;

        $client->save();
      }
    }
    */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'wd_id' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:21'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function validator_guest(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
        ]);
    }

    public function register(Request $request)
    {
      $data = $request->all();

      $client = Client::where('wd_id', '=', $data['wd_id'])->first();

      if(empty($client) || $client === NULL) {
        return back()->withInput()->withErrors(['msg' => 'We cannot locate your account.  Please contact Customer service at 866.498.0515']);
      }

      $PDFendPoint = config('constants.value.PDFendPoint');
      $PDFapiKey = config('constants.value.PDFapiKey');
      //$desktop = config('constants.value.desktop');
      $wd = new WebDistribution($PDFendPoint, $PDFapiKey); // , $desktop
      $custData = $wd->pdfCustomerCountry($data['wd_id']);

      //dd($custData);

      if($custData['zip_code'] != $data['zip_code']) {
        return back()->withInput()->withErrors(['msg' => 'Client ID is invalid.  Please contact customer service at 866.219.6468 or try again.  Note: Your zip code must match our customer records.']);
      } else {
        $this->validator($request->all())->validate();

        if ($client->country != 'USA') {
          return back()->withInput()->withErrors(['msg' => 'Online accounts are for US clients only.  For additional account or product information please contact customerservice@innovationsusa.com']);
        }

        else {
          event(new Registered($user = $this->create($request->all())));
        }

        // this works so keep for future reference
        // $this->uploadTradeCert($request);

        return back()->withInput()->withSuccess('Account created successfully. You may <a href=\'/login\' class=\'orangelink\'>log in</a>');
      }
    }

    public function registerGuest(Request $request) {

      $data = $request->all();
      $result = $this->validator_guest($request->all())->validate();
      //dd($result);

      $user = User::where('email', '=', $data['email'])->first();
      if($user != null) {
        return response()->json(array('success'=>"false", 'error'=>"exist"));
      }
      $role = Role::where('name', 'Guest')->orWhere('name', 'guest')->first();
      User::create([

        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role_id' => $role->id,
        'newsletter' => $data['newsletter'],
      ]);

      // data for saving into the cart_storage data
      $cartData = array('id'=> $data['email'], 'cart_data'=> array('company' => $data['company']));
      //DB::insert('insert into cart_storage (id, cart_data) values (?, ?)', [$data['email'], $data['company']]);

      DatabaseStorageModel::create($cartData);

      //save the company for guest into the cart_storage table


      // automatically login
      $loginData = array('email'=> $data['email'], 'password'=> $data['password']);

      if (!\Auth::attempt($loginData)) {
        return response()->json(array('success'=>"false", 'error'=>"notLogin"));

      }

      $request->session()->regenerate();
      return response()->json(array('success'=>"true"));

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data, $flag = True)
    {
      $role = Role::where('name', 'client')->orWhere('name', 'customer')->first();
      if(!$flag) {
        $role = Role::where('name', 'Guest')->orWhere('name', 'guest')->first();
      }
          User::create([
            'wd_id' => $data['wd_id'],
            'name' => $data['name'],
            'email' => $data['email'],

            'role_id' => $role->id,
            'password' => Hash::make($data['password']),
            'updated_at' => Carbon::now()
        ]);

        return back()->withInput()->withSuccess('Account created successfully.');
  }

  public function showRegistrationForm() {
    $formHelp = new FormHelper();
    $countryArr = $formHelp->countryArr();
    $statesArr = $formHelp->statesArr();

    return view ('auth.register',
      ['countryArr' => $countryArr, 'statesArr' => $statesArr]);
  }

}
