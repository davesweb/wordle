@extends('blog.layout')

@section('content')
    <h1>A simple Wordle game in PHP and Laravel</h1>
    <p>
        The first thing we're going to need is a working Wordle game that we can use to play, and later to run our algorithms on to try and find the optimal strategy.
    </p>
    <p>
        Now, PHP isn’t the best language to write games in, but it is very much possible. As a PHP developer I choose to write this game in PHP because I use it every day and to
        show you it is possible. I choose to use Laravel as a framework because it takes care of everything we need to create a working app. It allows us to focus just on the
        game logic itself, instead of other things, and thus allows us to create a prototype of our game pretty fast, even when using PHP.
    </p>
    <p style="text-align: center">
        <img src="{{ asset('img/play-wordle-artisan.png') }}" />
    </p>
    <h2>Words</h2>
    <p>
        Every Wordle game uses a library of words, so to create a Wordle game we'll also need a list of words. I've Googled for lists of 5-letters words, which there are plenty
        of nowadays with the popularity of the game. Then I combined several lists into one, removed any duplicates and added it as a resource to our repository. I ended up with
        a list of over 2500 unique 5-letter words, which should be plenty for our game. There are probably more 5-letter words, and you are free to create your own list, so in the
        code we'll anticipate the use of different lists. To start we're going to add some simple interfaces and classes to provide our Wordle game with these words.
    </p>
    <blockquote>
        We'll add all our backend game logic to the <code>App\Game</code> folder. All other functionality will be added according to Laravel's default conventions, so if we for
        instance add a console commend you can just use <code>php artisan make:command &lt;name&gt;</code> to create it.
    </blockquote>
    <p>
        <script src="https://gist.github.com/davesweb/6d2a65b3129ebd699f9dc5562e82f60f.js"></script>
    </p>
    <p>
        We'll start with a simple interface that allows us to get a list of words. Using Laravel's service container we'll inject the correct implementation when we need it later
        on. Next is a simple implementation for this interface, that reads our text file with words and returns it.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/d22d7544c9bf74783606cddaa39137ad.js"></script>
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/9bfbf4e3bd3beff7d99ec9ac43522199.js"></script>
    </p>
    <p>
        And finally we add a <code>WordGenerator</code> that accepts our interface and can return a random word from the list. It also has method to check if any given word is
        present in the list, which we can use later on to check if a word is valid or not.
    </p>
    <h2>The Game Object</h2>
    <p>
        The next step is to create a <code>Game</code> object. This will contain all the logic needed to play the game.
    </p>
    <p>
        To start, the <code>Game</code> object needs the <code>WordGenerator</code> we've created so it can generate random words. It also needs some basic methods to implement
        the game. It needs a <code>guess</code> method which accepts a word inputted by the player, and it needs to have methods to check if the player has won or if the game is
        over. We’ll also need a <code>start</code> method that tells the game to generate a new word and reset any previous guesses.
    </p>
    <p>
        For the guess method, we also need to return to the player which letters are in the correct position, which letters are in the word in a different position, and which
        letters are not in the word. We can do this by returning an array which per letter has the result, so we’ll add the array return type to the guess method. For the result
        we'll add a backed enum with the possibilities.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/ba7f87d882acabc2d99c0d6fa59b994a.js"></script>
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/a856edd47cc698dad13e38a11fc4aa53.js"></script>
    </p>
    <h2>Game logic</h2>
    <p>
        Having the basic structure in place, we can now add the logic for the actual game. First of all, the game needs to have a state. It needs to keep track of the current
        word to guess, and it needs to keep track of how many guesses there have been and what they were. The easiest way to do this in PHP is to use sessions, so we'll use
        dependency injection to inject Laravel's session store.
    </p>
    <p>
        We'll also make the number of guesses variable. The default Wordle games lets you guess six times, but for our purpose we'll need it to be variable.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/abb5b9749de84c2e6bdcbfe298eb8f44.js"></script>
    </p>
    <p>
        The last thing to add to our <code>Game</code> object is the logic for the <code>guess</code> method. Though it might seem like a difficult bit of code, the algorithm to
        check the guess is actually pretty easy. You just split the current word and the guess into individual letters. Then you compare the letters in the same position with
        each other and if they are the same the result is a green letter. If that's not the case you compare the letter of the guess with all letters in the current word and if
        that letter is found in the current word, the result is a yellow letter. Otherwise, it's incorrect. We keep track of each letter and return the array with the results.
    </p>
    <p>
        We also check if the guess is actually valid in the first place. A guess is invalid when it has more or less letters than the current word, when it's not present in our
        list of words, or when the player already tried it. In any of those cases, we just throw an exception that we can deal with later.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/0f0a685b101783dd53f6a20214103aaa.js"></script>
    </p>
    <h2>Let's play!</h2>
    <p>
        With a complete game object it is time to test it out and play the game. We won't start with our UI just yet, so we'll create a simple artisan command to play the game
        with.
    </p>
    <p>
        <script src="https://gist.github.com/davesweb/7792bfcd444ebb9ff196bd32a88e2b58.js"></script>
    </p>
    <p>
        The command itself is pretty simple as well. It starts a new game, and as long as we are playing (meaning we haven't won, and we haven't lost) ask the player to input
        a new word. We display the result in the same way the Wordle game does (green background for correct letters, yellow for letters in the wrong position and black
        background for wrong letters). If we've won, lost or if there's an error we show a nice message.
    </p>
    <p>
        As mentioned before, we'll need to make sure our interfaces are bound to the correct implementations, for now that means the <code>WordProvider</code>. Therefor we'll add
        this line to the <code>register</code> method of the <code>AppServiceProvider</code>:
    </p>
    <p>
        <code>$this->app->bind(WordProvider::class, fn() => new TextWordProvider(resource_path('game/words.txt')));</code>
    </p>
    <p>
        You can now play the game by calling <code>php artisan play</code>. In the <a href="{{ route('blog.chapter-2') }}">next chapter</a> we're going to add a proper UI to our
        game, so we can play it in the browser.
    </p>
@endsection
