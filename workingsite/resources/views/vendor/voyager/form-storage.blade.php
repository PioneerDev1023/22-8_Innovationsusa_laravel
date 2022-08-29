@extends('voyager::master')

@section('page_header')
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
@endsection

@include('voyager::alerts')
@include('voyager::dimmers')

@section('content')
      <h1 class="page-title">Manage Form Submissions</h1>
      <div class="container-fluid">
        <p>Export all form data stored for given form between the following dates:</p>

        {{ Form::open(
          array(
              'name' => 'export-forms',
              'url' => 'dashboard/form-data/',
              'method' => 'get',
              'class' => "form",
              'id' => "filter_form"
              )
          ) }}

         @php
         //dd($filter);
         $form_id = isset($filter['form_id']) ? $filter['form_id'] : "";
         $from = isset($filter['from']) ? $filter['from'] : "";
         $to = isset($filter['to']) ? $filter['to'] : "";
         echo '<div class="form-group  col-md-12">';
         echo Form::label('form Title', 'Form Title', ['class' => 'control-label']);
         echo Form::select('form_name', $formTitles, $form_id, ['required'=>'false', 'id' => 'form_name' , 'class'=>'form-control']);
         echo '</div>';
         echo '<div class="form-group  col-md-12">';
         echo Form::label('from', 'Date From', ['class' => 'control-label']);
         echo Form::date('from', $from, ['id' => 'from', 'class'=>'form-control']);
         echo '</div><div class="form-group  col-md-12 ">';
         echo Form::label('to', 'Date To', ['class' => 'control-label']);
         echo Form::date('to', $to, ['id' => 'to', 'class'=>'form-control']);
         echo '</div>';
         echo Form::submit('FILTER RESULT', ['class' => 'btn btn-primary']);
         @endphp
         {{ Form::close() }}
         {{ Form::open(
          array(
              'name' => 'export-forms',
              'url' => 'dashboard/export-form/',
              'method' => 'post',
              'class' => "form",
              'id' => "export_form"
              )
          ) }}
          @php
          echo Form::hidden('form_id', $form_id);
          echo Form::hidden('from', $from);
          echo Form::hidden('to', $to);
          echo Form::submit('EXPORT CSV FILE', ['class' => 'btn btn-danger float-right']);
          @endphp
          {{ Form::close() }}
        </div>
        <div class="table-container container-fluid mx-5 px-5">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">form title</th>
                @if(isset($col_data) && count($col_data) > 0)
                    @foreach($col_data as $col)
                        <th scope="col">{{$col}}</th>
                    @endforeach
                @endif
                <th scope="col">created at</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                @php
                $form_data = $row->getFormDataAttribute($row->data)
                @endphp
                    <tr>
                    <th scope="row">{{$index+1}}</th>
                    <td>{{$row->form_title}}</td>
                    @foreach($col_data as $col)
                        @if(is_array($form_data[$col]) || is_object($form_data[$col]))
                            @if($col == "images" || $col == "images")
                                <td class="text-center">
                                    <a class="modal-link">Show Files</a>
                                    <input class="image-files" type="hidden" value="{{json_encode($form_data[$col])}}">
                                    <!-- @foreach($form_data[$col] as $image)
                                        <img src="{{Storage::url($image)}}" style="max-width:100px">
                                    @endforeach -->
                                </td>
                            @else
                                <td>{{json_encode($form_data[$col])}}</td>
                            @endif
                        @else
                            @if($col == "images" || $col == "images")
                                <td>
                                    <img src="{{Storage::url($form_data[$col])}}" style="max-width:100px">
                                </td>
                            @else
                            <td>{{$form_data[$col]}}</td>
                            @endif
                        @endif                        
                    @endforeach
                    <td>{{$row->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="padding: 40px">
                    <h3 class="modal-title text-center m-3" id="exampleModalLabel">Select the image files to download.</h3>
                <div class="modal-body">
                    <input type="hidden" id="storage_url" value="{{Storage::url('')}}">
                    <form class="my-5" action="/dashboard/download-zip" method="GET">
                        <div id="image_form" class="row">
                            <div class="form-group">
                                <input type="checkbox" class="form-check-input">
                                <img src="./" style="max-width:100px;">
                            </div>
                        </div>
                        <button type="submit" id="download_btn" class="btn btn-success" style="display: block; width:100%; margin-top:50px;">Download Images</button>
                    </form>
                </div>
                <div class="modal-footer">                    
                </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.modal-link').on('click', function() {
            $('#image_form').empty();
            let files = $(this).siblings('.image-files').val();
            let storageUrl = $('#storage_url').val();
            files = JSON.parse(files);
            console.log(files)
            let fileData = Array();
            files.map((value) => {
                let url = value;
                if(value.includes('public/')) {
                    url = value.replace('public/', "")
                }
                let data = `<div class="form-group d-inline m-0 col-md-4">
                                <input type="checkbox" name="files[]" class="form-check-input" value="${value}">                                
                                <img src="${storageUrl+url}" style="max-width:100px; height:100px">
                            </div>`;
                fileData.push(data);
            })
            $('#image_form').append(fileData);
            $('#fileModal').modal();
        })
    })
</script>
@endsection
