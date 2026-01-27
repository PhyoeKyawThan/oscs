@extends('layouts.template')
@section('content')
<!-- Login Page -->
<div id="loginPage" class="page">
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
            <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

            <form id="loginForm">
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2" for="email">Email Address</label>
                    <input type="email" id="loginEmail"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="you@example.com" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2" for="password">Password</label>
                    <input type="password" id="loginPassword"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Enter your password" required>
                </div>

                <div class="mb-6">
                    <button type="submit"
                        class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all-300">Login</button>
                </div>

                <div class="text-center">
                    <p class="text-gray-500 dark:text-gray-400">Don't have an account? <a href="{{ route('customer.signup.index') }}"
                            class="text-primary font-medium hover:underline" data-page="signup">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection