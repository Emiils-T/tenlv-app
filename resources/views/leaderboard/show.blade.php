<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.leaderboard') }}
        </h2>
    </x-slot>
    <div>
        <table class="w-full text-sm text-center rtl:text-center text-body">
            <thead>
            <tr class="bg-black text-white">
                <th>{{__('messages.players')}}</th>
                <th>ELO</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="hover:bg-gray-50 text-base">
                    <td><a href="{{route('players.show',$user)}}">{{$user->name}}</a></td>
                    <td>{{$user->elo_rating}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>


</x-app-layout>
