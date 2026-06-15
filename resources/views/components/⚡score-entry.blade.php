<?php

use App\Models\TennisMatch;
use App\Models\Tournament;
use App\Models\User;
use App\Services\EloService;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {


    public Tournament $tournament;
    public $players;

    #[Validate('required|exists:users,id|different:player2_id')]
    public $player1_id = '';

    #[Validate('required|exists:users,id')]
    public $player2_id = '';

    #[Validate('required|exists:users,id')]
    public $winner_id = '';

    #[Validate('required|string|max:50')]
    public $score = '';

    public $isSaved = false;

    public function mount(Tournament $tournament,$players){
        $this->tournament = $tournament;
        $this->players= $players;
    }


    public function saveScore()
    {

        $this->validate();

        if($this->winner_id!=$this->player1_id && $this->winner_id!=$this->player2_id){
            $this->addError('winner_id', __('messages.winner_error'));
            return;
        }

        $match = TennisMatch::create([
            'tournament_id' => $this->tournament->id,
            'player1_id' => $this->player1_id,
            'player2_id' => $this->player2_id,
            'winner_id' => $this->winner_id,
            'score' => $this->score,
        ]);

        $player1 = User::find($this->player1_id);
        $player2 = User::find($this->player2_id);
        $eloService = app(EloService::class);
        $eloService->calculateAndApply($player1, $player2, $this->winner_id, $match);
        $this->isSaved = true;

        $this->dispatch('score-saved');
    }

};
?>
<div>
    @if($isSaved)
        <div class="flex items-center space-x-2 text-green-600 rounded-md">
            <span class="text-sm font-bold">
                {{__('messages.score_saved')}} : {{$score}}
            </span>
        </div>
    @endif

        <form wire:submit="saveScore">
            <div class="mb-4">
                <label class="block text-sm font-medium">1. Spēlētājs</label>
                <select wire:model="player1_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">-- Izvēlies spēlētāju --</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }} (ELO: {{ $player->elo_rating }})</option>
                    @endforeach
                </select>
                @error('player1_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">2. Spēlētājs</label>
                <select wire:model="player2_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">-- Izvēlies spēlētāju --</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }} (ELO: {{ $player->elo_rating }})</option>
                    @endforeach
                </select>
                @error('player2_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-green-700">Uzvarētājs</label>
                <select wire:model="winner_id" class="mt-1 block w-full border-green-500 rounded-md" required>
                    <option value="">-- Kurš uzvarēja? --</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }}</option>
                    @endforeach
                </select>
                @error('winner_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium">Rezultāts (Sets)</label>
                <input type="text" wire:model="score" placeholder="Piemēram: 6-4, 3-6, 7-5" class="mt-1 block w-full border-gray-300 rounded-md" required>
                @error('score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit">Save</button>
        </form>

</div>
