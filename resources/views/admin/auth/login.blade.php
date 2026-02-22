<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Custom styles for input groups since Tailwind doesn't have input-group */
        .input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }
        
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            color: #6b7280;
            text-align: center;
            white-space: nowrap;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem 0 0 0.5rem;
            border-right: none;
        }
        
        .input-group .form-control {
            border-radius: 0 0.5rem 0.5rem 0;
            border-left: none;
        }
        
        .input-group .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
            border-left: none;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-5">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#4361ee] to-[#3f37c9] text-white px-8 py-10 text-center">
            <i class="fas fa-lock text-5xl mb-5"></i>
            <h1 class="text-2xl font-semibold m-0">Admin Login</h1>
        </div>
        
        <!-- Body -->
        <div class="p-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-700 {{ !$loop->last ? 'mb-1' : '' }}">{{ $error }}</p>
                            @endforeach
                        </div>
                        <button type="button" class="ml-auto text-red-400 hover:text-red-600" onclick="this.closest('div[role=alert]').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control flex-1 px-4 py-3 text-sm border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email" 
                               required>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control flex-1 px-4 py-3 text-sm border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password" 
                               required>
                    </div>
                </div>
                
                <!-- Remember Me Checkbox -->
                <div class="mb-5">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-[#4361ee] to-[#3f37c9] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>