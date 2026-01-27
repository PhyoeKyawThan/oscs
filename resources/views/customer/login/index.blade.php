@extends('layouts.template')
@section('content')
<div id="loginPage" class="page">
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Welcome Back</h2>
                <p class="text-gray-500 dark:text-gray-400">Sign in to your account</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-600 dark:text-red-400 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-600 dark:text-green-400 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                        <p class="text-blue-600 dark:text-blue-400 text-sm">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('doLogin') }}" novalidate>
                @csrf
                
                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="pl-10 w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="you@example.com" 
                               required
                               autocomplete="email"
                               autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="pl-10 w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="Enter your password" 
                               required
                               autocomplete="current-password">
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('password', this)">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>
                    
                </div>

                <!-- Submit Button -->
                <div class="mb-6">
                    <button type="submit" 
                            id="loginButton"
                            class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all-300 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span>Sign In</span>
                        <span id="loginSpinner" class="ml-2 hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>

                <!-- Sign Up Link -->
                <div class="text-center">
                    <p class="text-gray-500 dark:text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('customer.signup.index') }}" 
                           class="text-primary font-medium hover:underline">
                            Sign up here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginSpinner = document.getElementById('loginSpinner');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            loginButton.disabled = true;
            loginSpinner.classList.remove('hidden');
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                loginButton.disabled = false;
                loginSpinner.classList.add('hidden');
                return;
            }
            
        });
    }

    setTimeout(() => {
        const errorMessages = document.querySelectorAll('.bg-red-50, .bg-green-50, .bg-blue-50');
        errorMessages.forEach(msg => {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease';
            setTimeout(() => msg.remove(), 500);
        });
    }, 5000);
    
    const emailField = document.getElementById('email');
    if (emailField && !emailField.value) {
        emailField.focus();
    }
});
</script>
@endpush