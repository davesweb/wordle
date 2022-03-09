<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title>Wordle - Spartner</title>
    </head>
    <body>
        <div class="container mx-auto p-1">
            <div class="w-full md:w-6/12 lg:w-3/12 mx-auto">
                <h1 class="font-medium text-3xl flex justify-between border-b border-slate-200">
                    <span>
                        Wordle<span class="text-green-500">Game</span>
                    </span>
                    <span class="text-orange-500"><a href="https://spartner.nl/" target="_blank">Spartner</a></span>
                </h1>
                @yield('content')
            </div>
        </div>
    </body>
</html>
