<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title><![CDATA[{{ setting('site_name_en', 'Khandan') }} - {{ config('app.name') }}]]></title>
        <link>{{ config('app.url') }}</link>
        <description><![CDATA[{{ __('messages.site_description') }}]]></description>
        <language>{{ $locale }}</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url('/rss') }}" rel="self" type="application/rss+xml"/>
        
        @foreach($articles as $article)
        <item>
            <title><![CDATA[{{ $article->title }}]]></title>
            <link>{{ url('/articles/' . $article->slug) }}</link>
            <guid isPermaLink="true">{{ url('/articles/' . $article->slug) }}</guid>
            <description><![CDATA[{{ $article->excerpt ?? Str::limit(strip_tags($article->body), 200) }}]]></description>
            @if($article->author)
            <dc:creator><![CDATA[{{ $article->author->name }}]]></dc:creator>
            @endif
            @foreach($article->categories as $category)
            <category><![CDATA[{{ $category->name }}]]></category>
            @endforeach
            <pubDate>{{ $article->published_at->toRssString() }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>
