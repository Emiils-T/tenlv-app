<?php

use App\Models\Tournament;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public Tournament $tournament;

    #[On('score-saved')]
    public function resfreshTable()
    {
        $this->tournament->unsetRelation('tennisMatches');
    }

    public function with()
    {
        $this->tournament->load(['players', 'tennisMatches']);
        $players = $this->tournament->players;
        $acceptedPlayers = $players->where('pivot.status', 'accepted');


        $standings = [];
        foreach ($acceptedPlayers as $player) {
            $standings[$player->id] = [
                'player' => $player,
                'wins' => 0,
                'losses' => 0,
            ];
        };
        foreach ($this->tournament->tennisMatches as $match) {
            $p1 = $match->player1_id;
            $p2 = $match->player2_id;

            $winner_id = $match->winner_id;

            if ($winner_id == $p1) {
                $standings[$p1]['wins']++;
                $standings[$p2]['losses']++;
            } elseif ($winner_id == $p2) {
                $standings[$p2]['wins']++;
                $standings[$p1]['losses']++;
            };
        }

        uasort($standings, fn($a, $b) => $b['wins'] <=> $a['wins']);
        return ['standings' => $standings];
    }
};
?>

<div>
    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-bold mb-4 text-gray-800">{{__('messages.tournament_table')}}</h3>

        <table class="w-full text-sm text-left border-collapse">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="border p-2">{{__('messages.position')}}</th>
                <th class="border p-2">{{__('messages.player')}}</th>
                <th class="border p-2 text-center">{{__('messages.wins')}}</th>
                <th class="border p-2 text-center">{{__('messages.losses')}}</th>
            </tr>
            </thead>
            <tbody>
            @php $place = 1; @endphp
            @foreach($standings as $row)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 font-bold text-blue-600">{{ $place++ }}</td>
                    <td class="border p-2 font-medium">{{ $row['player']->name }}</td>
                    <td class="border p-2 text-center text-green-600 font-semibold">{{ $row['wins'] }}</td>
                    <td class="border p-2 text-center text-red-500">{{ $row['losses'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-neutral-primary-soft block  p-6 border border-default rounded-base shadow-xs">
        <table class="w-full text-sm text-center rtl:text-center text-body">
            <thead>
            <tr>
                <th>{{__('messages.players')}}</th>
                <th>{{__('messages.result')}}</th>
                <th>{{__('messages.date')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tournament->tennisMatches as $match)
                <tr>
                    <td>{{$match->player1->name}} | {{$match->player2->name}}</td>
                    <td>{{$match->score}}</td>
                    <td>{{$match->updated_at}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

