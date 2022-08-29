<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@elseif (trim($slot) === 'Innovations')
<img src="https://www.innovationsusa.com/images/logoLG_729x81.png" alt="Innovations Logo" width="35%">
@else 
{{ $slot }}
@endif
</a>
</td>
</tr>
