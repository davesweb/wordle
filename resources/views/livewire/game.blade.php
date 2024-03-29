<div x-data="{init: document.addEventListener('keyup', e => window.Livewire.emit('addLetter', e.key), false)}">
    <div id="game-data" class="flex justify-between mb-2 items-center text-slate-900 dark:text-slate-100">
        <div class="w-1/3 relative">
            Score: {{ $score }}
            @if($scoreMutation !== 0)
                <div class="animated-score absolute right-0 -bottom-1/2 text-2xl text-orange-600 font-bold">{{ $scoreMutation > 0 ? '+' : '' }}{{ $scoreMutation }}</div>
            @endif
        </div>
        <div class="w-1/3 text-center">
            <button title="New word" class="bg-slate-200 text-slate-900 border-slate-300 hover:bg-slate-300 hover:border-slate-400 dark:bg-slate-500 dark:text-slate-100 dark:border-slate-400 dark:border-2 dark:hover:bg-slate-600 dark:hover:border-slate-500 border p-2 rounded text-sm" wire:click="newGame()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
            </button>
            <button title="Help" class="bg-slate-200 text-slate-900 border-slate-300 hover:bg-slate-300 hover:border-slate-400 dark:bg-slate-500 dark:text-slate-100 dark:border-slate-400 dark:border-2 dark:hover:bg-slate-600 dark:hover:border-slate-500 border p-2 rounded text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="flex w-1/3 place-content-end relative">
            @for($i = 0; $i < $lives; $i++)
                <x-heart />
            @endfor
            @if($livesMutation !== 0)
                <div class="animated-score absolute right-0 -bottom-1/2 text-2xl text-red-500 font-bold">{{ $livesMutation > 0 ? '+' : '' }}{{ $livesMutation }}</div>
            @endif
        </div>
    </div>
    <div id="guesses" class="guesses mb-5">
        @foreach($this->guesses as $previousGuess => $result)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                @foreach(str_split($previousGuess) as $index => $letter)
                    <div class="letter border rounded border-slate-400 dark:border-slate-600 dark:border-2 dark:text-slate-100 p-4 text-center font-medium text-lg guess @if($result[$index] === 1 || $result[$index] === \App\Game\Enums\Result::Correct) bg-green-500 text-white @endif @if($result[$index] === 2 || $result[$index] === \App\Game\Enums\Result::InWord) bg-yellow-500 text-white @endif @if($result[$index] === 0 || $result[$index] === \App\Game\Enums\Result::Incorrect) bg-slate-400 dark:bg-slate-900 text-white @endif">{{ str($letter)->upper() }}</div>
                @endforeach
            </div>
        @endforeach
        @if(count($this->guesses) < $this->maxTries)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                @foreach($this->letters as $letterIndex => $letter)
                    <div class="letter border rounded border-slate-400 dark:border-slate-600 dark:border-2 dark:text-slate-100 p-4 text-center font-medium text-lg guess @if($this->getBoughtLetter($letterIndex) !== null && $this->getBoughtLetter($letterIndex) === $letter) bg-green-500 text-white @endif">{{ str($letter)->upper() }}</div>
                @endforeach
                @for($i = count($this->letters); $i < 5; $i++)
                    @if ($this->getBoughtLetter($i) !== null)
                        <div class="letter border rounded border-slate-400 dark:border-slate-600 dark:border-2 dark:text-slate-100 p-4 text-center font-medium text-lg guess bg-green-500 text-white">{{ $this->getBoughtLetter($i) }}</div>
                    @else
                        <div class="letter border rounded border-slate-400 dark:border-slate-600 dark:border-2 dark:text-slate-100 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                    @endif
                @endfor
            </div>
        @endif
        @for($i = 0; $i < $this->maxTries - count($this->guesses) - 1; $i++)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                @for($j = 0; $j < 5; $j++)
                    <div class="letter border rounded border-slate-300 dark:border-slate-700 dark:border-2 dark:text-slate-100 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                @endfor
            </div>
        @endfor
    </div>
    <div id="messages" class="messages {{ $error || $success ? 'mb-5' : '' }} flex justify-center">
        @if($error)
            <div class="border border-red-600 bg-red-200 text-red-600 p-2 rounded dark:border-red-600 dark:bg-red-800 dark:text-red-100">
                {{ $error }}
            </div>
        @endif
        @if($success)
            <div class="border border-green-600 bg-green-200 text-green-600 p-2 rounded dark:border-green-600 dark:bg-green-800 dark:text-green-100">
                {{ $success }}
            </div>
        @endif
    </div>
    @if($this->canBuyTurn())
        <div class="text-center mb-5">
            <button wire:click="buyTurn()" class="letter border rounded p-2 font-medium grow mr-1 dark:border-2 border-slate-300 bg-slate-300 dark:bg-slate-600 dark:text-slate-100 dark:border-slate-500 disabled:dark:text-slate-400 disabled:cursor-not-allowed">Buy an extra turn</button>
        </div>
    @endif
    <div class="keyboard mb-5">
        @foreach($this->keyboard as $row => $letters)
            <div class="row flex mb-2">
                @foreach($letters as $letter)
                    <button class="letter border rounded py-2 text-sm font-medium grow mr-1 dark:border-2 @if($this->letterUsed($letter)) dark:bg-slate-700 dark:border-slate-700 dark:text-slate-100 bg-slate-400 text-slate-900 @else border-slate-300 bg-slate-300 dark:bg-slate-600 dark:text-slate-100 dark:border-slate-500 @endif" wire:click="addLetter('{{ $letter }}')">{{ $letter }}</button>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="options mb-5 flex justify-center items-center">
        <div>
            <button wire:click="buyLetter()" class="letter border rounded p-2 font-medium grow mr-1 dark:border-2 border-slate-300 bg-slate-300 dark:bg-slate-600 dark:text-slate-100 dark:border-slate-500 disabled:dark:text-slate-400 disabled:cursor-not-allowed" {{ !$this->canBuyLetter ? 'disabled' : '' }}>Buy a letter</button>
        </div>
    </div>
    <script>console.log('{{ $this->word }}');</script>
</div>
