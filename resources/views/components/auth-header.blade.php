@props([
    'title',
    'description',
])

<div class="flex w-full flex-col">
    <h1 class="text-h3">{{ $title }}</h1>
    <p class="card-body mt-1">{{ $description }}</p>
</div>
