<div x-data="{init: document.addEventListener('keyup', e => window.Livewire.emit('addLetter', e.key), false)}">
    <div id="guesses" class="guesses mb-5">
        @foreach($this->guesses as $previousGuess => $result)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                @foreach(str_split($previousGuess) as $index => $letter)
                    <div class="letter border rounded border-slate-400 p-4 text-center font-medium text-lg guess @if($result[$index] === 1 || $result[$index] === \App\Game\Enums\Result::Correct) bg-green-500 text-white @endif @if($result[$index] === 2 || $result[$index] === \App\Game\Enums\Result::InWord) bg-yellow-500 text-white @endif @if($result[$index] === 0 || $result[$index] === \App\Game\Enums\Result::Incorrect) bg-slate-400 text-white @endif">{{ str($letter)->upper() }}</div>
                @endforeach
            </div>
        @endforeach
        @if(count($this->guesses) < 6)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                @foreach($this->letters as $letter)
                    <div class="letter border rounded border-slate-400 p-4 text-center font-medium text-lg guess">{{ str($letter)->upper() }}</div>
                @endforeach
                @for($i = 0; $i < 5 - count($this->letters); $i++)
                        <div class="letter border rounded border-slate-400 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                @endfor
            </div>
        @endif
        @for($i = 0; $i < $this->maxTries - count($this->guesses) - 1; $i++)
            <div class="row grid grid-cols-5 gap-5 mb-3">
                <div class="letter border rounded border-slate-300 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                <div class="letter border rounded border-slate-300 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                <div class="letter border rounded border-slate-300 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                <div class="letter border rounded border-slate-300 p-4 text-center font-medium text-lg guess">&nbsp;</div>
                <div class="letter border rounded border-slate-300 p-4 text-center font-medium text-lg guess">&nbsp;</div>
            </div>
        @endfor
    </div>
    <div id="messages" class="messages mb-5 flex justify-center">
        <input wire:model="error" /> {{ $success }}
    </div>
    <div class="keyboard mb-5">
        @foreach($this->keyboard as $row => $letters)
            <div class="row flex mb-2">
                @foreach($letters as $letter)
                    <button class="letter border rounded border-slate-300 bg-slate-300 py-2 text-sm font-medium grow mr-1" wire:click="addLetter('{{ $letter }}')">{{ $letter }}</button>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
