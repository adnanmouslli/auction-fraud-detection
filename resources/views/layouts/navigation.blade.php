<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('auctions.index') }}" class="flex items-center">
                        <i class="fas fa-gavel text-blue-600 text-2xl mx-2"></i>
                        <span class="font-bold text-xl text-gray-800">نظام كشف الاحتيال</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('auctions.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('auctions.index') ? 'border-blue-500 text-gray-900' : '' }}">
                        المزادات النشطة
                    </a>
                    
                    @auth
                        <a href="{{ route('auctions.my') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('auctions.my') ? 'border-blue-500 text-gray-900' : '' }}">
                            مزاداتي
                        </a>
                        
                        <a href="{{ route('bids.my') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('bids.my') ? 'border-blue-500 text-gray-900' : '' }}">
                            عروضي
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('dashboard*') ? 'border-blue-500 text-gray-900' : '' }}">
                                لوحة التحكم
                            </a>
                            
                            <a href="{{ route('fraud-alerts.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('fraud-alerts.*') ? 'border-blue-500 text-gray-900' : '' }}">
                                <span id="fraud-alert-badge" class="inline-flex items-center px-2 mx-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full hidden">0</span>
                                إنذارات الاحتيال
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Authentication -->
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 mx-2 hover:text-blue-600">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 mx-2 hover:text-blue-600">إنشاء حساب</a>
                @else
                    <div class="ms-3 relative">
                        <div class="flex items-center">
                            <span class="text-gray-800 ml-2">{{ Auth::user()->name }}</span>
                            <button id="user-menu-button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <i class="fas fa-user-circle text-gray-400 text-2xl"></i>
                            </button>
                        </div>

                        <!-- User dropdown menu -->
                        <div id="user-dropdown" class="hidden origin-top-right absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الملف الشخصي</a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">تسجيل الخروج</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('auctions.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('auctions.index') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium focus:outline-none transition">
                المزادات النشطة
            </a>
            
            @auth
                <a href="{{ route('auctions.my') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('auctions.my') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium focus:outline-none transition">
                    مزاداتي
                </a>
                
                <a href="{{ route('bids.my') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('bids.my') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium focus:outline-none transition">
                    عروضي
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium focus:outline-none transition">
                        لوحة التحكم
                    </a>
                    
                    <a href="{{ route('fraud-alerts.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('fraud-alerts.*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium focus:outline-none transition">
                        إنذارات الاحتيال
                        <span id="mobile-fraud-alert-badge" class="inline-flex items-center px-2 mx-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full hidden">0</span>
                    </a>
                @endif
            @endauth
        </div>

        <!-- Responsive Authentication Menu -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @guest
                <div class="mt-3 space-y-1">
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition">
                        إنشاء حساب
                    </a>
                </div>
            @else
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition">
                        الملف الشخصي
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-right block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
    
    // Toggle user dropdown
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function() {
            userDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
    
    // For admin: fetch pending fraud alerts count
    @if(auth()->check() && auth()->user()->isAdmin())
    function fetchPendingAlertsCount() {
        fetch('{{ route('fraud-alerts.pending-count') }}')
            .then(response => response.json())
            .then(data => {
                const badgeDesktop = document.getElementById('fraud-alert-badge');
                const badgeMobile = document.getElementById('mobile-fraud-alert-badge');
                
                if (data.count > 0) {
                    badgeDesktop.textContent = data.count;
                    badgeDesktop.classList.remove('hidden');
                    
                    badgeMobile.textContent = data.count;
                    badgeMobile.classList.remove('hidden');
                } else {
                    badgeDesktop.classList.add('hidden');
                    badgeMobile.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching alerts count:', error));
    }
    
    // Fetch initially and then every 30 seconds
    fetchPendingAlertsCount();
    setInterval(fetchPendingAlertsCount, 30000);
    @endif
</script>
@endpush