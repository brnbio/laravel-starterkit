<x-mail::message>

@if (! empty($greeting))
    # {{ $greeting }}
@endif

@foreach ($introLines as $line)
    {{ $line }}
@endforeach

@isset($actionText)
    <x-mail::button :url="$actionUrl" :color="$level ?? 'primary'">
        {{ $actionText }}
    </x-mail::button>
@endisset

@foreach ($outroLines as $line)
    {{ $line }}
@endforeach

<p>
    Besten Grüße
</p>
<p>
    Ihr Team von {{ config('app.name') }}
</p>

</x-mail::message>