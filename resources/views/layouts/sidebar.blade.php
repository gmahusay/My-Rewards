<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

<div :class="{'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': ! sidebarOpen}" class="fixed inset-y-0 left-0 z-30 w-64 min-h-screen overflow-y-auto transition duration-300 transform bg-white border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-auto">
    <div class="flex items-center justify-center mt-8 px-4 text-center">
        @php $branding = Auth::user()->getBrandingBusiness(); @endphp
        <div class="flex flex-col items-center">
            <a href="{{ route('dashboard') }}">
                @if($branding && $branding->website_logo_path)
                    <img src="{{ Storage::url($branding->website_logo_path) }}" alt="{{ $branding->website_name ?? 'Logo' }}" class="block h-16 w-auto object-contain mb-2">
                @else
                    <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                @endif
            </a>
            <span class="text-xl font-bold text-gray-900 break-words">
                {{ ($branding && $branding->website_name) ? $branding->website_name : 'My Rewards' }}
            </span>
        </div>
    </div>

    <nav class="mt-10">
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="mx-3">Dashboard</span>
        </a>

        @if (Auth::user()->hasRole('employee'))
            <a href="{{ route('employee.claims.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('employee.claims.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-3">Claims</span>
            </a>
            <a href="{{ route('employee.nominations.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('employee.nominations.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <span class="mx-3">Nominations</span>
            </a>
            <a href="{{ route('events.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('events.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="mx-3">Events</span>
            </a>
            <a href="{{ route('employee.referrals.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('employee.referrals.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="mx-3">Referrals</span>
            </a>
        <a href="{{ route('employee.kpis.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('employee.kpis.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="mx-3">KPIs</span>
        </a>
        <a href="{{ route('gamification.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('gamification.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="mx-3">Gamification</span>
        </a>
        @endif

        @if (Auth::user()->hasRole('customer'))
            <a href="{{ route('customer.claims.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('customer.claims.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-3">Claims</span>
            </a>
            <a href="{{ route('events.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('events.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="mx-3">Events</span>
            </a>
            <a href="{{ route('customer.referrals.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('customer.referrals.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="mx-3">Referrals</span>
            </a>
        <a href="{{ route('customer.kpis.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('customer.kpis.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="mx-3">KPIs</span>
        </a>
        <a href="{{ route('gamification.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('gamification.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="mx-3">Gamification</span>
        </a>
        @endif

        @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.businesses.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('admin.businesses.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="mx-3">Businesses</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="mx-3">Users</span>
            </a>
        @endif

        @if (Auth::user()->hasRole('business'))
            <a href="{{ route('business.claims.categories.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.claims.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-3">Claims</span>
            </a>

            <a href="{{ route('business.nominations.categories.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.nominations.categories.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <span class="mx-3">Nominations</span>
            </a>
            <a href="{{ route('business.events.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.events.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="mx-3">Events</span>
            </a>

            <a href="{{ route('business.referrals.categories.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.referrals.categories.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="mx-3">Referrals</span>
            </a>

        <a href="{{ route('business.kpis.categories.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.kpis.categories.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="mx-3">KPIs</span>
        </a>
        <a href="{{ route('gamification.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('gamification.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="mx-3">Gamification</span>
        </a>

            <a href="{{ route('business.employees.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.employees.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="mx-3">Employees</span>
            </a>
            
            <a href="{{ route('business.customers.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.customers.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="mx-3">Customers</span>
            </a>

            <!-- Shop Dropdown for Business -->
            <div x-data="{ shopOpen: {{ request()->routeIs('shop.*', 'business.products.*', 'business.orders.*') ? 'true' : 'false' }} }">
                <button @click="shopOpen = !shopOpen" class="flex items-center justify-between w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('shop.*', 'business.products.*', 'business.orders.*') ? 'bg-gray-100 text-gray-700' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="mx-3">Shop</span>
                    </div>
                    <svg :class="shopOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="shopOpen" x-cloak class="bg-gray-50 py-2">
                    <a href="{{ route('shop.index') }}" class="flex items-center px-16 py-2 text-sm text-gray-600 hover:text-indigo-600 {{ request()->routeIs('shop.index') ? 'text-indigo-600 font-bold' : '' }}">
                        Browse Shop
                    </a>
                    <a href="{{ route('business.products.index') }}" class="flex items-center px-16 py-2 text-sm text-gray-600 hover:text-indigo-600 {{ request()->routeIs('business.products.*') ? 'text-indigo-600 font-bold' : '' }}">
                        Manage Products
                    </a>
                    <a href="{{ route('business.orders.index') }}" class="flex items-center px-16 py-2 text-sm text-gray-600 hover:text-indigo-600 {{ request()->routeIs('business.orders.*') ? 'text-indigo-600 font-bold' : '' }}">
                        Manage Orders
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown for Business -->
            <div x-data="{ settingsOpen: {{ request()->routeIs('business.settings.payment.*') ? 'true' : 'false' }} }">
                <button @click="settingsOpen = !settingsOpen" class="flex items-center justify-between w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('business.settings.payment.*') ? 'bg-gray-100 text-gray-700' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="mx-3">Settings</span>
                    </div>
                    <svg :class="settingsOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="settingsOpen" x-cloak class="bg-gray-50 py-2">
                    <a href="{{ route('business.settings.payment.edit') }}" class="flex items-center px-16 py-2 text-sm text-gray-600 hover:text-indigo-600 {{ request()->routeIs('business.settings.payment.*') ? 'text-indigo-600 font-bold' : '' }}">
                        Payment Settings
                    </a>
                </div>
            </div>
        @elseif (!Auth::user()->hasRole('admin'))
            <a href="{{ route('shop.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('shop.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="mx-3">Shop</span>
            </a>
            @if(Auth::user()->hasRole('referrer'))
            <a href="{{ route('gamification.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ request()->routeIs('gamification.*') ? 'bg-gray-100 text-gray-700 border-r-4 border-indigo-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="mx-3">Gamification</span>
            </a>
            @endif

        @endif
    </nav>
</div>
