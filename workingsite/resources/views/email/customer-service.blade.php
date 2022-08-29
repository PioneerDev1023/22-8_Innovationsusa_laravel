<!DOCTYPE html>
<html>
<head>
    <title>Customer Service Request</title>
</head>
<body>
<p>Hi, {!! $data['name'] !!}</p>
<p>We have received your customer service request and will respond shortly.</p>

    <p>Thank you,</p>
    <p>Team Innovations</p>
    <h3>The data you submitted is below:</h3>
      @if ($data['wd_id'])
        <p>Innovations Customer ID: {{ $data["wd_id"] }}</p>
      @endif
        <p>Company Name: {{ $data["company_name"] }}</p>
        <p>Name: {{ $data["name"] }}</p>
        <p>E-Mail Address: {{ $data["email"] }}</p>
        <p>Topic: {{ $data["topic"] }}</p>
        <p>Message:  {{ $data["message"] }}</p>
</body>
</html>
