<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<style>
    
@font-face {font-family: "Futura (Light)"; src: url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.eot"); src: url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.eot?#iefix") format("embedded-opentype"), url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.woff2") format("woff2"), url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.woff") format("woff"), url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.ttf") format("truetype"), url("//db.onlinewebfonts.com/t/03d4f5490a4430b3b649aa802662a936.svg#Futura (Light)") format("svg"); }

@font-face {font-family: "Futura Book"; src: url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.eot"); src: url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.eot?#iefix") format("embedded-opentype"), url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.woff2") format("woff2"), url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.woff") format("woff"), url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.ttf") format("truetype"), url("//db.onlinewebfonts.com/t/02a0efa4275f78836dfc89db9e21feea.svg#Futura Book") format("svg"); }

.inner-body {
    font-family : "Futura (Light)";
}
table.content > tr > .header {
    font-family: "Futura Book";
}
table.bg-beige {
    background-color: #e7e5dc;
    color: #75787B;
}
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>

<table class="wrapper bg-beige" width="600px" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
