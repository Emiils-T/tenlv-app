<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All players') }}
        </h2>
    </x-slot>

    <div class="w-full flex justify-center">

        <div>
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white">
                <div>
                    <table>
                        <thead class="bg-black text-white">
                        <tr>
                            <th class="border-b p-3">Vārds</th>
                            <th class="border-b p-3">E-pasts</th>
                            <th class="border-b p-3">ELO Reitings</th>
                            <th class="border-b p-3">Statuss</th>
                            <th class="border-b p-3">Loma</th>
                            <th class="border-b p-3 text-center">Darbības</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 {{ $user->trashed() ? 'bg-red-50' : '' }}">
                                <td class="p-3 font-medium {{ $user->trashed() ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                    {{ $user->name }}
                                </td>
                                <td class="p-3 text-gray-500">{{ $user->email }}</td>
                                <td class="p-3 font-mono">{{ $user->elo_rating }}</td>

                                <td class="p-3">
                                    @if($user->trashed())
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-200 text-red-800">Bloķēts</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-green-200 text-green-800">Aktīvs</span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <form method="POST" action="{{ route('admin.users.role', $user->id) }}"
                                          class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role"
                                                class="text-sm border-gray-300 rounded-md py-1 pr-8 {{ $user->trashed() ? 'opacity-50' : '' }}" {{ $user->trashed() || $user->id === Auth::id() ? 'disabled' : '' }}>
                                            <option value="player" {{ $user->role === 'player' ? 'selected' : '' }}>
                                                Spēlētājs
                                            </option>
                                            <option
                                                value="organiser" {{ $user->role === 'organiser' ? 'selected' : '' }}>
                                                Organizators
                                            </option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>
                                        @if(!$user->trashed() && $user->id !== Auth::id())
                                            <button type="submit"
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs px-2 py-1 rounded">
                                                Saglabāt
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                <td class="p-3 text-center">
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.toggleBlock', $user->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->trashed())
                                                <button type="submit"
                                                        class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                    Atbloķēt
                                                </button>
                                            @else
                                                <button type="submit"
                                                        class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                    Bloķēt
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">{{__('messages.you')}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
