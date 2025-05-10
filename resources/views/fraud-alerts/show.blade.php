<!-- resources/views/fraud-alerts/show.blade.php -->
@extends('layouts.app')

@section('title', 'تفاصيل إنذار الاحتيال')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 space-x-reverse md:space-x-3 md:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-chart-line mx-2"></i>
                            لوحة التحكم
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-left text-gray-400 text-sm mx-2"></i>
                            <a href="{{ route('fraud-alerts.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                إنذارات الاحتيال
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-left text-gray-400 text-sm mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">إنذار #{{ $fraudAlert->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">تفاصيل إنذار الاحتيال</h1>
                        <p class="text-gray-600">إنذار رقم: {{ $fraudAlert->id }}</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        @if($fraudAlert->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> قيد الانتظار
                            </span>
                        @elseif($fraudAlert->status == 'resolved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> تم التأكيد
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times mr-1"></i> تم الرفض
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <!-- Alert Details -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الإنذار</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">رقم الإنذار</p>
                                    <p class="text-gray-900 font-medium">{{ $fraudAlert->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ الإنشاء</p>
                                    <p class="text-gray-900">{{ $fraudAlert->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">الحالة</p>
                                    <p class="text-gray-900">
                                        @if($fraudAlert->status == 'pending')
                                            <span class="text-yellow-600">قيد الانتظار</span>
                                        @elseif($fraudAlert->status == 'resolved')
                                            <span class="text-green-600">تم التأكيد</span>
                                        @else
                                            <span class="text-gray-600">تم الرفض</span>
                                        @endif
                                    </p>
                                </div>
                                
                                @if($fraudAlert->status != 'pending')
                                    <div>
                                        <p class="text-sm text-gray-500">تم المعالجة بواسطة</p>
                                        <p class="text-gray-900">{{ $fraudAlert->resolver ? $fraudAlert->resolver->name : 'غير معروف' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">تاريخ المعالجة</p>
                                        <p class="text-gray-900">{{ $fraudAlert->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Bid Details -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل العرض</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">رقم العرض</p>
                                    <p class="text-gray-900 font-medium">{{ $fraudAlert->bid->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">قيمة العرض</p>
                                    <p class="text-gray-900 font-bold">{{ number_format($fraudAlert->bid->bid_amount, 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ العرض</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">درجة مؤشر الاحتيال</p>
                                    <p class="text-gray-900 font-medium">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fraudAlert->bid->fraud_score > 0.7 ? 'bg-red-100 text-red-800' : ($fraudAlert->bid->fraud_score > 0.4 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ number_format($fraudAlert->bid->fraud_score * 100, 1) }}%
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">سبب الاشتباه</p>
                                <p class="text-gray-900 mt-1">{{ $fraudAlert->reason }}</p>
                            </div>
                        </div>
                        
                        <!-- User Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">بيانات المستخدم</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">المستخدم</p>
                                    <p class="text-gray-900 font-medium">{{ $fraudAlert->bid->user->name }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->user->email }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">رقم المستخدم</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->user->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ التسجيل</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->user->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">نشاط المستخدم</p>
                                <div class="grid grid-cols-2 gap-4 mt-1">
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <p class="text-gray-500 text-xs">عدد المزايدات</p>
                                        <p class="text-gray-900 font-bold">{{ $fraudAlert->bid->user->bids()->count() }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <p class="text-gray-500 text-xs">عدد الاحتيالات المكتشفة</p>
                                        <p class="text-gray-900 font-bold">{{ $fraudAlert->bid->user->bids()->where('is_fraud', true)->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Auction Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">بيانات المزاد</h2>
                                <a href="{{ route('auctions.show', $fraudAlert->bid->auction) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    عرض المزاد <i class="fas fa-external-link-alt mr-1"></i>
                                </a>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">عنوان المزاد</p>
                                    <p class="text-gray-900 font-medium">{{ $fraudAlert->bid->auction->title }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">رقم المزاد</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->auction->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">السعر الحالي</p>
                                    <p class="text-gray-900 font-bold">{{ number_format($fraudAlert->bid->auction->current_price, 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">السعر الأولي</p>
                                    <p class="text-gray-900">{{ number_format($fraudAlert->bid->auction->starting_price, 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">حالة المزاد</p>
                                    <p class="text-gray-900">
                                        @if($fraudAlert->bid->auction->status == 'active')
                                            <span class="text-green-600">نشط</span>
                                        @elseif($fraudAlert->bid->auction->status == 'completed')
                                            <span class="text-blue-600">مكتمل</span>
                                        @else
                                            <span class="text-red-600">ملغي</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">البائع</p>
                                    <p class="text-gray-900">{{ $fraudAlert->bid->auction->seller->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">الإجراءات</h3>
                            
                            @if($fraudAlert->status == 'pending')
                                <div class="space-y-4">
                                    <form method="POST" action="{{ route('fraud-alerts.resolve', $fraudAlert) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-check-circle mr-2"></i> تأكيد الاحتيال
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('fraud-alerts.dismiss', $fraudAlert) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-times-circle mr-2"></i> رفض الإنذار
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            @if($fraudAlert->status == 'resolved')
                                                <i class="fas fa-check-circle text-green-400"></i>
                                            @else
                                                <i class="fas fa-times-circle text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div class="mr-3">
                                            <h3 class="text-sm font-medium {{ $fraudAlert->status == 'resolved' ? 'text-green-800' : 'text-gray-800' }}">
                                                {{ $fraudAlert->status == 'resolved' ? 'تم تأكيد الاحتيال' : 'تم رفض الإنذار' }}
                                            </h3>
                                            <div class="mt-2 text-sm {{ $fraudAlert->status == 'resolved' ? 'text-green-700' : 'text-gray-700' }}">
                                                <p>تم {{ $fraudAlert->status == 'resolved' ? 'تأكيد' : 'رفض' }} هذا الإنذار بتاريخ {{ $fraudAlert->updated_at->format('Y-m-d H:i') }}</p>
                                                <p class="mt-1">بواسطة: {{ $fraudAlert->resolver ? $fraudAlert->resolver->name : 'غير معروف' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Bid Actions -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات العرض</h3>
                            
                            <div class="space-y-4">
                                @if(!$fraudAlert->bid->is_fraud)
                                    <form method="POST" action="{{ route('bids.mark-fraud', $fraudAlert->bid) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-ban mr-2"></i> تعليم كاحتيال
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('bids.mark-legitimate', $fraudAlert->bid) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-check mr-2"></i> تعليم كعرض سليم
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <!-- User Actions -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">المستخدم المشتبه به</h3>
                            
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $fraudAlert->bid->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $fraudAlert->bid->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <a href="#" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-eye mr-2"></i> عرض نشاط المستخدم
                                </a>
                                
                                <a href="{{ route('bids.index', ['user_id' => $fraudAlert->bid->user->id]) }}" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-history mr-2"></i> سجل المزايدات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection