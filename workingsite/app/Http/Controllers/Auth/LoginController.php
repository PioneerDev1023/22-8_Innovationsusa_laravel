<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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

        $this->middleware('guest')->except('logout');

        $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'login', 'flipbook');

        SEOMeta::setTitle('Log In to Your Innovations Account');
        SEOMeta::setDescription("login innovations wallcovering");
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription("login innovations wallcovering");
        OpenGraph::setTitle('Login');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle('Login');
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle('Login');
        JsonLd::setDescription("login innovations wallcovering");
        JsonLd::setType('Flipbook');

    }

    //update method of AuthenticatesUsers class to catch the client login

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : $this->redirectTo();
    }

    // returns the client's dashboard url

    protected function redirectTo() {

        $user = $this->guard()->user();

        if ($user->role->name == "Client" || $user->role->name == "Customer") {

            $this->redirectTo = RouteServiceProvider::CLIENT_DASHBOARD;

        }


        return redirect()->intended($this->redirectTo);
    }

    protected function guard()
    {
        return Auth::guard(app('VoyagerGuard'));
    }

    public function logout(Request $request) {

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');

    }
}
