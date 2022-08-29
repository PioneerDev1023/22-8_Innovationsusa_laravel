<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\User;

class VoyagerUserController extends BaseVoyagerUserController
{
  protected function guard() {
    return Auth::guard(app('VoyagerGuard'));
  }
  public function indexGuest(Request $request) {
    $this->authorize('export_guests');
    // if query param set, form has been submitted -> display message
    // if(isset($request->query)) {
    //   $query = $request->query();
    //   if(isset($query['guestUser'])) {
    //     Session::flash('message', $query['product'].' updated successfully!');
    //     Session::flash('alert-class', 'success');
    //   }
    // }
    return view('vendor.voyager.export-guests');
  }
  public function exportGuest(Request $request) {
    $this->authorize('export_guests');
    $from = $request->input('from');
    $to = $request->input('to');
    if($from >= $to) {
        return redirect()->back()->with('message', 'The starting date is older than the ending date');
    }
    $guests = User::where('created_at', '>', $from)->where('created_at', '<', $to)->get();
    //dd($guests);
    if($guests) {
        $delimiter = ",";
        $f = fopen('php://memory', 'w');
        fputcsv($f, array("Name", "Email", "Role", "Registered Date", "Updated Date", 'Newsletter'), $delimiter);        
        foreach($guests as $guest) {
            $lineData = array($guest->name, $guest->email, $guest->role->name, $guest->created_at, $guest->updated_at, $guest->newsletter ? "Yes" : "No");
        fputcsv($f, $lineData, $delimiter);
        }
        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . "Guests(".$from."_".$to.").csv" . '";');

        //output all remaining data on a file pointer
        fpassthru($f);
    }
    //return redirect()->back();
  }
}
