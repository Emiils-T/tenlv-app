<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pievienot rezultātu: {{ $tournament->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('tennisMatches.store', $tournament->id) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">1. Spēlētājs</label>
                        <select name="player1_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">-- Izvēlies spēlētāju --</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }} (ELO: {{ $player->elo_rating }})</option>
                            @endforeach
                        </select>
                        @error('player1_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">2. Spēlētājs</label>
                        <select name="player2_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">-- Izvēlies spēlētāju --</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }} (ELO: {{ $player->elo_rating }})</option>
                            @endforeach
                        </select>
                        @error('player2_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-green-700">Uzvarētājs</label>
                        <select name="winner_id" class="mt-1 block w-full border-green-500 rounded-md" required>
                            <option value="">-- Kurš uzvarēja? --</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }}</option>
                            @endforeach
                        </select>
                        @error('winner_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium">Rezultāts (Sets)</label>
                        <input type="text" name="score" placeholder="Piemēram: 6-4, 3-6, 7-5" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">
                        Saglabāt maču
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
