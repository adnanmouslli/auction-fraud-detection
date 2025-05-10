@extends('layouts.app')

@section('title', 'المزادات النشطة')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">المزادات النشطة</h1>
                <p class="text-gray-600">استعرض أحدث المزادات المتاحة للمشاركة</p>
            </div>
            
            @auth
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('auctions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i> إنشاء مزاد جديد
                    </a>
                </div>
            @endauth
        </div>
        
        <!-- Search and Filter Bar -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <form action="{{ route('auctions.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4 md:space-x-reverse">
                    <div class="flex-grow">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ابحث عن مزاد..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">جميع المزادات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-filter mr-2"></i> تصفية
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        @if($auctions->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-12 text-center">
                    <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد مزادات متاحة حالياً</p>
                    @auth
                        <div class="mt-6">
                            <a href="{{ route('auctions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i> إنشاء مزاد جديد
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        @else
            <!-- Auctions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($auctions as $auction)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                        <div class="relative">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-gavel text-gray-400 text-5xl"></i>
                            </div>
                            
                            <!-- Status Badge -->
                            @if($auction->status == 'active')
                                <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span> نشط
                                </span>
                            @elseif($auction->status == 'completed')
                                <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1 text-xs"></i> مكتمل
                                </span>
                            @else
                                <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1 text-xs"></i> ملغي
                                </span>
                            @endif
                            
                            <!-- Time Remaining Badge -->
                            @if($auction->status == 'active' && now()->lt($auction->end_time))
                                <span class="absolute top-2 left-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" 
                                    data-countdown="{{ $auction->end_time->format('Y-m-d H:i:s') }}">
                                    <i class="fas fa-clock mr-1 text-xs"></i> 
                                    <span class="countdown-text">جاري الحساب...</span>
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition">
                                    <a href="{{ route('auctions.show', $auction) }}">{{ Str::limit($auction->title, 40) }}</a>
                                </h3>
                            </div>
                            
                            <p class="text-gray-500 text-sm mb-4">{{ Str::limit($auction->description, 80) }}</p>
                            
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-gray-500 text-xs mb-1">السعر الحالي</p>
                                    <p class="text-xl font-bold text-blue-600">{{ number_format($auction->current_price, 2) }}</p>
                                </div>
                                
                                <a href="{{ route('auctions.show', $auction) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    التفاصيل <i class="fas fa-chevron-left mr-2"></i>
                                </a>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                                <div>
                                    <i class="fas fa-user mr-1"></i> {{ $auction->seller->name }}
                                </div>
                                <div>
                                    <i class="fas fa-calendar-alt mr-1"></i> {{ $auction->created_at->format('Y-m-d') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $auctions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Countdown functionality
    function updateCountdowns() {
        document.querySelectorAll('[data-countdown]').forEach(el => {
            const endTime = new Date(el.getAttribute('data-countdown')).getTime();
            const now = new Date().getTime();
            const timeLeft = endTime - now;
            
            if (timeLeft <= 0) {
                el.querySelector('.countdown-text').textContent = 'انتهى';
                return;
            }
            
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            
            let countdownText = '';
            
            if (days > 0) {
                countdownText = `${days} يوم`;
            } else if (hours > 0) {
                countdownText = `${hours} ساعة`;
            } else {
                countdownText = `${minutes} دقيقة`;
            }
            
            el.querySelector('.countdown-text').textContent = countdownText;
        });
    }
    
    // Update countdowns immediately and then every minute
    updateCountdowns();
    setInterval(updateCountdowns, 60000);
</script>
@endpush