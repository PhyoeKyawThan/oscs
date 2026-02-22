@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Profile</span>
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Profile Card Column -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6 text-center">
                @if(auth('admin')->user()->avatar)
                    <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                         alt="{{ auth('admin')->user()->name }}" 
                         class="rounded-full mx-auto w-36 h-36 object-cover border-4 border-white shadow-md">
                @else
                    <div class="bg-blue-50 rounded-full w-36 h-36 mx-auto flex items-center justify-center border-4 border-white shadow-md">
                        <i class="fas fa-user fa-4x text-blue-600"></i>
                    </div>
                @endif
                <h5 class="text-xl font-semibold text-gray-900 my-3">{{ auth('admin')->user()->name }}</h5>
                <p class="text-gray-500 text-sm mb-1">{{ auth('admin')->user()->email }}</p>
                <p class="text-gray-500 text-sm mb-4">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</p>
                <div class="flex justify-center">
                    <span class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full">
                        {{ auth('admin')->user()->role }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Form Column -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.profile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Full Name -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">Full Name</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                                       name="name" 
                                       value="{{ old('name', auth('admin')->user()->name) }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">Email</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="email" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                                       name="email" 
                                       value="{{ old('email', auth('admin')->user()->email) }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Picture -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">Profile Picture</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="file" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                       name="avatar" 
                                       accept="image/*">
                                <small class="text-gray-500 text-sm mt-1 block">Max file size: 2MB. Allowed: JPG, PNG, GIF</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    
                    <!-- Change Password Section -->
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h5>
                    
                    <!-- Current Password -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">Current Password</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                                       name="current_password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">New Password</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                                       name="new_password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 sm:col-span-3">
                                <h6 class="text-sm font-medium text-gray-700">Confirm Password</h6>
                            </div>
                            <div class="col-span-12 sm:col-span-9">
                                <input type="password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                                       name="new_password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-3"></div>
                        <div class="col-span-12 sm:col-span-9 flex gap-3">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Save Changes
                            </button>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview avatar before upload
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.querySelector('input[name="avatar"]');
        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const existingImg = document.querySelector('.card-body img, .bg-blue-50 img');
                        if (existingImg) {
                            existingImg.src = e.target.result;
                        } else {
                            const iconDiv = document.querySelector('.bg-blue-50');
                            if (iconDiv) {
                                // Replace icon with image
                                iconDiv.innerHTML = `<img src="${e.target.result}" class="rounded-full w-36 h-36 object-cover border-4 border-white shadow-md">`;
                            }
                        }
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    });
</script>
@endpush