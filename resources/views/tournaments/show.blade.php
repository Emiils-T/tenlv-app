<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tournament') }}
        </h2>
    </x-slot>
    <div class="flex flex-row items-center justify-center">

        <div class="bg-neutral-primary-soft block max-w-md p-6 border border-default rounded-base shadow-xs">
            <a href="#">
                <h5 class="mt-6 mb-2 text-2xl font-semibold tracking-tight text-heading">{{ $tournament->name }}</h5>
            </a>
            <h3 class="text-lg font-bold mt-6">{{__('messages.address')}}</h3>
            <p>{{$tournament->court->address}}</p>
            <p>{{__('messages.organiser')}}: {{$tournament->organiser->name}}</p>
            <h3 class="text-lg font-bold mt-8 mb-4">{{__('messages.participants')}}</h3>
            <ul class="divide-y divide-gray-200">
                @forelse($tournament->players as $player)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <a href="{{route('players.show',$player->id)}}"
                               class="font-medium text-gray-900">{{ $player->name }}</a>
                            <span class="ml-2 text-xs text-gray-500">(ELO: {{ $player->elo_rating }})</span>
                        </div>

                        <div class="flex items-center space-x-4">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $player->pivot->status === 'accepted' ? 'bg-green-100 text-green-800' : ($player->pivot->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ $player->pivot->status }}
                </span>

                            @if(Auth::id() === $tournament->organiser_id && $player->pivot->status === 'pending')
                                <form method="POST"
                                      action="{{ route('tournaments.registration.update', [$tournament->id, $player->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit"
                                            class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                        {{__('messages.approve')}}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('tournaments.registration.update', [$tournament->id, $player->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit"
                                            class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                        {{__('messages.reject')}}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <p class="text-gray-500 text-sm">{{__('messages.no_applications')}}</p>
                @endforelse
            </ul>
            @auth
                @if($tournament->status === 'open' && !$tournament->players->contains($user))
                    <form method="POST" action="{{ route('tournaments.register', $tournament->id) }}" class="mt-4">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700">
                            {{__('messages.apply')}}
                        </button>
                    </form>
                @elseif($tournament->players->contains($user))
                    @php
                        // Iegūstam konkrētā spēlētāja statusu šajā turnīrā no pivot tabulas
                        $playerPivot = $tournament->players->find($user)->pivot;
                    @endphp
                    <p class="mt-4 text-sm font-medium">
                        {{__('messages.your_app_status')}}:
                        <span class="px-2 py-1 rounded text-xs
                {{ $playerPivot->status === 'accepted' ? 'bg-green-100 text-green-800' : ($playerPivot->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ $playerPivot->status }}
            </span>
                    </p>
                @endif
            @endauth

            @can('update',$tournament)
                <a href="{{route('tournaments.edit',$tournament)}}"
                   class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    {{__('messages.edit_tournament')}}
                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 12H5m14 0-4 4m4-4-4-4"/>
                    </svg>
                </a>
            @endcan
            <a href="{{route('tennisMatches.create',$tournament)}}"
               class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                {{__('messages.tennis_matches')}}
                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 12H5m14 0-4 4m4-4-4-4"/>
                </svg>
            </a>
            @can('delete', $tournament)
                <form method="POST" action="{{ route('tournaments.destroy', $tournament) }}" class="inline-block"
                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        {{__('messages.delete')}}

                        <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 12H5m14 0-4 4m4-4-4-4"/>
                        </svg>
                    </button>
                </form>
            @endcan
            @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <livewire:score-table :tournament="$tournament"/>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <livewire:score-entry :tournament="$tournament" :players="$players"/>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
