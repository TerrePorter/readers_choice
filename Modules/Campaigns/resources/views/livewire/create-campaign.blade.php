@push('css')
    <style>
        /* CHECKBOX TOGGLE SWITCH */
        .toggle-checkbox:checked {
            @apply: right-0 border-green-400;
            right: 0;
            border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
            @apply: bg-green-400;
            background-color: #68D391;
        }
    </style>
@endpush
@push('js')
    <script>
        function initDataCreateCampaign() {
            return {
                campaign_id:  0,

                // handle event to update livewire campaign id to use this form as a update/create form
                handleCampaignIdUpdate($event) {
                    console.log($event.detail);
                    this.$wire.loadCampaign($event.detail);
                    //console.log('test:' + this.campaign_id);
                }

            };
        }
    </script>
@endpush
<div x-data="initDataCreateCampaign" @update_campaign_id.window="handleCampaignIdUpdate($event)">
    <div  wire:loading.remove>
<form class="bg-white  " wire:submit.prevent="save">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
            Internal Name
        </label>
        <input wire:model="name" maxlength="75" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Name">
        @error('name')
            <p class="text-red-500 text-xs italic p-2">{{ $message }}</p>
        @else
            <p class="text-red-500 text-xs italic p-2">example: rc_{{ date("Y") }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Page Title
        </label>
        <input wire:model="title" maxlength="255" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" placeholder="Title">
        @error('title')
            <p class="text-red-500 text-xs italic p-2">{{ $message }}</p>
        @enderror
    </div>
    <div class="">
        @component('campaigns::components.alpine.datetimepicker', ['label_caption' => "Select Start Date", "date_id" => "start_datetime", 'extra_date_input_data' => 'wire:model=start_datetime', 'default_timestamp'=> $start_datetime])@endcomponent
        @error('start_datetime')
        <p class="text-red-500 text-xs italic p-2">{{ $message }}</p>
        @enderror
    </div>
    <div class="">
        @component('campaigns::components.alpine.datetimepicker', ['label_caption' => "Select End Date", "date_id" => "end_datetime", 'extra_date_input_data' => 'wire:model=end_datetime', 'default_timestamp'=> $end_datetime])@endcomponent
        @error('end_datetime')
        <p class="text-red-500 text-xs italic p-2">{{ $message }}</p>
        @enderror
    </div>
    <div class="w-full mb-6 ">
        <label for="toggle" class="text-gray-700">Enabled</label>
        <div class="relative inline-block w-10 ml-3 align-middle select-none transition duration-200 ease-in">
            <input type="checkbox" wire:model="enabled" name="enabled" id="enabled" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
            <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
        </div>
    </div>
    <div class="flex items-center justify-between" >
        <button class="bg-slate-500 hover:bg-slate-700 hover:text-green-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button"
                @click="$dispatch('my-dialog-modal-button-handler', {handler: myDialogModals['CreateCampaign']['handleCancelButton'], data: {dialog_key: 'CreateCampaign'}})">
            Cancel
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 hover:text-green-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            <span x-text="myDialogModals['CreateCampaign']['submitButtonCaption']"></span>
        </button>
    </div>
</form>
    </div>
    <div wire:loading>
        Processing ...
    </div>
</div>
