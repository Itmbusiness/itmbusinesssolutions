<x-layouts::app :title="__('Overview')">
    @if (auth()->user()->isConsultant())
        @include('pages.dashboard.consultant')
    @else
        @include('pages.dashboard.client')
    @endif
</x-layouts::app>
