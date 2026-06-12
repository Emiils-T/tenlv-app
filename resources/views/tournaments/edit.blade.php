<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rediģēt turnīru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('tournaments.update',$tournament->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">

                            <label for="name" class="block text-sm font-medium text-gray-700">Turnīra nosaukums</label>
                            <input type="text" name="name" id="name" value="{{ old('name',$tournament->name) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Norises datums</label>
                            <input type="date" name="date" id="date" value="{{ old('date',$tournament->date) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                            @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="court_id" class="block text-sm font-medium text-gray-700">Norises vieta (Korts)</label>
                            <select name="court_id" id="court_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled {{ old('court_id',$tournament->court_id) ? '' : 'selected' }}>Izvēlies kortu...</option>

                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                        {{ $court->address }} |
                                        {{ $court->name }} (Segums: {{ $court->surface_type }})
                                    </option>
                                @endforeach
                            </select>

                            @error('court_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status">
                                <option value="open">Open</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="finished">Finished</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tournaments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Atcelt
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                                Saglabāt izmaiņas
                            </button>
                        </div>
                        @if($errors->any())
                            <div class="mb-4 text-red-600">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
