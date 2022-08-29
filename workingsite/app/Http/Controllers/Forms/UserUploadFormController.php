<?php

namespace App\Http\Controllers\Forms;

use Illuminate\Http\Request;
use App\Http\Utils\Namefix;
use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;
use App\FormStorage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserUploadFormController extends Controller
{
    public function userImageForm() {

        return view('forms.user-image-upload');
    }

    public function saveUserImage(Request $request) {


        $this->validator($request->all())->validate();

        // image fields should be named as "images" or "image"

        $files = $request->file('files');

        if (!isset($files) || count($files) < 1) {
            return back()->withInput()->withErrors(['files' => 'Please attach at least a file!']);
        }

        $images = array();

        foreach ($request->file('files') as $imagefile) {

            $ext = $imagefile->extension();
            $filename = intval(microtime(true) * 1000);
            $filename = $filename .'.' . $ext;
            $path = Storage::putFileAs('public/user-upload-images', $imagefile, $filename);
            if($path) {
                $images[] = $path;
            }
        }

        $data = array(
            "companyName" => $request->companyName,
            "designerName" => $request->designerName,
            "skus" => $request->skus,
            "photoName" => $request->photoName,
            "checkbox" => $request->checkbox,
            "termAgree" => $request->termAgree,
            "userName" => $request->userName,
            "userEmail" => $request->userEmail,
            "images" => $images,
        );


        $formStorage = new FormStorage();
        $formStorage->form_id = 2;
        $formStorage->form_title = "User Image Upload Form";
        $formStorage->setFormDataAttribute($data);
        $result = $formStorage->save();
        if (!$result) {
            return back()->withInput()->withErrors(['database' => 'Cannot save the input data!']);
        }

        $email = $request->userEmail;
        $email2 = 'marketing@innovationsusa.com';
        $html = "";

        Mail::send('email.image-upload', ['data'=>$data], function ($message) use ($email, $email2, $html) {
          $message->to( [$email, $email2], 'Recipients' )
            ->subject('Innovations Image Submission')
            ->from('noreply@innovationsusa.com','noreply@innovationsusa.com')
            ->setBody($html, 'text/html'); //html body
          });

        return back()->withInput()->withSuccess('Images uploaded successfully.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'companyName' => ['required', 'string', 'max:255'],
            'skus' => ['required', 'string', 'max:255'],
            'termAgree' => ['required', 'string', 'max:21'],
            'userName' => ['required', 'string', 'max:30'],
            'files' => ['required'],
            'userEmail' => ['required', 'string','email', 'max:255'],
            'checkbox' => ['required'],
            // 'g-recaptcha-response' => 'required|captcha',
        ]);
    }
}
