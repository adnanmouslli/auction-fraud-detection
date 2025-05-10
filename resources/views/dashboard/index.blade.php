@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">لوحة التحكم</h1>
            <p class="text-gray-600">مرحباً {{ auth()->user()->name }}، إليك نظرة عامة على النظام</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Auctions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-gavel text-blue-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">إجمالي المزادات</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['total_auctions'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('dashboard.auctions') }}" class="text-blue-500 hover:text-blue-700 text-sm">عرض التفاصيل <i class="fas fa-chevron-left mr-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Active Auctions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-bullhorn text-green-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">المزادات النشطة</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['active_auctions'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('auctions.index') }}" class="text-green-500 hover:text-green-700 text-sm">عرض التفاصيل <i class="fas fa-chevron-left mr-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Total Bids -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <i class="fas fa-money-bill-wave text-yellow-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">إجمالي العروض</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['total_bids'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('bids.index') }}" class="text-yellow-500 hover:text-yellow-700 text-sm">عرض التفاصيل <i class="fas fa-chevron-left mr-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-500 bg-opacity-10">
                            <i class="fas fa-users text-indigo-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">المستخدمين</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['total_users'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-indigo-500 text-sm">إجمالي المستخدمين في النظام</span>
                    </div>
                </div>
            </div>
            
            <!-- Fraud Alerts -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-500 bg-opacity-10">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">إنذارات الاحتيال</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['total_fraud_alerts'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('fraud-alerts.index') }}" class="text-red-500 hover:text-red-700 text-sm">
                            عرض التفاصيل 
                            @if($stats['pending_alerts'] > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 mx-1">{{ $stats['pending_alerts'] }}</span>
                            @endif
                            <i class="fas fa-chevron-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Fraud Percentage -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-chart-pie text-purple-500 text-xl"></i>
                        </div>
                        <div class="mx-4">
                            <p class="text-gray-500 text-sm">نسبة الاحتيال</p>
                            <h3 class="text-2xl font-bold text-gray-700">{{ $stats['fraud_percentage'] }}%</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-purple-500 text-sm">نسبة الاحتيال الكلية في النظام</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Fraud Trend Chart -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">اتجاهات الاحتيال (آخر 30 يوم)</h2>
                <div id="fraud-trend-chart" class="h-64"></div>
            </div>
        </div>
        
        <!-- Recent Fraud Alerts -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">أحدث إنذارات الاحتيال</h2>
                
                @if($recentAlerts->isEmpty())
                    <div class="bg-gray-50 rounded-md p-4 text-center">
                        <p class="text-gray-500">لا توجد إنذارات احتيال حديثة</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الإنذار</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المزاد</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة العرض</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentAlerts as $alert)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alert->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $alert->bid->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $alert->bid->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ Str::limit($alert->bid->auction->title, 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($alert->bid->bid_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($alert->reason, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alert->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                            <a href="{{ route('fraud-alerts.show', $alert) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-left">
                        <a href="{{ route('fraud-alerts.index') }}" class="text-blue-500 hover:text-blue-700">عرض جميع الإنذارات <i class="fas fa-chevron-left mx-1"></i></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fraud Trend Chart
    const ctx = document.getElementById('fraud-trend-chart').getContext('2d');
    
    // Parse data from controller
    const fraudTrendData = @json($fraudTrend);
    
    const dates = fraudTrendData.map(item => item.date);
    const fraudRates = fraudTrendData.map(item => item.fraud_rate);
    const totalBids = fraudTrendData.map(item => item.total_bids);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'نسبة الاحتيال (%)',
                    data: fraudRates,
                    borderColor: 'rgb(220, 38, 38)',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'إجمالي العروض',
                    data: totalBids,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'نسبة الاحتيال (%)'
                    },
                    position: 'right'
                },
                y1: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'إجمالي العروض'
                    },
                    position: 'left',
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            }
        }
    });
</script>
@endpush