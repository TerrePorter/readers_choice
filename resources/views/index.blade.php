@extends('layouts.app')

@push('css')
    <style>


    </style>
@endpush

@push('js')
    <script>
        function initFrontPage() {
            return {
                votesNeeded: 10,
                votesTaken: 0,
                page_loaded: false,
                init() {
                    this.page_loaded = true;
                }
            };
        }
    </script>
@endpush

@section('content')
<div x-data="initFrontPage">
    <div>
        <img src="{{ asset('assets/images/banner.png') }}" class="w-full" >
    </div>
    <div class="h-6 mt-12 bg-yellow-200 sm:bg-purple-300 md:bg-green-300 lg:bg-purple-300 xl:bg-yellow-100 2xl:bg-gray-200">
        <span class="sm:hidden">TINY</span>
        <span class="hidden sm:block md:hidden">SMALL</span>
        <span class="hidden md:block lg:hidden">MEDIUM</span>
        <span class="hidden lg:block xl:hidden">LARGE</span>
        <span class="hidden xl:block  2xl:hidden">EX-LARGE</span>
        <span class="hidden 2xl:block">EX-EX-LARGE</span>
    </div>
    <div class="flex flex-row mt-12">
        <div class="md:text-lg md:mt-5">
            <p style="padding: 10px;">Once a year, loyal readers decide the winners of the
                Readers' Choice Awards for the best dining, entertainment, businesses and services.</p>
            <p style="padding: 10px;">To vote and get a chance to win one of two $50 gift cards, place the name of your favorite business in the categories below. You
                must vote in a minimum of 10 categories.</p>
            <p style="padding: 10px;">Vote as many times as you want! <b>Nominations must be submitted by May 12.</b></p>
        </div>
        <div class="w-full max-w-40">
            <img src="{{ asset('assets/images/medal.png') }}" class="w-full" >
        </div>
    </div>

    <div class="flex mx-auto w-full md:w-3/4 xl:w-1/2 mx-auto " >
        <div class="text-center bg-gradient-to-b from-orange-50 to-orange-200 p-12">
            <p style="margin:0"> Improperly completed ballots will be eliminated. <em>Employees and family members are not
                    eligible for the drawings.</em></p>
        </div>
    </div>

    <div class="sticky top-0 bg-gradient-to-b from-brown-50 to-brown-200 mt-6 p-4 mx-auto w-full md:w-3/4 xl:w-1/2 " >
        <div class="text-center ">
            <div x-show="!page_loaded">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </div>
            <div x-show="votesNeeded != votesTaken" x-cloak>
                    <p style="margin-bottom: 0">Ballot needs <span x-text="votesNeeded - votesTaken" class="text-xl text-rose-500 font-bold"></span> more nominations to meet the minimum requirement.</p>
            </div>
            <div x-show="votesNeeded == votesTaken" x-cloak>
                <div class="text-center">
                    <div class="btn btn-success btn-sm">Go to Ballot Review &gt;&gt;</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex mx-auto w-full md:w-3/4 xl:w-1/2 mt-6" >
        <div class="border-t border-2 border-t-blue-700 p-3 w-full text-2xl text-center bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-blue-100 via-blue-300 to-blue-500">
            Business and Services
        </div>
    </div>



</div>
@endsection
