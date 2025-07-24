<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.8s ease-out; }
    </style>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8"
      x-data="{ transitioning: false, errorMessage: '', bgImage: '' }"
      :style="'background-image: url(' + bgImage + '); background-size: cover; background-position: center;'">

    <div class="w-full max-w-md p-8 space-y-6 bg-gray-800 bg-opacity-60 backdrop-blur-lg rounded-xl shadow-xl fade-in"
         x-init="$el.classList.add('fade-in')">
         
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-white">Log in to your account</h2>
            <p class="mt-2 text-sm text-gray-400">
                Or
                <a href="#" @click.prevent="transitioning = true; setTimeout(() => window.location.href = '{{ route('register') }}', 500)"
                   class="font-medium text-[#FF2D20] hover:text-[#FF4E40] transition duration-300">
                    register for a new account
                </a>
            </p>
        </div>

        <form class="space-y-6" action="{{ route('login') }}" method="POST" x-data="{ showPassword: false }"
              @submit.prevent="errorMessage = 'Invalid email or password'">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email address</label>
                    <input id="email" name="email" type="email" required
                           class="w-full px-4 py-3 border border-gray-600 bg-gray-900 text-white rounded-lg focus:outline-none focus:ring-[#FF2D20] focus:border-[#FF2D20]"
                           placeholder="Email address">
                </div>
                
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                           class="w-full px-4 py-3 border border-gray-600 bg-gray-900 text-white rounded-lg focus:outline-none focus:ring-[#FF2D20] focus:border-[#FF2D20]"
                           placeholder="Password">
                    
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-4 flex items-center text-sm text-gray-400 hover:text-white">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 15.232a8 8 0 0 1-10.464-10.464m10.464 10.464a8 8 0 0 0 10.464-10.464m-10.464 10.464A8 8 0 0 1 4.707 9.293m0 0A8 8 0 0 1 15.232 15.232z" />
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.5c-5.5 0-9 7.5-9 7.5s3.5 7.5 9 7.5 9-7.5 9-7.5-3.5-7.5-9-7.5z" />
                        </svg>
                    </button>
                </div>
            </div>

            <p class="text-red-500 text-sm" x-show="errorMessage" x-text="errorMessage"></p>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox"
                           class="h-4 w-4 text-[#FF2D20] focus:ring-[#FF2D20] border-gray-600 rounded">
                    <label for="remember-me" class="ml-2 text-sm">Remember me</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="text-[#FF2D20] hover:text-[#FF4E40] transition duration-300">Forgot your password?</a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full py-3 rounded-lg bg-[#FF2D20] text-white font-medium hover:bg-[#FF4E40] transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF2D20]">
                    Log in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
