@extends('layouts.template')
@section('content')
    <!-- Profile Page -->
    <div id="profilePage" class="page">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your account settings and preferences</p>
            </div>

            <!-- Success/Error Messages -->
            <div id="successMessage" class="hidden mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 dark:text-green-300" id="successText"></p>
                </div>
            </div>

            <div id="errorMessage" class="hidden mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-800 dark:text-red-300" id="errorText"></p>
                </div>
            </div>

            <!-- Validation Errors -->
            <div id="validationErrors" class="hidden mb-6 p-4 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-700 rounded-lg">
                <ul class="list-disc pl-5 text-yellow-800 dark:text-yellow-300 space-y-1" id="errorsList"></ul>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Profile Overview -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Personal Information</h2>
                            <button onclick="toggleEdit('personalInfo')" 
                                class="text-primary hover:text-blue-700 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                        </div>

                        <!-- View Mode -->
                        <div id="personalInfoView">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Full Name</p>
                                    <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewName">{{ $user->name ?? 'Not set' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email Address</p>
                                    <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewEmail">{{ $user->email ?? 'Not set' }}</p>
                                    @if($user->hasVerifiedEmail())
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 mt-1">
                                            Not Verified
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Phone Number</p>
                                    <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewPhone">{{ $user->phone ?? 'Not set' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                                    <p class="text-gray-900 dark:text-white font-medium mt-1">{{ $user->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                                <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewAddress">{{ $user->address ?? 'Not set' }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">City</p>
                                        <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewCity">{{ $user->city ?? 'Not set' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Country</p>
                                        <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewCountry">{{ $user->country ?? 'Not set' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Postal Code</p>
                                        <p class="text-gray-900 dark:text-white font-medium mt-1" id="viewPostalCode">{{ $user->postal_code ?? 'Not set' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div id="personalInfoEdit" class="hidden">
                            <form id="personalInfoForm">
                                @csrf
                                <input type="hidden" name="action" value="update_profile">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editName">Full Name</label>
                                        <input type="text" id="editName" name="name" value="{{ $user->name }}"
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                        <span class="text-red-500 text-sm hidden" id="nameError"></span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editPhone">Phone Number</label>
                                            <input type="tel" id="editPhone" name="phone" value="{{ $user->phone }}"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                            <span class="text-red-500 text-sm hidden" id="phoneError"></span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editCity">City</label>
                                            <input type="text" id="editCity" name="city" value="{{ $user->city }}"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                            <span class="text-red-500 text-sm hidden" id="cityError"></span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editAddress">Address</label>
                                        <textarea id="editAddress" name="address" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ $user->address }}</textarea>
                                        <span class="text-red-500 text-sm hidden" id="addressError"></span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editCountry">Country</label>
                                            <select id="editCountry" name="country"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                                <option value="">Select Country</option>
                                                <option value="USA" {{ $user->country == 'USA' ? 'selected' : '' }}>United States</option>
                                                <option value="Canada" {{ $user->country == 'Canada' ? 'selected' : '' }}>Canada</option>
                                                <option value="UK" {{ $user->country == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                                <option value="Australia" {{ $user->country == 'Australia' ? 'selected' : '' }}>Australia</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                            <span class="text-red-500 text-sm hidden" id="countryError"></span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editPostalCode">Postal Code</label>
                                            <input type="text" id="editPostalCode" name="postal_code" value="{{ $user->postal_code }}"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                            <span class="text-red-500 text-sm hidden" id="postalCodeError"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <button type="button" onclick="toggleEdit('personalInfo')"
                                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            Cancel
                                        </button>
                                        <button type="submit" id="personalInfoSubmit"
                                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span id="personalInfoBtnText">Save Changes</span>
                                            <span id="personalInfoSpinner" class="hidden ml-2">
                                                <svg class="animate-spin h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Settings Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Security Settings</h2>
                        
                        <!-- Change Email -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Change Email Address</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your email address</p>
                                </div>
                                <button onclick="toggleEdit('changeEmail')"
                                    class="text-primary hover:text-blue-700 font-medium text-sm">
                                    Change Email
                                </button>
                            </div>
                            
                            <!-- Change Email Form -->
                            <div id="changeEmailForm" class="hidden mt-4">
                                <form id="emailUpdateForm">
                                    @csrf
                                    <input type="hidden" name="action" value="update_email">
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="currentPasswordEmail">Current Password</label>
                                            <input type="password" id="currentPasswordEmail" name="current_password"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                required>
                                            <span class="text-red-500 text-sm hidden" id="currentPasswordEmailError"></span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="newEmail">New Email Address</label>
                                            <input type="email" id="newEmail" name="new_email"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                required>
                                            <span class="text-red-500 text-sm hidden" id="newEmailError"></span>
                                        </div>
                                        
                                        <div class="flex justify-end space-x-4">
                                            <button type="button" onclick="toggleEdit('changeEmail')"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit" id="emailUpdateSubmit"
                                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span id="emailUpdateBtnText">Update Email</span>
                                                <span id="emailUpdateSpinner" class="hidden ml-2">
                                                    <svg class="animate-spin h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Change Password -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Change Password</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your password for enhanced security</p>
                                </div>
                                <button onclick="toggleEdit('changePassword')"
                                    class="text-primary hover:text-blue-700 font-medium text-sm">
                                    Change Password
                                </button>
                            </div>
                            
                            <!-- Change Password Form -->
                            <div id="changePasswordForm" class="hidden mt-4">
                                <form id="passwordUpdateForm">
                                    @csrf
                                    <input type="hidden" name="action" value="update_password">
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="currentPassword">Current Password</label>
                                            <input type="password" id="currentPassword" name="current_password"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                required>
                                            <span class="text-red-500 text-sm hidden" id="currentPasswordError"></span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="newPassword">New Password</label>
                                            <input type="password" id="newPassword" name="new_password"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                required>
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Password must contain at least 8 characters, including uppercase, lowercase, numbers, and symbols.
                                            </div>
                                            <span class="text-red-500 text-sm hidden" id="newPasswordError"></span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="newPasswordConfirmation">Confirm New Password</label>
                                            <input type="password" id="newPasswordConfirmation" name="new_password_confirmation"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                required>
                                            <span class="text-red-500 text-sm hidden" id="newPasswordConfirmationError"></span>
                                        </div>
                                        
                                        <div class="flex justify-end space-x-4">
                                            <button type="button" onclick="toggleEdit('changePassword')"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit" id="passwordUpdateSubmit"
                                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span id="passwordUpdateBtnText">Update Password</span>
                                                <span id="passwordUpdateSpinner" class="hidden ml-2">
                                                    <svg class="animate-spin h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Account Summary -->
                <div class="space-y-8">
                    <!-- Account Summary Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Account Summary</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Account Status</p>
                                    <p class="font-medium text-gray-900 dark:text-white">Active</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email Verification</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $user->hasVerifiedEmail() ? 'Verified' : 'Pending' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-red-200 dark:border-red-800">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Danger Zone</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                            Irreversible and destructive actions. Proceed with caution.
                        </p>
                        
                        <button onclick="showDeleteAccountModal()"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete Account</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    This action cannot be undone. All your data will be permanently deleted.
                </p>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    To confirm, please type <span class="font-mono text-red-600 dark:text-red-400">DELETE MY ACCOUNT</span> below:
                </p>
                <input type="text" id="deleteConfirmation" 
                    class="w-full mt-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Type DELETE MY ACCOUNT">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="hideDeleteAccountModal()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button id="deleteAccountBtn" onclick="deleteAccount()" disabled
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Global state
let editMode = {
    personalInfo: false,
    changeEmail: false,
    changePassword: false
};

// Toggle edit mode
function toggleEdit(section) {
    editMode[section] = !editMode[section];
    
    switch(section) {
        case 'personalInfo':
            const view = document.getElementById('personalInfoView');
            const edit = document.getElementById('personalInfoEdit');
            view.classList.toggle('hidden');
            edit.classList.toggle('hidden');
            break;
            
        case 'changeEmail':
            const emailForm = document.getElementById('changeEmailForm');
            emailForm.classList.toggle('hidden');
            break;
            
        case 'changePassword':
            const passwordForm = document.getElementById('changePasswordForm');
            passwordForm.classList.toggle('hidden');
            break;
    }
}

// Clear all messages
function clearMessages() {
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('validationErrors').classList.add('hidden');
    document.getElementById('errorsList').innerHTML = '';
    
    // Clear all error spans
    document.querySelectorAll('[id$="Error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
}

// Show loading state for a button
function showLoading(buttonId) {
    const button = document.getElementById(buttonId);
    const btnText = document.getElementById(buttonId.replace('Submit', 'BtnText'));
    const spinner = document.getElementById(buttonId.replace('Submit', 'Spinner'));
    
    if (button && btnText && spinner) {
        button.disabled = true;
        spinner.classList.remove('hidden');
    }
}

// Hide loading state
function hideLoading(buttonId) {
    const button = document.getElementById(buttonId);
    const btnText = document.getElementById(buttonId.replace('Submit', 'BtnText'));
    const spinner = document.getElementById(buttonId.replace('Submit', 'Spinner'));
    
    if (button && btnText && spinner) {
        button.disabled = false;
        spinner.classList.add('hidden');
    }
}

// Show success message
function showSuccess(message) {
    clearMessages();
    document.getElementById('successText').textContent = message;
    document.getElementById('successMessage').classList.remove('hidden');
    
    // Hide success message after 5 seconds
    setTimeout(() => {
        document.getElementById('successMessage').classList.add('hidden');
    }, 5000);
}

// Show error message
function showError(message) {
    clearMessages();
    document.getElementById('errorText').textContent = message;
    document.getElementById('errorMessage').classList.remove('hidden');
}

// Show validation errors
function showValidationErrors(errors) {
    clearMessages();
    
    const errorsList = document.getElementById('errorsList');
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
    
    document.getElementById('validationErrors').classList.remove('hidden');
}

// Update profile view with new data
function updateProfileView(userData) {
    const fields = {
        'viewName': userData.name,
        'viewEmail': userData.email,
        'viewPhone': userData.phone,
        'viewAddress': userData.address,
        'viewCity': userData.city,
        'viewCountry': userData.country,
        'viewPostalCode': userData.postal_code
    };
    
    for (const [id, value] of Object.entries(fields)) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }
}

// Personal Information Form Submission
document.getElementById('personalInfoForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    clearMessages();
    showLoading('personalInfoSubmit');
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("profile.index") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showSuccess(data.message);
            updateProfileView(data.user);
            toggleEdit('personalInfo');
        } else if (response.status === 422) {
            showValidationErrors(data.errors);
        } else {
            showError(data.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    } finally {
        hideLoading('personalInfoSubmit');
    }
});

// Email Update Form Submission
document.getElementById('emailUpdateForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    clearMessages();
    showLoading('emailUpdateSubmit');
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("profile.index") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showSuccess(data.message);
            document.getElementById('viewEmail').textContent = document.getElementById('newEmail').value;
            document.getElementById('emailUpdateForm').reset();
            toggleEdit('changeEmail');
        } else if (response.status === 422) {
            showValidationErrors(data.errors);
        } else {
            showError(data.message || 'Failed to update email');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    } finally {
        hideLoading('emailUpdateSubmit');
    }
});

