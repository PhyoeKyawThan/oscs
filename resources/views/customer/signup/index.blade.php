@extends('layouts.template')
@section('content')
    <!-- Signup Page -->
    <div id="signupPage" class="page">
        <div class="max-w-md mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold mb-6 text-center">Create Account</h2>

                <form id="signupForm">
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="name">Full Name</label>
                        <input type="text" id="signupName"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="John Doe" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="email">Email Address</label>
                        <input type="email" id="signupEmail"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="you@example.com" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="password">Password</label>
                        <input type="password" id="signupPassword"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Create a password" required>
                    </div>

                    <div class="mb-6">
                        <button type="submit"
                            class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all-300">Create
                            Account</button>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">Already have an account? <a href="{{ route('customer.login.index') }}"
                                class="text-primary font-medium hover:underline" data-page="login">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection