<nav x-data="{ open: false }" class="back shad margin_bottom_1rem">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.home') }}">
                        {{ __('Adm') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if (Auth::user()['status'] === 'admin')
                        <x-nav-link :href="route('admin.user.add')" :active="request()->routeIs('admin.user.add')">
                            {{ __('User add') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.user.remove')" :active="request()->routeIs('admin.user.remove')">
                            {{ __('User remove') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.user.change')" :active="request()->routeIs('admin.user.change')">
                            {{ __('User change') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.page.add')" :active="request()->routeIs('admin.page.add')">
                            {{ __('Page add') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.page.remove')" :active="request()->routeIs('admin.page.remove')">
                            {{ __('Page remove') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                            {{ __('Logs') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md back focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
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

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.home')" :active="request()->routeIs('admin.home')">
                {{ __('Adm') }}
            </x-responsive-nav-link>
            @if (Auth::user()['status'] === 'admin')
            <x-responsive-nav-link :href="route('admin.user.add')" :active="request()->routeIs('admin.user.add')">
                {{ __('User add') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.user.remove')" :active="request()->routeIs('admin.user.remove')">
                {{ __('User remove') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.user.change')" :active="request()->routeIs('admin.user.change')">
                {{ __('User change') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.page.add')" :active="request()->routeIs('admin.page.add')">
                {{ __('Page add') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.page.remove')" :active="request()->routeIs('admin.page.remove')">
                {{ __('Page remove') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                {{ __('Logs') }}
            </x-responsive-nav-link>

            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t">
            <div class="px-4">
                <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm ">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
