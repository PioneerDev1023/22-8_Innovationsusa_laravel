<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($products as $product)
        <url>
            <loc>{{ url('/') }}/item/{{ strtolower($product->fabricName) }}</loc>
            <lastmod></lastmod>
            <changefreq>yearly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    @foreach ($catalogs as $catalog)

        <url>
            <loc>{{ url('/') }}/catalogs/{{ $catalog->slug }}</loc>
            <lastmod>$catalog->updated_at->tz('UTC')->toAtomString()</lastmod>
            <changefreq>yearly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach ($videos as $video)
        <url>
            <loc>{{ url('/') }}/videos/{{ $video->slug }}</loc>
            <lastmod>$video->updated_at->tz('UTC')->toAtomString()</lastmod>
            <changefreq>yearly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach ($collections as $collection)
        <url>
            <loc>{{ url('/') }}/collections/{{ $collection->slug }}</loc>
            <lastmod>$collection->updated_at->tz('UTC')->toAtomString()</lastmod>
            <changefreq>yearly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
        <url>
            <loc>{{ url('/') }}/mentions</loc>
            <lastmod></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>



</urlset>
