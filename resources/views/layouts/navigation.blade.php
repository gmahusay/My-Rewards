<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger -->
                <div class="flex items-center -me-2 lg:hidden">
                    <button @click="sidebarOpen = ! sidebarOpen" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                        <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center sm:ms-6">
                @php $branding = Auth::user()->getBrandingBusiness(); @endphp
                @if($branding && ($branding->website_logo_path || $branding->website_name))
                    <div class="hidden md:flex items-center mr-4 px-3 py-1 bg-gray-50 rounded-lg border border-gray-100">
                        @if($branding->website_logo_path)
                            <img src="{{ Storage::url($branding->website_logo_path) }}" alt="{{ $branding->website_name }}" class="h-6 w-auto object-contain mr-2">
                        @endif
                        @if($branding->website_name)
                            <span class="text-sm font-semibold text-gray-700">{{ $branding->website_name }}</span>
                        @endif
                    </div>
                @endif

                @if(!Auth::user()->hasRole('admin'))
                    <!-- Notifications Dropdown -->
                    <div class="mr-4">
                        <x-dropdown align="right" width="w-96">
                            <x-slot name="trigger">
                                <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 transition focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                    @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-indigo-600 rounded-full">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Notifications</span>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-bold uppercase">Mark all read</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : 'bg-indigo-50/30' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 pt-0.5">
                                                    @if(($notification->data['type'] ?? '') == 'claim')
                                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                        </div>
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                                    <div class="mt-2 flex items-center justify-between">
                                                        <span class="text-[10px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                        <div class="flex gap-2">
                                                            @if(!$notification->read_at)
                                                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-bold uppercase">Mark Read</button>
                                                                </form>
                                                            @endif
                                                            <a href="{{ $notification->data['url'] ?? $notification->data['action_url'] ?? $notification->data['link'] ?? '#' }}" class="text-[10px] text-gray-500 hover:text-gray-700 font-bold uppercase">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-8 text-center text-gray-500 text-sm">
                                            No notifications yet.
                                        </div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        @php $cartCount = count(session()->get('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div class="flex items-center">
                                <span class="mr-2">{{ number_format(Auth::user()->points) }} pts</span>
                                <span class="mx-1 text-gray-300">|</span>
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover ml-2">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold ml-2">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
        </div>
    </div>
</nav>
