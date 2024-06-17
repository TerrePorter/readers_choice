<!-- component -->
<div class="fixed left-0 bottom-0 z-40 " x-data="initCookieAccept()" @keydown.window.escape="cookies = false">
    @push('js')
        <script>
            function initCookieAccept() {
                return {
                    cookies: false,
                    init() {
                        console.log('cookie alert init');
                        // Show the alert if we cant find the "acceptCookies" cookie
                        if (!this.getCookie("ps_acceptCookies")) {
                            this.$watch('cookies', o => !o && window.setTimeout(() => (cookies = true), 1000)); setTimeout(() => cookies = true, 1500);
                        }
                    },

                    acceptCookies() {
                        this.setCookie("ps_acceptCookies", true, 365);
                        this.cookies = false;
                        // dispatch the accept event
                        window.dispatchEvent(new Event("cookieAlertAccept"))
                    },

                    // Cookie functions from w3schools
                    setCookie(cname, cvalue, exdays) {
                        var expires = "";
                        if (exdays) {
                            var date = new Date();
                            date.setTime(date.getTime() + (exdays*24*60*60*1000));
                            expires = "; expires=" + date.toUTCString();
                        }
                        document.cookie = name + "=" + (encodeURIComponent(cvalue) || "")  + expires + "; path=/";
                    },

                    getCookie(nameEQ) {
                        var nameEQ = name + "=";
                        var ca = document.cookie.split(';');
                        for(var i=0;i < ca.length;i++) {
                            var c = ca[i];
                            while (c.charAt(0)==' ') c = c.substring(1,c.length);
                            if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length,c.length));
                        }
                        return null;
                    },
                };
            }
        </script>
    @endpush

    <!-- Advise -->
    <div x-show="cookies" class="fixed sm:left-4 bottom-20 rounded-lg bg-white shadow-2xl w-full sm:w-1/2 xl:w-1/4 max-w-[450px] overflow-hidden"
         style="display: none;"
         x-transition:enter="transition ease-in duration-200"
         x-transition:enter-start="opacity-0 transform -translate-x-40"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-40">

        <!-- Text -->
        <div class="">
            <div class="relative overflow-hidden px-8 pt-8">
                <div width="80" height="77" class="absolute -top-10 -right-10 text-yellow-500">
                    <svg width="120" height="119" viewBox="0 0 120 119" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3"
                              d="M6.38128 49.1539C3.20326 32.893 13.809 17.1346 30.0699 13.9566L70.3846 6.07751C86.6455 2.89948 102.404 13.5052 105.582 29.7661L113.461 70.0808C116.639 86.3417 106.033 102.1 89.7724 105.278L49.4577 113.157C33.1968 116.335 17.4384 105.729 14.2604 89.4686L6.38128 49.1539Z"
                              fill="currentColor"/>
                    </svg>
                </div>
                <div class="text-2xl flex flex-col pb-4">
                    <small>Hello there..</small>
                    <span class="text-3xl font-bold">Cookies Required!</span>
                </div>
                <div class="pb-4">
                    <p>This site uses browser cookies for the login system, advertisements, and site analytics. By using this site you consent to the usage of cookies.</p>
                </div>
            </div>

        </div>
        <!-- Buttons -->
        <div class="w-full flex justify-center items-center border-t border-solid border-gray-200">
            <button class="flex-1 px-4 py-3 text-gray-500 hover:text-white hover:bg-green-400 duration-150" @click="acceptCookies()">
                I understand
            </button>
        </div>
    </div>
</div>
