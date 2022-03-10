@extends('blog.layout')

@section('content')
    <h1>Tailwind, Livewire and Alpine user interface</h1>
    <p>
        Last time we created our Wordle game in PHP and Laravel, and to be able to test it we added a very simple
        artisan command. But that isn't very user-friendly and completely useless if we want to deploy our app as a
        website, so today we're going to add a web user interface.
    </p>
    <p>
        For this we'll use Tailwind, Alpine and Livewire. If you've never used them before I recommend you read their
        respective documentations on how to install them in your Laravel app.
    </p>
    <p>
        Once those are installed, we're going to be using a Livewire component as our controller. To do this, we create
        the component and then point our homepage route to it.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/06ad7ac3a324469dac80bd92620fd0c3.js"></script>
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/42500f900a2afc1fbb36e39a2f801da7.js"></script>
    </p>
    <p>
        Explanation about the game components<br />
        - Add WordleGame + keyboard + guesses + letters + reset game<br />
        - Add addLetter method + alpine event listener + button clicks<br />
        - Implement guess method + messages
    </p>
    <p>
        Finished game:<br />
        - component class<br />
        - game view<br />
        - see other files in the repository<br />
    </p>
@endsection