<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.all_tournaments') }}
        </h2>
    </x-slot>
    <div class="grid md:grid-cols-3 sm:grid-cols-1 lg:grid-cols-5 justify-items-center ">
        @foreach($tournaments as $tournament)
            <div class="sm:text-center md:text-start bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                <h5 class="mb-3 text-2xl font-semibold tracking-tight text-heading leading-8">{{$tournament->name}}</h5>
                <p class="text-body mb-6">{{$tournament->date}} -  {{ $tournament->status }}</p>
                <a href="{{route('tournaments.show',$tournament)}}" class="inline-flex items-center text-black bg-brand box-border border
                border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    {{__('messages.more_info')}}
                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                </a>
            </div>

        @endforeach
    </div>




</x-app-layout>
