@extends('admin::layouts.master')

@section('title', 'Dashboard: Campaign Management')

@push('css')
@endpush

@push('js')
    <script>

        function initAdminCampaigns() {
            return {

                page: 1,
                search_text: '',
                campaigns: '',
                data_loaded: false,
                page_loaded: false,
                perPageSelection: 5,
                showPerPageSelection: false,
                showSearchTextbox: true,
                showSearchPageCountDropdown: true,

                init() {
                    this.page_loaded = true;

                    console.log('init');



                    this.sendAjaxRequest();
                },

                deleteCampaign(campaign_id) {

                    //TODO:: add in confirmation dialog box


                    this.showSearchTextbox = false;
                    this.showSearchPageCountDropdown = false;
                    this.data_loaded = false,

                    this.handleDataSend('deleteCampaignsRecordById',
                        'handleDeleteCampaignsRecordById',
                        {
                            campaign_id: campaign_id
                        });
                },

                handleDeleteCampaignsRecordById(jsonData) {

                    //TODO:: add in info dialog box
                    alert(json.message);

                    if (jsonData.status) {
                        this.sendAjaxRequest();
                    }
                },

                viewCampaignCategoryDetails(campaign_id) {
                    window.location = '{{ route('admin.campaigns.index')  }}' + '/edit_categories/' + campaign_id;
                },

                viewCampaignDetails(campaign_id) {


                    this.showSearchTextbox = false;
                    this.showSearchPageCountDropdown = false;

                    this.$dispatch('create-my-dialog-modal', {
                        'dialog_key': 'CreateCampaign',
                        'dialog_modal': {
                            'handleCancelButton': 'handleDefaultModalCloseAction',
                            'submitButtonCaption': 'Update',
                            'dispatch': {
                                    'name': 'update_campaign_id',
                                    'data': campaign_id
                            }
                        }
                    });

                },

                showCreateCampaignModal() {

                    this.showSearchTextbox = false;
                    this.showSearchPageCountDropdown = false;

                    this.$dispatch('create-my-dialog-modal', {
                        'dialog_key': 'CreateCampaign',
                        'dialog_modal': {
                            'submitButtonCaption': 'Create',
                            'handleCancelButton': 'handleDefaultModalCloseAction'
                        }
                    });
                },

                sendAjaxRequest() {
                    this.handleDataSend('fetchCampaignsRecordsList',
                        'handleCampaignsRecordsList',
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

                handleCampaignsRecordsList(jsonData) {
                    this.data_loaded = true;
                    this.campaigns = jsonData['data'];
                    document.getElementById('pagination_links').innerHTML = jsonData['links'];
                    if (this.campaigns) {
                        this.showSearchTextbox = true;
                        this.showSearchPageCountDropdown = true;
                    }
                },


                myDialogModalsButtonHandler($event) {

                    console.log('myDialogModalsButtonHandler:');
                    console.log($event);

                    handler = $event.detail['handler'];
                    data = $event.detail['data'];

                    console.log($event.detail);

                    console.log(handler);

                    this[handler](data);
                },


                handleDefaultModalCloseAction(response) {
                    this.$dispatch('hide-my-dialog-modal', {dialog_key: response.dialog_key});
                    this.handleSearch();

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

@push('my-dialogs')
    @component('dialogs::components.dialog-confirmok')@endcomponent

    <div>
        <script>
            registerDialog('CreateCampaign', {
                'xshow': false,
                'handleCancelButton': '',
                'submitButtonCaption': 'Create'
            });
        </script>
        <div x-show="myDialogModals['CreateCampaign']['xshow']" class="flex flex-col items-center">
            @livewire('campaigns.create-campaign')
        </div>
    </div>
@endpush

@section('content')

    <div id="main" >

        <div class="pt-12 flex">
            <x-admin::sidebar/>
            <div x-data="initAdminCampaigns" class="w-full h-full p-4 m-8 " @my-dialog-modal-button-handler.window="myDialogModalsButtonHandler($event)">
                <div class="text-xl font-bold p-3">
                    Campaigns Management
                </div>

                <div class="flex items-center w-full mr-8"  >
                    <div x-show="!page_loaded">
                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8 min-h-screen w-full" x-cloak>

                        <div class="align-middle rounded-tl-lg rounded-tr-lg inline-block w-full py-4  bg-white">

                            <div class="w-full flex flex-row">
                                <div class="w-1/5 p-3">
                                    <div class="flex-auto w-1/4">
                                        <div
                                            @click="showCreateCampaignModal"
                                            class="inline-flex border-2 border-gray-300 bg-gray-100 mr-2 rounded-md px-3 py-2 text-black ring-1 ring-transparent hover:text-black/70 focus-visible:ring-[#FF2D20]  focus:outline-none transition ease-in-out duration-150">
                                            <div class="mr-2">
                                                <svg class="h-6 w-6" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>Create</div>
                                        </div>

                                    </div>
                                </div>
                                <div class=" w-3/5 p-3 ">

                                    <div class=" w-full border rounded flex-auto px-2 lg:px-6 h-12 bg-transparent" x-show="campaigns && showSearchTextbox">
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

                                </div>
                                <div class=" w-1/5 text-right p-3 ">

                                    <div class="relative" x-show="campaigns && showSearchPageCountDropdown"
                                         x-cloak
                                         @click.away="showPerPageSelection = false" x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95">
                                        <x-dropdown align="right" width="w-14">
                                            <x-slot name="trigger">
                                                <div class="inline-flex border-2 border-gray-300 bg-gray-100 mr-2 rounded-md px-3 py-2 text-black ring-1 ring-transparent hover:text-black/70 focus-visible:ring-[#FF2D20]  focus:outline-none transition ease-in-out duration-150">
                                                    <span x-text="perPageSelection"></span>

                                                    <div class="mt-1">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </x-slot>

                                            <x-slot name="content">
                                                <ul>
                                                @for($i = 1; $i < 10; $i++)
                                                    <li
                                                        class="block w-full text-right px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                        x-show="perPageSelection != {{ $i * 5 }}" @click="handlePerPageSelection('{{ $i * 5 }}')" title="{{ $i * 5 }} Results Per Page"
                                                        role="option">
                                                        <span class="font-normal truncate">{{ $i * 5 }}</span>
                                                    </li>
                                                @endfor
                                                </ul>
                                            </x-slot>
                                        </x-dropdown>

                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="align-middle inline-block min-w-full  overflow-hidden bg-white  pt-3 ">
                            <table class="w-full">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider"></th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Name</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Title</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Status</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Start</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">End</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300"></th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr x-show="!data_loaded">
                                    <td colspan="7" class="align-middle text-center">
                                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                    </td>
                                </tr>
                                <tr x-show="!campaigns && data_loaded">
                                    <td colspan="7" class="align-middle text-center">
                                        <div class="text-lg leading-5 text-gray-800 p-6">No campaigns to display.</div>
                                    </td>
                                </tr>

                                <template x-for="campaign in campaigns" :key="campaign.id">
                                    <tr x-show="data_loaded">
                                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
                                            <div @click="deleteCampaign(campaign.id)" title="Delete Campaign"
                                                 class="inline-block p-2 cursor-pointer hover:bg-red-500 rounded">
                                                <svg class="h-6 w-6" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                        </td>
                                        <td  class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm leading-5 text-gray-800"><span x-text="campaign.id"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                            <div class="text-sm leading-5 text-blue-900"><span x-text="campaign.name"></span></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                            <div class="text-sm leading-5 text-blue-900"><span x-text="campaign.title"></span></div>
                                        </td>
                                        <td x-show="campaign.enabled" class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
                                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                <span class="relative text-xs">active</span>
                                            </span>
                                        </td>
                                        <td x-show="!campaign.enabled" class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
                                                            <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                                            <span class="relative text-xs">not active</span>
                                                        </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-blue-900 text-sm leading-5" x-text="campaign.start_datetime">September 12</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-blue-900 text-sm leading-5" x-text="campaign.end_datetime">September 12</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
                                            <div class="flex flex-row">
                                                <div @click="viewCampaignDetails(campaign.id)" title="Edit Campaign"
                                                     class="cursor-pointer mr-3 text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                                    <svg class="h-6 w-6" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </div>
                                                <div @click="viewCampaignCategoryDetails(campaign.id)" title="Edit Campaign Categories"
                                                     class="cursor-pointer text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                                    <svg class="h-6 w-6" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </div>
                                            </div>
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
