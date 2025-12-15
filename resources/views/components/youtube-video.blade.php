@props(['url', 'title' => 'Educational Video'])

@php
    // Extract YouTube video ID from URL
    $videoId = '';
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
        $videoId = $matches[1];
    }
@endphp

@if($videoId)
<div class="w-full">
    <div class="relative w-full bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg shadow-lg overflow-hidden" style="padding-bottom: 56.25%; /* 16:9 aspect ratio */">
        <iframe
            class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/{{ $videoId }}"
            title="{{ $title }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen>
        </iframe>
    </div>
</div>
@endif
