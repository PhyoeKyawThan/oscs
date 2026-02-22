<footer class="bg-gray-50 border-t mt-10">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm text-gray-500">
            <p class="mb-2 md:mb-0">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>

            <p class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-gray-200 rounded text-xs font-medium">
                    v1.0.0
                </span>
                <span>{{ now()->format('F d, Y') }}</span>
            </p>
        </div>
    </div>
</footer>