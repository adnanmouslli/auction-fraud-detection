@extends('layouts.app')

@section('title', 'إنشاء مزاد جديد')

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
                            <span class="text-sm font-medium text-gray-500">إنشاء مزاد جديد</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">إنشاء مزاد جديد</h1>
                    <p class="text-gray-600">قم بإنشاء مزاد جديد وتحديد تفاصيله</p>
                </div>
                
                <form action="{{ route('auctions.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">عنوان المزاد</label>
                        <div class="mt-1">
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">وصف المزاد</label>
                        <div class="mt-1">
                            <textarea name="description" id="description" rows="4"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Starting Price -->
                        <div>
                            <label for="starting_price" class="block text-sm font-medium text-gray-700">السعر الأولي</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="starting_price" id="starting_price" min="0.01" step="0.01" value="{{ old('starting_price') }}" required
                                    class="block w-full pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">SAR</span>
                                </div>
                            </div>
                            @error('starting_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">تاريخ البدء</label>
                            <div class="mt-1">
                                <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">تاريخ الانتهاء</label>
                            <div class="mt-1">
                                <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('auctions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            إلغاء
                        </a>
                        <button type="submit" class="mr-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            إنشاء المزاد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set default values for dates
    document.addEventListener('DOMContentLoaded', function() {
        // Default start time is now + 1 hour, rounded to nearest hour
        const startDate = new Date();
        startDate.setHours(startDate.getHours() + 1);
        startDate.setMinutes(0);
        startDate.setSeconds(0);
        
        // Default end time is now + 7 days
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 7);
        endDate.setHours(startDate.getHours());
        endDate.setMinutes(0);
        endDate.setSeconds(0);
        
        // Format dates for datetime-local input
        const formatDate = (date) => {
            return date.toISOString().slice(0, 16);
        };
        
        // Only set if user hasn't already entered values
        if (!document.getElementById('start_time').value) {
            document.getElementById('start_time').value = formatDate(startDate);
        }
        
        if (!document.getElementById('end_time').value) {
            document.getElementById('end_time').value = formatDate(endDate);
        }
    });
</script>
@endpush