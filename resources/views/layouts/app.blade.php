<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wordle - Spartner</title>
        @livewireStyles
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="dark:bg-gray-800">
        <div class="container mx-auto p-1">
            <div class="w-full md:w-6/12 lg:w-3/12 mx-auto p-2 md:p-0">
                <h1 class="font-medium text-3xl flex justify-between border-b border-slate-200 mb-2">
                    <span class="dark:text-slate-50">
                        Wordle<span class="text-green-500">Game</span>
                    </span>
                    <span class="text-orange-500"><a href="https://spartner.nl/" target="_blank">Spartner</a></span>
                </h1>
                @yield('content', $slot ?? '')
            </div>
        </div>
        @livewireScripts
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
