<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Css -->
    @stack('css')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Js -->
    @stack('js')

    @livewireStyles
</head>
<body class="font-sans antialiased">
<x-cookie-alert/>


<div class="min-h-screen bg-duststorm-50">
    <!-- Page Content -->
    <div class="md:container md:mx-auto">
        <header >
            <div class="flex flex-row justify-between p-3 sm:p-6">
                <div class="">
                    <div class="top_header_text" style="text-align: right">Readers Choice Contest Page</div>
                </div>
                <div class="flex flex-row">
                        @auth
                            <div x-data="{}" x-cloak>
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <div class="inline-flex border-2 border-gray-300 bg-gray-100 mr-2 rounded-md px-3 py-2 text-black ring-1 ring-transparent hover:text-black/70 focus-visible:ring-[#FF2D20]  focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->nickname }}</div>

                                            <div class="mt-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </x-slot>

                                    <x-slot name="content">
                                        @role('Admin')
                                        <x-dropdown-link :href="route('admin.index')">
                                            {{ __('Admin Dashboard') }}
                                        </x-dropdown-link>
                                        @endrole

                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                             onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth

                </div>
            </div>
        </header>
        <main>
            @yield('content')
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
