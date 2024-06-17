@extends('admin::layouts.master')

@section('title', 'Dashboard: User Management')

@push('css')
    <style>
        .lds-ellipsis {
            /* change color here */
            color: #1c4c5b
        }
        .lds-ellipsis,
        .lds-ellipsis div {
            box-sizing: border-box;
        }
        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-ellipsis div {
            position: absolute;
            top: 33.33333px;
            width: 13.33333px;
            height: 13.33333px;
            border-radius: 50%;
            background: currentColor;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }
        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }

    </style>

@endpush

@push('js')
    <script>

        function initAdminUsersEdit() {
            return {
                page_loaded: false,
                init() {
                    this.page_loaded = true;
                },
            };
        }

    </script>
@endpush

@section('content')

    <header>
        <!-- Page Heading -->
        {{-- @livewire('navigation') --}}
    </header>

    <div id="main"  >
        <div class="pt-12 flex">

            <x-admin::sidebar/>
            <div x-data="initAdminUsersEdit" class="w-full h-full p-4 m-8 ">
                <div class="text-xl font-bold p-3">
                    User Management : {{ $user['nickname'] }}
                </div>
                <div class="flex items-center w-full mr-8"  >
                    <div x-show="!page_loaded">
                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <div class="-my-2 py-2 w-1/2 overflow-y-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8" x-cloak>
                        <div class="flex flex-col flex-grow-0">
                            <div class="flex flex-col md:flex-row">
                                <div class="w-full md:w-1/3   text-green text-center py-4">
                                    <div class="flex flex-row">
                                        <div>Profile : </div>
                                        @if ($user['has_profile'])
                                        <div class="whitespace-no-wrap text-blue-900 leading-5">
                                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                <span class="relative  ">active</span>
                                            </span>
                                        </div>
                                        @else
                                        <div class="whitespace-no-wrap text-blue-900 leading-5">
                                                            <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                                            <span class="relative  ">not active</span>
                                                        </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 bg-gray-300 text-green text-center py-4">
                                    middle Column
                                </div>
                                <div class="w-full md:w-1/3 bg-yellow-400 text-green text-center py-4">
                                    <div @click="viewUserDetails(user.id)" class="text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">View Details</div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
