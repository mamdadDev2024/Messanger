<header 
    x-data="{ isMobileMenuOpen: false }" 
    class="container mx-auto py-4 px-4 sm:px-6 lg:px-8 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md sticky top-0 z-50 shadow-md"
>
    @if (Route::has('login'))
        <nav class="flex items-center justify-between" aria-label="Main navigation">
            <!-- App Logo/Brand -->
            <a href="/" class="flex items-center gap-2 text-xl font-semibold text-gray-900 dark:text-gray-100 transition-colors duration-300 hover:text-blue-600 dark:hover:text-blue-400" aria-label="TeleMessenger Home">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.157.16-.295.295-.605.295l.213-3.05 5.56-5.022c.242-.213-.054-.333-.373-.121l-6.869 4.326-2.96-.924c-.643-.203-.656-.643.136-.953l11.566-4.458c.537-.232 1.006.136.832.941z"/>
                </svg>
                <span class="hidden sm:inline select-none">TeleMessenger</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-6 text-gray-700 dark:text-gray-300 text-base font-medium">
                @auth
                    <x-nav-link href="{{ url('/dashboard') }}" :active="request()->routeIs('dashboard')" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                        <i class="fas fa-home mr-2"></i> داشبورد
                    </x-nav-link>
                @else
                    <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                        <i class="fas fa-sign-in-alt mr-2"></i> ورود
                    </x-nav-link>

                    @if (Route::has('register'))
                        <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded font-semibold">
                            <i class="fas fa-user-plus mr-2"></i> ثبت نام
                        </x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="isMobileMenuOpen = !isMobileMenuOpen"
                :aria-expanded="isMobileMenuOpen.toString()"
                aria-controls="mobile-menu"
                aria-label="باز و بسته کردن منوی موبایل"
                class="lg:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" :class="{'hidden': isMobileMenuOpen, 'block': !isMobileMenuOpen}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" :class="{'block': isMobileMenuOpen, 'hidden': !isMobileMenuOpen}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div 
            x-show="isMobileMenuOpen" 
            x-transition
            x-cloak
            id="mobile-menu"
            class="lg:hidden bg-white dark:bg-gray-900 shadow-md rounded-b-md mt-2 py-4 px-6"
        >
            <nav class="flex flex-col space-y-4 text-gray-700 dark:text-gray-300 text-base font-medium">
                @auth
                    <x-mobile-nav-link href="{{ url('/dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition-colors duration-200">
                        <i class="fas fa-home mr-3"></i> داشبورد
                    </x-mobile-nav-link>
                @else
                    <x-mobile-nav-link href="{{ route('login') }}" class="hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-3"></i> ورود
                    </x-mobile-nav-link>

                    @if (Route::has('register'))
                        <x-mobile-nav-link href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded font-semibold transition-colors duration-200">
                            <i class="fas fa-user-plus mr-3"></i> ثبت نام
                        </x-mobile-nav-link>
                    @endif
                @endauth
            </nav>
        </div>
    @endif
</header>
