@props(['subject', 'label', 'custom' => false])
@php $path = \App\Support\PublicMedia::path($subject); @endphp

<article class="admin-image-item" x-data="{ preview: null, selected: false }">
    <div class="admin-image-preview">
        @if ($path)
            <img src="{{ asset($path) }}" alt="{{ $label }}" width="1536" height="1024" loading="lazy" x-show="!preview">
        @else
            <div class="flex h-full items-center justify-center gap-2 text-muted" x-show="!preview"><x-ui.icon name="image" size="24px" />{{ __('No image selected') }}</div>
        @endif
        <template x-if="preview"><img :src="preview" alt="{{ $label }}" width="1536" height="1024"></template>
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between gap-3">
            <h3 class="font-semibold">{{ $label }}</h3>
            <span class="text-meta text-muted">{{ !$path ? __('Removed') : ($custom ? __('Custom') : __('Default')) }}</span>
        </div>
        <form method="POST" action="{{ route('admin.images.update', $subject) }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')
            <x-ui.field :label="__('Choose image')" :for="'image-'.$subject" :hint="__('JPEG, PNG or WebP. Maximum 8 MB.')">
                <x-ui.input type="file" name="image" :id="'image-'.$subject" accept="image/jpeg,image/png,image/webp" required :aria-describedby="'image-'.$subject.'-hint'"
                    x-on:change="if (preview) URL.revokeObjectURL(preview); selected = !!$event.target.files[0]; preview = selected ? URL.createObjectURL($event.target.files[0]) : null" />
            </x-ui.field>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <x-ui.button type="submit" variant="secondary" icon="upload-simple" x-bind:disabled="!selected">{{ $path ? __('Replace image') : __('Upload image') }}</x-ui.button>
                @if ($path)
                    <x-ui.button variant="icon" type="submit" :form="'remove-image-'.$subject" icon="trash" :aria-label="__('Remove :image image', ['image' => $label])" :title="__('Remove image')" />
                @endif
                @if ($custom)
                    <x-ui.button variant="icon" type="submit" :form="'restore-image-'.$subject" icon="arrow-counter-clockwise" :aria-label="__('Restore default :image image', ['image' => $label])" :title="__('Restore default image')" />
                @endif
            </div>
        </form>
        <form method="POST" action="{{ route('admin.images.destroy', $subject) }}" id="remove-image-{{ $subject }}">
            @csrf
            @method('DELETE')
        </form>
        <form method="POST" action="{{ route('admin.images.restore', $subject) }}" id="restore-image-{{ $subject }}">
            @csrf
        </form>
    </div>
</article>
