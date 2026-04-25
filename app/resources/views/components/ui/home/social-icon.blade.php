@props(['href' => '#', 'src' => '', 'size' => 85])
<a href="{{ $href }}" class="block" style="width:{{ $size }}px;height:{{ $size }}px">
<x-ui.lazy-image
            src="{{ $src }}"
            alt=""
            class="w-full h-full"
/>
</a>
