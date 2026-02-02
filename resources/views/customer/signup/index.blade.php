@extends('layouts.template')
@section('content')
    <!-- Signup Page -->
    <div id="signupPage" class="page">
        <div class="max-w-md mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold mb-6 text-center">Create Account</h2>

                <!-- Success Message -->
                <div id="successMessage" class="hidden mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 rounded-lg">
                    <p class="text-green-800 dark:text-green-200" id="successText"></p>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 rounded-lg">
                    <p class="text-red-800 dark:text-red-200" id="errorText"></p>
                </div>

                <!-- Validation Errors -->
                <div id="validationErrors" class="hidden mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-700 rounded-lg">
                    <ul class="list-disc pl-5 text-yellow-800 dark:text-yellow-200" id="errorsList"></ul>
                </div>

                <form id="signupForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="name">Full Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="John Doe" required>
                        <span class="text-red-500 text-sm hidden" id="nameError"></span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="you@example.com" required>
                        <span class="text-red-500 text-sm hidden" id="emailError"></span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Create a password (min. 8 characters)" required>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <p>Password must contain:</p>
                            <ul class="list-disc pl-5">
                                <li>At least 8 characters</li>
                                <li>Uppercase and lowercase letters</li>
                                <li>At least one number</li>
                                <li>At least one symbol</li>
                            </ul>
                        </div>
                        <span class="text-red-500 text-sm hidden" id="passwordError"></span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Confirm your password" required>
                        <span class="text-red-500 text-sm hidden" id="passwordConfirmationError"></span>
                    </div>

                    <div class="mb-6">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="btnText">Create Account</span>
                            <span id="spinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">Already have an account? 
                            <a href="{{ route('customer.login.index') }}" class="text-primary font-medium hover:underline">Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const validationErrors = document.getElementById('validationErrors');
    const errorsList = document.getElementById('errorsList');
    const successText = document.getElementById('successText');
    const errorText = document.getElementById('errorText');

    // Clear all messages
    function clearMessages() {
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');
        validationErrors.classList.add('hidden');
        errorsList.innerHTML = '';
        
        // Clear individual field errors
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    // Show loading state
    function showLoading() {
        submitBtn.disabled = true;
        btnText.textContent = 'Creating Account...';
        spinner.classList.remove('hidden');
    }

    // Hide loading state
    function hideLoading() {
        submitBtn.disabled = false;
        btnText.textContent = 'Create Account';
        spinner.classList.add('hidden');
    }

    // Display validation errors
    function showValidationErrors(errors) {
        validationErrors.classList.remove('hidden');
        errorsList.innerHTML = '';
        
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                errors[field].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorsList.appendChild(li);
                });
                
                // Show field-specific errors
                const fieldErrorElement = document.getElementById(field + 'Error');
                if (fieldErrorElement) {
                    fieldErrorElement.textContent = errors[field][0];
                    fieldErrorElement.classList.remove('hidden');
                }
            }
        }
    }

    // Form submission
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        clearMessages();
        showLoading();
        
        // Gather form data
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
            _token: '{{ csrf_token() }}'
        };
        
        try {
            const response = await fetch('{{ route("customer.signup.index") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Success
                successText.textContent = data.message;
                successMessage.classList.remove('hidden');
                
                // Clear form
                signupForm.reset();
                
                // Redirect after 3 seconds
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 3000);
                }
                
            } else if (response.status === 422) {
                // Validation errors
                showValidationErrors(data.errors);
                errorText.textContent = data.message;
                errorMessage.classList.remove('hidden');
                
            } else {
                // Other errors
                errorText.textContent = data.message || 'Something went wrong. Please try again.';
                errorMessage.classList.remove('hidden');
            }
            
        } catch (error) {
            console.error('Error:', error);
            errorText.textContent = 'Network error. Please check your connection and try again.';
            errorMessage.classList.remove('hidden');
        } finally {
            hideLoading();
        }
    });

    // Real-time password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswordConfirmation() {
        const passwordConfirmationError = document.getElementById('passwordConfirmationError');
        
        if (password.value && passwordConfirmation.value && password.value !== passwordConfirmation.value) {
            passwordConfirmationError.textContent = 'Passwords do not match.';
            passwordConfirmationError.classList.remove('hidden');
            return false;
        } else {
            passwordConfirmationError.classList.add('hidden');
            return true;
        }
    }
    
    passwordConfirmation.addEventListener('input', validatePasswordConfirmation);
    password.addEventListener('input', validatePasswordConfirmation);
});
</script>
@endpush