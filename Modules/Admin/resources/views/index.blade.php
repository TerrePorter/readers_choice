@extends('admin::layouts.master')

@section('content')
    <header>
        <!-- Page Heading -->
        {{-- @livewire('navigation') --}}
    </header>

    <div id="main" >
        <div class="pt-12 flex">
            <div class="flex flex-col px-4 py-4 overflow-y-auto border-b border-r h-screen md:w-64 ">
                <div class="flex flex-col justify-between mt-6">
                    <x-admin::sidebar/>
                </div>
            </div>
            <div class="w-full h-full p-4 m-8 overflow-y-auto">
                <div class="text-xl font-bold p-3">
                    Dashboard
                </div>
                <div class="flex items-center justify-center p-16 mr-8 border-4 border-dotted lg:p-40">
                    Content...
                </div>
            </div>
        </div>
    </div>
@stop


