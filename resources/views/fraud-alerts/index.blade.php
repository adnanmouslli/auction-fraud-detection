@extends('layouts.app')

@section('title', 'إنذارات الاحتيال')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">إنذارات الاحتيال</h1>
                <p class="text-gray-600">إدارة ومراجعة إنذارات الاحتيال المكتشفة بواسطة نظام الذكاء الاصطناعي</p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm">
                    <i class="fas fa-bell mr-2 text-yellow-500"></i>
                    إجمالي الإنذارات: {{ $alerts->total() }}
                </span>
            </div>
        </div>
        
        <!-- Search and Filter Bar -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <form action="{{ route('fraud-alerts.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4 md:space-x-reverse">
                    <div class="flex-grow">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ابحث عن رقم المزاد، اسم المستخدم، الخ..." 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">جميع الإنذارات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم التأكيد</option>
                            <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>تم الرفض</option>
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
        
        @if($alerts->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-12 text-center">
                    <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد إنذارات احتيال في الوقت الحالي</p>
                    <p class="text-gray-500">سيتم إظهار الإنذارات هنا عند اكتشاف أي نشاط مشبوه</p>
                </div>
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الإنذار</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المزاد</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة العرض</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($alerts as $alert)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alert->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $alert->bid->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $alert->bid->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('auctions.show', $alert->bid->auction) }}" class="text-sm text-blue-600 hover:underline">
                                            {{ Str::limit($alert->bid->auction->title, 30) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($alert->bid->bid_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ Str::limit($alert->reason, 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($alert->status == 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                قيد الانتظار
                                            </span>
                                        @elseif($alert->status == 'resolved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                تم التأكيد
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                تم الرفض
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alert->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <a href="{{ route('fraud-alerts.show', $alert) }}" class="text-blue-600 hover:text-blue-900 ml-2">عرض</a>
                                        
                                        @if($alert->status == 'pending')
                                            <form method="POST" action="{{ route('fraud-alerts.resolve', $alert) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="mx-2 text-red-600 hover:text-red-900">تأكيد</button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('fraud-alerts.dismiss', $alert) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-gray-600 hover:text-gray-900">رفض</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-6">
                    {{ $alerts->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection