<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\FormStorage;
use App\Forms;

class VoyagerFormStorageController extends BaseVoyagerUserController
{
  protected function guard() {
    return Auth::guard(app('VoyagerGuard'));
  }

  public function indexFormStorage(Request $request) {
    $this->authorize('view_form_data');
    // $this->authorize('export_form');
    $form_id = $request->input('form_name');
    $from = $request->input('from');
    $to = $request->input('to');

    $filter = array();
    $data = null;
    if(isset($form_id) && $form_id != null) {
      $filter['form_id'] = $form_id;
      $data = FormStorage::where('form_id', '=', $form_id);
    } else {
      $data = FormStorage::where('form_id', '=', 1);
    }

    if(isset($from) && $from != null) {
      $filter['from'] = $from;
      $data = $data->where('created_at', '>=', $from);
    }

    if(isset($to) && $to != null) {
      $filter['to'] = $to;
      $data = $data->where('created_at', '<=', $to);
    }

    if(isset($data)) {
      $data = $data->get();
    }
    else {
      $data = array();
    }

    $col_data = array();

    if(isset($data) && count($data) > 0) {
      $row = $data[0];
      $col_data = array_keys($row->getFormDataAttribute($row->data));
    }

    $forms = FormStorage::select('id','form_id', 'form_title')
      ->groupBy('form_id')
      ->get();
    $formTitles = array();
    foreach($forms as $form) {
      $formTitles[$form->form_id] = $form->form_title;
    }
    //dd($formTitles);
    return view('vendor.voyager.form-storage', array('data' => $data, 'col_data'=>$col_data, 'formTitles' => $formTitles, "filter" => $filter));
  }

  public function exportFormStorage(Request $request) {

    $form_id = $request->input('form_id');
    $from = $request->input('from');
    $to = $request->input('to');

    $filter = array();
    if(isset($form_id) && $form_id != "") {
      $filter['form_id'] = $form_id;
    $data = FormStorage::where('form_id', '=', $form_id);
    }

    if(isset($from) && $from != "") {
      $filter['from'] = $from;
      $data = $data->where('created_at', '>', $from);
    }
    if(isset($to) && $to != "") {
      $filter['to'] = $to;
      $data = $data->where('created_at', '<', $to);
    }

    if(isset($data))
      $data = $data->get();
    else {
      $data = array();
    }

    //dd($guests);
    if(isset($data) && count($data) > 0) {
        $row = $data[0];
        $col_data = array_keys($row->getFormDataAttribute($row->data));
        $delimiter = ",";
        $f = fopen('php://memory', 'w');
        $col_data1 = array_merge(array("Form Title"), $col_data, array("Updated Date", 'status'));
        fputcsv($f, $col_data1, $delimiter);
        foreach($data as $item) {

            $storeData = array();
            $form_data = $item->getFormDataAttribute($item->data);
            foreach($col_data as $col) {
              if(is_array($form_data[$col]) || is_object($form_data[$col])) {
                $storeData[] = json_encode($form_data[$col]);
              } else {
                $storeData[] = $form_data[$col];
              }
            }
            $lineData = array_merge(array($item->form_title), $storeData, array($item->created_at, $item->updated_at));
        fputcsv($f, $lineData, $delimiter);
        }
        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . "FormData(".$from."_".$to.").csv" . '";');

        //output all remaining data on a file pointer
        fpassthru($f);
    }
    else {
      return redirect()->back();
    }
  }

  public function zip_download(Request $request)
  {

      $files = $request['files'];

      if(!$files) {
        return redirect()->back();
      }
      $zip_file = "images.zip";
      $zip = new \ZipArchive();

      $zipPath = storage_path("app/public/tmp/$zip_file");

      $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

      foreach ($files as $file)
      {
          // We're skipping all subfolders
          $filePath = storage_path("app/".$file);

          $relativeNameInZipFile = basename($filePath);

          // extracting filename with substr/strlen

          $zip->addFile($filePath, $relativeNameInZipFile);
      }
      $zip->close();
      return response()->download($zipPath);
  }
}
