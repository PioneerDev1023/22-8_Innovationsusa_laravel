<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ codecheef ]]></title>
        <link><![CDATA[ https://example.com/feed ]]></link>
        <description><![CDATA[ A Nice Description Of Your Website! ]]></description>
        <language>en</language>
        <pubDate>{{ now()->toDayDateTimeString('America/New_York') }}</pubDate>
        @if(!empty($data))
        @foreach($data as $datum)
            <item>
                <title><![CDATA[{{ $datum->title }}]]></title>
                <link>{{ $datum->slug }}</link>
                <description><![CDATA[{!! $datum->description !!}]]></description>
                <category>{{ $datum->createt_at }}</category>
                <author><![CDATA[{{ $datum->name  }}]]></author>
                <guid>{{ $datum->id }}</guid>
                <pubDate>{{ $datum->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
        @endif
    </channel>
</rss>