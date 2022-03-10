@extends('blog.layout')

@section('content')
    <h1>Algorithms</h1>
    <p>
        As you might remember, one of the reason why we've created this game, is because I was curious about strategy.
        Can we for instance use a set list of words, which contains as many different letters as possible, to get
        as much of the letters of the actual word as soon as possible? And if so, what is this list and how many words
        do you need to win the game as often as possible? And finally, are there other tactics we can try?
    </p>
    <p>
        We could of course try and answer these questions by playing the game a bunch of times, but that would take
        forever and probably won't give you any accurate results. So we're going to create a player in our app that
        can play the game for us. We'll give our algorithms we want to try to this player, and let it run a couple
        of thousand times. After that we'll compare the results of different algorithms and hopefully find out what
        the most optimal strategy is, and what words are best to start with.
    </p>
@endsection