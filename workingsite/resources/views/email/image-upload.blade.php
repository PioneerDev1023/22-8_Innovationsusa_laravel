<!DOCTYPE html>
<html>
<head>
    <title>Installation Image Uploaded</title>
</head>
<body>

<p>A user uploaded installation images.  Full submission data can be seen by staff at www.innovationsusa.com/dashboard/form-data.</p>

    <h3>Some of the data submitted is below:</h3>

      <p>Company name: {!! $data['companyName'] !!}</p>
      <p>Designer name: {!! $data['designerName']!!}</p>
      <p>SKUs featured: {!! $data['skus']!!}</p>
      <p>Email: {!! $data['userEmail'] !!}</p>
      <p>Images:</p>
      <div>
    <div class="col-md-10">
        @foreach($data['images'] as $image)
            <img src="{{'https://www.innovationsusa.com/storage/'.ltrim($image, 'public')}}" style="width:100px">
        @endforeach
    </div>

</body>
</html>
