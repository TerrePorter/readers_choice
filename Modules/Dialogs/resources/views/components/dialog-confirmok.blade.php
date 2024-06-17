<div>
    <script>
        registerDialog('ConfirmOk', {
                'xshow': false,
                'message': '',
                'handleAnswerOk':'',
                'answerOk_Caption':'',
            });
    </script>
<div x-show="myDialogModals['ConfirmOk']['xshow']" class="flex flex-col items-center">
    <div x-show="myDialogModals['ConfirmOk']['title']" class="w-full border-b-2 border-b-gray-300 text-center">
        <span x-html="myDialogModals['ConfirmOk']['title']"></span>
    </div>
    <div class="max-h-40 overflow-y-auto">
        <p class="text-2xl text-center font-serif p-4">
            <span x-html="myDialogModals['ConfirmOk']['message']"></span>
        </p>
    </div>
    <div class="w-fit justify-between mt-5">
        <button
            class="px-4 py-2 bg-red-400 hover:bg-blue-700 text-white text-xl font-serif rounded-full border-none focus:outline-none"
            @click="$dispatch('my-dialog-modal-button-handler', {handler: myDialogModals['ConfirmOk']['handleAnswerOk'], data: {dialog_key: 'ConfirmOk'}})">
            <span x-text="myDialogModals['ConfirmOk']['answerOk_Caption']"></span>
        </button>
    </div>
</div>
</div>
