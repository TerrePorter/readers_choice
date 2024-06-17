@extends('admin::layouts.master')

@section('title', 'Dashboard: User Management')

@push('css')
@endpush

@push('js')
    <script>


    function initAdminUsers() {

        return {

            page: 1,
            search_text: '',
            users: '',
            data_loaded: false,
            page_loaded: false,
            perPageSelection: 5,
            showPerPageSelection: false,

            init() {
                this.page_loaded = true;

                console.log('init');

                this.sendAjaxRequest();
            },

            viewUserDetails(user_id) {
                window.location = '{{ route('admin.users.index')  }}' + '/edit/' + user_id;
            },

            sendAjaxRequest() {
                this.handleDataSend('fetchUserRecordsList',
                    'handleUserRecordsList',
                    {
                        per_page: this.perPageSelection,
                        page: this.page,
                        search_text: this.search_text
                    });
            },

            handleSearch() {
                if (this.data_loaded) {
                    this.data_loaded = false;
                    this.sendAjaxRequest();
                } else {
                    alert('data not loaded');
                }
            },

            handlePaginationChangePage(pageUrl) {
                query = pageUrl.split("?")[1];
                let params = new URLSearchParams(query);
                if (params.has("page")) {
                    console.log("loading page : " + params.get("page"));
                    this.data_loaded = false;
                    this.page = params.get("page");
                    this.sendAjaxRequest();
                } else {
                    alert('requested page url appears to be invalid');
                }
            },

            togglePerPageSelection() {
                this.showPerPageSelection = !this.showPerPageSelection;
            },

            handlePerPageSelection(val) {
                this.perPageSelection = val;
                this.togglePerPageSelection();
                if (this.data_loaded) {
                    this.data_loaded = false;
                    this.sendAjaxRequest();
                } else {
                    alert('data not finished loading');
                }
            },

            handleUserRecordsList(jsonData) {
                this.data_loaded = true;
                this.users = jsonData['data'];
                document.getElementById('pagination_links').innerHTML = jsonData['links'];
            },

            async handleDataSend(dsCommand, dsCallback, dsData) {
                console.log('handleDataSend:');
                console.log(dsCommand);
                console.log(dsCallback);
                console.log(dsData);

                await (await fetch('{{ route('admin.alpine_data_send') }}', {
                        method: 'POST',
                        body: JSON.stringify({
                            command: dsCommand,
                            data: dsData
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    }).then((response) => response.json()).then((json) => this[dsCallback](json))
                )
            },


        }

    }

    </script>
@endpush

@section('content')

    <header>
        <!-- Page Heading -->
        {{-- @livewire('navigation') --}}
    </header>

    <div id="main" >
        <div class="pt-12 flex">

            <x-admin::sidebar/>
            <div x-data="initAdminUsers" class="w-full h-full p-4 m-8 ">
                <div class="text-xl font-bold p-3">
                    User Management
                </div>
                <div class="flex items-center w-full mr-8"  >
                    <div x-show="!page_loaded">
                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8" x-cloak>

                        <div class="align-middle rounded-tl-lg rounded-tr-lg inline-block w-full py-4  bg-white  px-12">
                            <div class="flex justify-between">
                                <div class="border rounded w-7/12 px-2 lg:px-6 h-12 bg-transparent">
                                    <div class="flex flex-wrap items-stretch   h-full mb-6 relative">
                                        <div class="flex">
                                                        <span class="flex items-center leading-normal bg-transparent rounded rounded-r-none border border-r-0 border-none lg:px-3 py-2 whitespace-no-wrap text-grey-dark text-sm">
                                                            <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                        </div>
                                        <input  @keyup.enter="handleSearch" x-model="search_text" type="text" class="flex-shrink flex-grow flex-auto leading-normal tracking-wide w-px flex-1 border border-none border-l-0 rounded rounded-l-none px-3 relative focus:outline-none text-xxs lg:text-xs lg:text-base text-gray-500 font-thin" placeholder="Search">
                                    </div>
                                </div>


                                <div class="flex flex-row h-full mb-6 relative">
                                    <div >

                                        <button class="text-sm text-white bg-blue-800 px-4 py-2 border-0 rounded-md outline-none hover:bg-blue-900"
                                                x-on:click="showPerPageSelection = ! showPerPageSelection">
                                            <span x-text="perPageSelection"></span>
                                            <svg data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="m19.5 8.25-7.5 7.5-7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>

                                        <div class="relative">
                                            <div class="bg-gray-800 rounded-md p-3 min-w-[220px] top-1 w-full absolute z-10" x-show="showPerPageSelection"
                                                 x-cloak
                                                 @click.away="showPerPageSelection = false" x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95">
                                                <ul>
                                                    @for($i = 1; $i < 10; $i++)
                                                        <li x-show="perPageSelection != {{ $i * 5 }}" @click="handlePerPageSelection('{{ $i * 5 }}')" title="{{ $i * 5 }} Results Per Page"
                                                            class="text-white"
                                                            role="option">
                                                            <span class="font-normal truncate">{{ $i * 5 }}</span>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="align-middle inline-block min-w-full  overflow-hidden bg-white  pt-3 ">
                            <table class="w-full">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Fullname</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Email</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Last Login</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Created At</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300"></th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr x-show="!data_loaded">
                                    <td colspan="7" class="align-middle text-center">
                                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                    </td>
                                </tr>

                                <template x-for="user in users" :key="user.id">
                                    <tr x-show="data_loaded">
                                        <td  class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm leading-5 text-gray-800"><span x-text="user.id"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                            <div class="text-sm leading-5 text-blue-900"><span x-text="user.nickname"></span></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5"><span x-text="user.email"></span></td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-blue-900 text-sm leading-5" x-text="user.last_login">September 12</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-blue-900 text-sm leading-5" x-text="user.created_at">September 12</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
                                            <div @click="viewUserDetails(user.id)" class="text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">View Details</div>
                                        </td>
                                </template>

                                </tbody>
                            </table>
                             <div id="pagination_links"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
