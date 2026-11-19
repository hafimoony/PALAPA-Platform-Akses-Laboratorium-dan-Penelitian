@props(['judul'])
<div class="card mb-3">
    <div class="card-header"><h5 class="card-title mb-0">{{ $judul }}</h5></div>
    <div class="card-body">{{ $slot }}</div>
</div>
