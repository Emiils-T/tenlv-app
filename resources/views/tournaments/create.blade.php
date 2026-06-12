<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Izveidot jaunu turnīru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('tournaments.store') }}">
                        @csrf <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Turnīra nosaukums</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Norises datums</label>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                            @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="court_id" class="block text-sm font-medium text-gray-700">Norises vieta (Korts)</label>
                            <select name="court_id" id="court_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled {{ old('court_id') ? '' : 'selected' }}>Izvēlies kortu...</option>

                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                        {{ $court->address }} | {{ $court->name }} (Segums: {{ $court->surface_type }})
                                    </option>
                                @endforeach
                            </select>

                            @error('court_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tournaments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Atcelt
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                                Saglabāt un izveidot
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
