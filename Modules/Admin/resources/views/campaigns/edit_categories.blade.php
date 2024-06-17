@extends('admin::layouts.master')

@section('title', 'Dashboard: Campaign Management')

@push('css')
@endpush

@push('js')
    <script>

        function initManageCampaignCategories() {
            return {
                data_loaded: true,
                page_loaded: false,
                categories: {},
                campaign_id: {{ $campaign_details['id'] }},
                init() {

                    this.page_loaded = true;

                    this.categories = {!! json_encode($categories) !!};

                    this.campaign_details = {!! json_encode($campaign_details) !!};

                    console.log(this.campaign_details);

                }

            };
        }

    </script>
@endpush


@section('content')

    <div id="main" >
        <div class="h-6 mt-12 bg-yellow-200 sm:bg-purple-300 md:bg-green-300 lg:bg-purple-300 xl:bg-yellow-100 2xl:bg-gray-200">
            <span class="sm:hidden">TINY</span>
            <span class="hidden sm:block md:hidden">SMALL</span>
            <span class="hidden md:block lg:hidden">MEDIUM</span>
            <span class="hidden lg:block xl:hidden">LARGE</span>
            <span class="hidden xl:block  2xl:hidden">EX-LARGE</span>
            <span class="hidden 2xl:block">EX-EX-LARGE</span>
        </div>
        <div class="pt-12 flex">
            <x-admin::sidebar/>
            <div x-data="initManageCampaignCategories" class="w-full h-full p-4 m-8 " @my-dialog-modal-button-handler.window="myDialogModalsButtonHandler($event)">
                <div class="text-xl font-bold p-3">
                    Campaign Category Management
                </div>
                <div class="flex items-center w-full mr-8"  >
                    <div x-show="!page_loaded">
                        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8 min-h-screen w-full" x-cloak>
                        <div class="flex flex-col">
                            <div class="flex flex-row">
                                <div class="font-bold p-2">Campaign Id: </div>
                                <div class="p-2" x-text="campaign_id"></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="font-bold p-2">Campaign Name: </div>
                                <div class="p-2">{{ $campaign_details['name'] }}</div>
                            </div>
                        </div>
                        <div class="flex mr-3 flex-wrap">

                                <template x-for="categoryIndex in categories" :key="categoryIndex.id">
                                    <div class="mb-6 mr-3 lg:w-96">
                                        <div class="text-xl border-t border-l border-r p-3 ">
                                            <span x-text="categoryIndex.title"></span>
                                        </div>

                                        <div class="text-center w-full border-r border-l border-b pb-3">
                                            <div class="relative"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95">
                                                <x-dropdown align="right" width="w-full">
                                                    <x-slot name="trigger">
                                                        <div class="inline-flex border-2 border-gray-300 bg-gray-100 mr-2 rounded-md px-3 py-2 text-black ring-1 ring-transparent hover:text-black/70 focus-visible:ring-[#FF2D20]  focus:outline-none transition ease-in-out duration-150">
                                                            Select Category

                                                            <div class="mt-1">
                                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        <ul>
                                                            <template x-for="category in categoryIndex.categories" :key="category.id">
                                                                <li
                                                                    class="block w-full text-right px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                    @click="handleCategorySelection(category.id)"
                                                                    role="option">
                                                                    <span class="font-normal" x-text="category.name"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </div>

                                        <div class="p-3 border-l border-r border-b ">
                                            <template x-for="row in campaign_details['categories'][categoryIndex.id]" :key="row.id">
                                                <div class="flex hover:bg-gray-100">
                                                    <div class="p-3 hover:bg-red-500">
                                                        <svg class="h-6 w-6" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="p-3">
                                                        <span   x-text="row.name"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="text-center w-full border-r border-l border-b pb-3 ">
                                            <div class="flex flex-row justify-between">
                                                <div class="p-3">
                                                    <div class="cursor-pointer mr-3 text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">
                                                        Reset
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <div class="cursor-pointer mr-3 text-center px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">
                                                        Save
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div>

                                </div>

                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
