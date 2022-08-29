@php
print_r($message);
@endphp
<div>

    @php

        print_r($embed);

        $link = Request::segment(1).'/'.Request::segment(2);

    @endphp
</div>

<p class="text-left" style="padding:12px 0 0 9px;"><a href='{{$link}}'>View full page</a></p>
