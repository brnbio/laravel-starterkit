<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') === 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            if ('{{ $appearance ?? "system" }}' === "system" && matchMedia("(prefers-color-scheme: dark)").matches) {
                document.documentElement.classList.add("dark");
            }
        })();
    </script>
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @routes
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>