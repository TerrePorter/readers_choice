<div>
    <script>

        // index for all loaded modals
        let myDialogModals = {};

        // helper to register a dialog modal
        function registerDialog(key, data) {
            myDialogModals[key] = data;
        }

        // alpine handler function
        function xInitMyDialogModal() {
            return {
                modal_background_color: '',
                modal_background_color_default: 'bg-white',
                imageAttachmentData: '',
                myDialogModalXshowAttribute: false,
                current_image_id: '',
                myDialogModals:  myDialogModals,

                // old code
                // todo:: remove old vars
                save_image_to_gallery: false,
                image_nsfw: false,
                imageData: '',

                // create a selected modal from event
                createMyDialog($event) {

                    // get the event data
                    dialog_key = $event.detail.dialog_key;
                    dialog_modal = $event.detail.dialog_modal;

                    // if does not have extra data, then set defaults
                    if (!dialog_modal.hasOwnProperty('extra_data')) {
                        dialog_modal.extra_data = {};
                    }

                    // if has modal background color, set var
                    if (dialog_modal.hasOwnProperty('modal_background_color')) {
                        if (dialog_modal.modal_background_color !== 'default') {
                            this.modal_background_color = dialog_modal.modal_background_color;
                        } else {
                            this.modal_background_color = this.modal_background_color_default;
                        }
                    } else {
                        // set defaults for background color
                        this.modal_background_color = this.modal_background_color_default;
                    }

                    // if has title set var
                    if (dialog_modal.hasOwnProperty('title')) {
                        if (!dialog_modal.title) {
                            this.myDialogModals[dialog_key]['title'] = dialog_modal.title;
                        }
                    }

                    // if has a event trigger then dispatch the event
                    if (dialog_modal.hasOwnProperty('dispatch')) {
                        this.$dispatch(dialog_modal.dispatch.name, dialog_modal.dispatch.data);
                    }

                    // update the modal data with new params
                    this.myDialogModals[dialog_key] = dialog_modal;

                    // TODO: from old code, account for the possibility of usage with new structure
                    switch (dialog_key) {
                        case 'EnlargeImage':
                            this.current_image_id = this.myDialogModals['EnlargeImage']['currentImage'];
                            image = document.getElementById(this.current_image_id);
                            dImage = document.getElementById('MyDialogModalsEnlargeImageId');
                            if (!image.dataset.image400.includes('data:image')) {
                                dImage.setAttribute('src', image.dataset.image400 + '?' + new Date().getTime());
                            } else {
                                dImage.setAttribute('src', image.dataset.image400);
                            }
                            dImage.setAttribute("data-key", this.current_image_id);
                            this.myDialogModals['EnlargeImage']['url'] = image.dataset.imagefull;
                            break;
                        case 'DeleteImage':
                            this.current_image_id = this.myDialogModals['DeleteImage']['currentImage'];
                            image = document.getElementById(this.current_image_id);
                            dImage = document.getElementById('MyDialogModalsDeleteImageId');
                            dImage.setAttribute('src', image.dataset.image400 + '?' + new Date().getTime());
                            dImage.setAttribute("data-key", this.current_image_id);
                            break;
                        case 'EditImageDetails':
                            this.current_image_id = this.myDialogModals['EditImageDetails']['currentImage'];
                            image = document.getElementById(this.current_image_id);
                            dImage = document.getElementById('MyDialogModalsEditDetailsImageId');
                            dImage.setAttribute('src', image.dataset.image400 + '?' + new Date().getTime());
                            dImage.setAttribute("data-key", this.current_image_id);
                            document.getElementById('EditImageDetailsFolder').value = this.myDialogModals['EditImageDetails']['defaultValueFolder'];
                            document.getElementById('EditImageDetailsNSFW').value = this.myDialogModals['EditImageDetails']['defaultValueNSFW'];
                            break;
                    }

                    // show the modal
                    this.myDialogModals[dialog_key]['xshow'] = true;
                    this.myDialogModalXshowAttribute = true;
                },

                // default close modal hanlder (not ever used, just here for reference)
                defaultCloseModalHandler($event) {
                    dialog_key = $event.detail.dialog_key;
                    this.myDialogModals[dialog_key]['xshow'] = false;
                    this.myDialogModalXshowAttribute = false;
                },

                // get the value of a html element
                getElementValue(elemId) {
                    return document.getElementById(elemId).value;
                },

                // process image file attachment (old code, remove after feature updated to new structure)
                handleImageFileAttachment(event) {
                    this.fileToDataUrl(event, src => this.imageData = src)
                },

                // handle upload attachment (old code, remove after feature updated to new structure)
                handleImageFileAttachmentSendData() {
                    console.log(this.myDialogModals);
                    this.myDialogModals['UploadImageAttachment']['xshow'] = false;
                    this.myDialogModalXshowAttribute = false;
                    document.getElementById('file-upload').dispatchEvent(
                        new CustomEvent('my-dialog-modal-button-handler',
                            {
                                detail: {
                                    handler: 'handleFileChosenAction',
                                    dialog_key: 'UploadImageAttachment',
                                    'data': {
                                        image: this.imageData,
                                        image_nsfw: this.image_nsfw,
                                        save_image_to_gallery: this.save_image_to_gallery,
                                    }
                                }, bubbles: true }));

                },

                // part of the image upload (old code)
                fileToDataUrl(event, callback) {
                    if (! event.target.files.length) return
                    let file = event.target.files[0];
                    let reader = new FileReader();
                    reader.readAsDataURL(file)
                    reader.onload = e => callback(e.target.result)
                },
            }
        }
    </script>
        <div x-data="xInitMyDialogModal()" @hide-my-dialog-modal.window="defaultCloseModalHandler($event)" @create-my-dialog-modal.window="createMyDialog($event)">
            <div class="fixed z-1 w-full  h-full top-0 left-0 flex items-center justify-center"
                 x-cloak x-show="myDialogModalXshowAttribute">
                <div  class="fixed w-full h-full bg-gray-500 opacity-50"></div>
                <div :class="{ [modal_background_color]: true }" class="relative z-2 w-full max-w-xl sm:w-auto   p-4 sm:p-8 mx-auto rounded-xl flex flex-col items-center">
                    {{$slot}}
                </div>
            </div>
        </div>
</div>
