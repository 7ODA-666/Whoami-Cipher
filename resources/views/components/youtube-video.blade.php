@props(['url', 'title' => 'Educational Video'])

@php
    // Extract YouTube video ID from URL
    $videoId = '';
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
        $videoId = $matches[1];
    }
@endphp

@if($videoId)
<div class="w-full max-w-full">
    <div class="video-container bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border shadow-lg">
        <iframe
            src="https://www.youtube.com/embed/{{ $videoId }}"
            title="{{ $title }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
            loading="lazy">
        </iframe>
    </div>
</div>
@endif
