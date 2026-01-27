@extends('layouts.template')
@section('content')
    <div id="categoriesPage" class="page">
        <h2 class="text-3xl font-bold mb-8">Product Categories</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="category-large bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-8 text-white">
                <div class="flex items-center mb-4">
                    <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-laptop text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">Electronics</h3>
                </div>
                <p class="mb-6 opacity-90">Latest smartphones, laptops, tablets, smartwatches, and accessories.</p>
                <a href="#"
                    class="inline-block bg-white text-blue-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                    data-category="electronics">Browse Electronics</a>
            </div>

            <div class="category-large bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-8 text-white">
                <div class="flex items-center mb-4">
                    <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-tshirt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">Fashion</h3>
                </div>
                <p class="mb-6 opacity-90">Clothing, shoes, bags, jewelry, and accessories for all styles.</p>
                <a href="#"
                    class="inline-block bg-white text-purple-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                    data-category="fashion">Browse Fashion</a>
            </div>

            <div class="category-large bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-8 text-white">
                <div class="flex items-center mb-4">
                    <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-home text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">Home & Kitchen</h3>
                </div>
                <p class="mb-6 opacity-90">Furniture, kitchen appliances, home decor, bedding, and more.</p>
                <a href="#"
                    class="inline-block bg-white text-green-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                    data-category="home">Browse Home & Kitchen</a>
            </div>
        </div>
    </div>
@endsection