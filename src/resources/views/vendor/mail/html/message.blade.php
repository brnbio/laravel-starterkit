<x-mail::layout>

    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            <img src="{{ asset('images/logo.svg') }}" height="40" alt="{{ config('app.name') }}">
        </x-mail::header>
    </x-slot:header>

    {!! $slot !!}

    <x-slot:footer>
        <x-mail::footer>
            <h3>
                {{ config('app.name') }}
            </h3>
            <p>
                <strong>[IMPRESSUM]</strong>
            </p>
        </x-mail::footer>
    </x-slot:footer>

</x-mail::layout>