// Password Update Form Submission
document.getElementById('passwordUpdateForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    clearMessages();
    showLoading('passwordUpdateSubmit');
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("profile.index") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showSuccess(data.message);
            document.getElementById('passwordUpdateForm').reset();
            toggleEdit('changePassword');
        } else if (response.status === 422) {
            showValidationErrors(data.errors);
        } else {
            showError(data.message || 'Failed to update password');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    } finally {
        hideLoading('passwordUpdateSubmit');
    }
});

// Delete Account Modal Functions
function showDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('deleteConfirmation').value = '';
    document.getElementById('deleteAccountBtn').disabled = true;
}

// Delete account confirmation input validation
document.getElementById('deleteConfirmation')?.addEventListener('input', function(e) {
    const deleteBtn = document.getElementById('deleteAccountBtn');
    if (e.target.value === 'DELETE MY ACCOUNT') {
        deleteBtn.disabled = false;
    } else {
        deleteBtn.disabled = true;
    }
});

// Delete account function
async function deleteAccount() {
    if (!confirm('Are you absolutely sure? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("profile.delete") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showSuccess('Your account has been deleted successfully.');
            setTimeout(() => {
                window.location.href = '{{ route("customer.home.index") }}';
            }, 2000);
        } else {
            showError(data.message || 'Failed to delete account');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    } finally {
        hideDeleteAccountModal();
    }
}

// Load profile data on page load (optional)
document.addEventListener('DOMContentLoaded', function() {
    // You can load additional profile data here if needed
    console.log('Profile page loaded');
});
</script>
@endpush

@push('styles')
<style>
.transition-all-300 {
    transition: all 0.3s ease;
}

/* Custom scrollbar for dark mode */
.dark ::-webkit-scrollbar {
    width: 8px;
}

.dark ::-webkit-scrollbar-track {
    background: #1f2937;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>
@endpush