<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__('Player Info')}}
        </h2>
    </x-slot>
    <div>
        <div>
            <div class="flex w-1/2 items-center justify-between text-center">
                <div>
                    <h3 class="text-2xl"> {{$user->name}}</h3>
                </div>
                <div class="text-right">
                    <p>Pašreizējais ELO</p>
                    <p>{{$user->elo_rating}}</p>
                </div>

            </div>
            <div>
                <div class="sm:rounded-lg">
                    <h4>ELO Reitinga vēsture</h4>
                </div>
                <div class="bg-neutral-primary-soft block  p-6 border border-default rounded-base shadow-xs">
                    <table>
                        <thead>
                        <tr>
                            <th>Datums</th>
                            <th>Pretinieks</th>
                            <th>Rezultāts</th>
                            <th>ELO Pirms</th>
                            <th>Izmaiņa</th>
                            <th>Elo Pēc</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($user->eloHistory as $history)
                            @php
                                $match = $history->match;
                                $opponent = $match->player1_id == $user->id ? $match->player2 : $match->player1;
                                $diff = $history->elo_after - $history->elo_before;
                                $win = $match->winner_id == $user->id;
                            @endphp

                            <tr>
                                <td class="px-6 py-7">
                                    {{$history->created_at->format('d.m.Y H:i')}}
                                </td>
                                <td class="px-6 py-7">
                                    <a href="{{route('players.show',$opponent->id)}}">{{$opponent->name}}</a>
                                </td>
                                <td class="px-6 py-7">
                                    {{ $win ? 'W':'L' }} ({{$match->score}})
                                </td>
                                <td class="px-6 py-7">
                                    {{ $history->elo_before }}
                                </td>
                                <td class=" px-6 py-7 {{ $diff >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $diff > 0 ? '+'.$diff : $diff }}
                                </td>

                                <td  class="px-6 py-7">
                                    {{ $history->elo_after }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>
                                    Vel nav neviens reģistrēts ELO ieraksts
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
