<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function getForm()
    {

        return view('forms.form', [

        ]);
    }

    public function getTestForm()
    {
        return view('test-form', ['data' => 'Request Presentation of Spring 2022 Collection']);
    }

    public function getTestForm2()
    {
        return view('test-form2', ['data' => 'Request Presentation of Spring 2022 Collection']);
    }

    public function saveFormStorage(Request $request) {
        $data = $request->all();
        $saveData = array(            
            'full_name' => $request->name,
            'company_name' => $request->company_name,
            'city' => $request->city,
            'state' => $request->state,
            'email' => $request->email,
            'phone_number' => $request->phone,
        );        

        //dd($saveData);
        $formData = new \App\FormStorage();
        $formData['form_id'] = 0;
        $formData['form_title'] = "Presentation Request";
        $formData->setFormDataAttribute($saveData);
        $result = $formData->save();
        if(!$result) {
            return redirect()->back()->withInput()->withErrors(['msg' => 'Cannot save form data. Please try again later.']);
        }
        return redirect()->back()->withInput()->withSuccess('Presentation request successfully saved!');
    }

    public function saveFormStorage2(Request $request) {
        $data = $request->all();
        $saveData = array(            
            'full_name' => $request->name,
            'company_name' => $request->company_name,
            'rep' => $request->rep,
        );        

        //dd($saveData);
        $formData = new \App\FormStorage();
        $formData['form_id'] = 1;
        $formData['form_title'] = "Representative Form";
        $formData->setFormDataAttribute($saveData);
        $result = $formData->save();
        if(!$result) {
            return redirect()->back()->withInput()->withErrors(['msg' => 'Cannot save form data. Please try again later.']);
        }
        return redirect()->back()->withInput()->withSuccess('Presentation request successfully saved!');
    }
}
