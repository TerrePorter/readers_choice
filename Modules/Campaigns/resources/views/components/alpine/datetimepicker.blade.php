@props(["label_caption" => "Select Date", "date_id" => "date", "function_name" => "initDateTimePicker".rand(1,100), 'extra_date_input_data' => '', 'default_timestamp' => time()])
@push('css')
    {{-- orginal from https://tailwindcomponents.com/component/date-and-time-picker --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/themes/airbnb.min.css">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
@endpush
<div
    x-data="{ init() { flatpickr(this.$refs.datetimewidget, {wrap: true, enableTime: true, dateFormat: 'M j, Y h:i K', defaultDate: '{{$default_timestamp}}'}); }}"
    x-ref="datetimewidget"
    class="flatpickr container mx-auto   mt-3"
>
    <label for="datetime" class="flex-grow  text-gray-700 text-sm font-bold mb-2">{{ $label_caption }}</label>
    <div class="flex align-middle align-content-center mt-2 mb-4">
        <input
            id="{{ $date_id }}" name="{{ $date_id }}"
            x-ref="datetime"
            type="text"
            data-input
            placeholder="{{ $label_caption }}"
            class="block w-full px-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm"
            {{ $extra_date_input_data }}
        >

        <a
            class="h-11 w-10 input-button cursor-pointer rounded-r-md bg-transparent border-gray-300 border-t border-b border-r"
            title="clear" data-clear
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-2 ml-1" viewBox="0 0 20 20" fill="#c53030">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"/>
            </svg>
        </a>
    </div>

</div>


