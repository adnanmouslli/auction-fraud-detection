@extends('layouts.app')

@section('title', $auction->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 space-x-reverse md:space-x-3 md:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('auctions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mx-2"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-left text-gray-400 text-sm mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ Str::limit($auction->title, 40) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <!-- Auction Header -->
                <div class="border-b border-gray-200 pb-5 mb-5">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $auction->title }}</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="mx-1"><i class="fas fa-user mr-1"></i> {{ $auction->seller->name }}</span>
                                <span class="mx-1"><i class="fas fa-calendar-alt mr-1"></i> {{ $auction->created_at->format('Y-m-d') }}</span>
                            </p>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="mt-3 md:mt-0">
                            @if($auction->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span> مزاد نشط
                                </span>
                            @elseif($auction->status == 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1"></i> مزاد مكتمل
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> مزاد ملغي
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Auction Image and Description -->
                    <div class="lg:col-span-2">
                        <div class="h-64 bg-gray-200 flex items-center justify-center mb-6 rounded">
                            <i class="fas fa-gavel text-gray-400 text-5xl"></i>
                        </div>
                        
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">وصف المزاد</h2>
                            <div class="text-gray-700 leading-relaxed">
                                {{ $auction->description ?? 'لا يوجد وصف لهذا المزاد.' }}
                            </div>
                        </div>
                        
                        <!-- Auction Dates -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">تفاصيل الوقت</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ البدء</p>
                                    <p class="text-gray-700">{{ $auction->start_time->format('Y-m-d H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ الانتهاء</p>
                                    <p class="text-gray-700">{{ $auction->end_time->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bid History -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">سجل المزايدات</h2>
                            
                            @if($bidHistory->isEmpty())
                                <div class="bg-gray-50 rounded-lg p-6 text-center">
                                    <p class="text-gray-500">لا توجد مزايدات على هذا المزاد حتى الآن.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة العرض</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                                @if(auth()->check() && auth()->user()->isAdmin())
                                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($bidHistory as $bid)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $bid->user->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($bid->bid_amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $bid->created_at->format('Y-m-d H:i:s') }}
                                                    </td>
                                                    @if(auth()->check() && auth()->user()->isAdmin())
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if($bid->is_fraud)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    احتيال
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    موثوق
                                                                </span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Auction Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Current Price and Countdown -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">السعر الحالي</p>
                                <p class="text-3xl font-bold text-blue-600">{{ number_format($auction->current_price, 2) }}</p>
                                <p class="text-sm text-gray-500 mt-1">السعر الأولي: {{ number_format($auction->starting_price, 2) }}</p>
                            </div>
                            
                            @if($auction->status == 'active' && !$isEnded)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">الوقت المتبقي</p>
                                    <div class="text-lg font-medium text-gray-900" id="countdown" data-end-time="{{ $auction->end_time->format('Y-m-d H:i:s') }}">
                                        جاري الحساب...
                                    </div>
                                </div>
                                
                                @auth
                                    @if(auth()->id() != $auction->seller_id)
                                        <div class="mt-6">
                                            <form action="{{ route('bids.store', $auction) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="bid_amount" class="block text-sm font-medium text-gray-700 mb-1">قيمة العرض</label>
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <input type="number" name="bid_amount" id="bid_amount" step="0.01" min="{{ $auction->current_price + 0.01 }}" 
                                                            value="{{ old('bid_amount', $auction->current_price + 1) }}"
                                                            class="block w-full pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="0.00">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">SAR</span>
                                                        </div>
                                                    </div>
                                                    @error('bid_amount')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                
                                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <i class="fas fa-gavel mr-2"></i> تقديم عرض
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-6 text-center">
                                        <p class="text-gray-600 mb-3">يجب تسجيل الدخول للمشاركة في المزاد</p>
                                        <a href="{{ route('login') }}" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            تسجيل الدخول
                                        </a>
                                    </div>
                                @endauth
                            @elseif($auction->status == 'completed' || $isEnded)
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mt-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="mr-3">
                                            <h3 class="text-sm font-medium text-blue-800">انتهى المزاد</h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>لقد انتهى هذا المزاد ولم يعد متاحاً للمشاركة.</p>
                                                @if($highestBid)
                                                    <p class="mt-1">الفائز: <strong>{{ $highestBid->user->name }}</strong> بمبلغ <strong>{{ number_format($highestBid->bid_amount, 2) }}</strong></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($auction->status == 'cancelled')
                                <div class="bg-red-50 border border-red-200 rounded-md p-4 mt-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-400"></i>
                                        </div>
                                        <div class="mr-3">
                                            <h3 class="text-sm font-medium text-red-800">تم إلغاء المزاد</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <p>تم إلغاء هذا المزاد ولم يعد متاحاً للمشاركة.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Seller Info -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">معلومات البائع</h3>
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $auction->seller->name }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500">عضو منذ {{ $auction->seller->created_at->format('Y-m-d') }}</p>
                        </div>
                        
                        <!-- Admin Controls -->
                        @if(auth()->check() && (auth()->id() == $auction->seller_id || auth()->user()->isAdmin()))
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">إدارة المزاد</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('auctions.edit', $auction) }}" class="block w-full text-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-edit mr-2"></i> تعديل المزاد
                                    </a>
                                    
                                    @if($auction->status == 'active')
                                        <form action="{{ route('auctions.complete', $auction) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="block w-full text-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <i class="fas fa-check mr-2"></i> إكمال المزاد
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('auctions.cancel', $auction) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="block w-full text-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <i class="fas fa-times mr-2"></i> إلغاء المزاد
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('dashboard.fraud-alerts') }}?auction_id={{ $auction->id }}" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-shield-alt mr-2"></i> فحص الاحتيال
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Countdown functionality
    function updateCountdown() {
        const countdownEl = document.getElementById('countdown');
        if (!countdownEl) return;
        
        const endTime = new Date(countdownEl.getAttribute('data-end-time')).getTime();
        const now = new Date().getTime();
        const timeLeft = endTime - now;
        
        if (timeLeft <= 0) {
            countdownEl.innerHTML = 'انتهى المزاد';
            // Refresh the page to show the ended state
            window.location.reload();
            return;
        }
        
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        
        let countdownText = '';
        
        if (days > 0) {
            countdownText = `${days} يوم ${hours} ساعة ${minutes} دقيقة`;
        } else if (hours > 0) {
            countdownText = `${hours} ساعة ${minutes} دقيقة ${seconds} ثانية`;
        } else {
            countdownText = `${minutes} دقيقة ${seconds} ثانية`;
        }
        
        countdownEl.innerHTML = countdownText;
    }
    
    // Update countdown immediately and then every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endpush