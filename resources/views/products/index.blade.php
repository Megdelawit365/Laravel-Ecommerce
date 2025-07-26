<x-app-layout>

    <body class="bg-gray-100 text-gray-800">

        <div class="max-w-6xl mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Products</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                            class="w-full h-48 object-cover rounded-md mb-4">
                        <div>
                            <h5 class="text-lg font-semibold">{{ $product->name }}</h5>
                            <p class="text-gray-600">{{ $product->price }} Birr</p>
                            <a href="{{route('products.show', $product->id)}}"
                                class="inline-block mt-2 text-blue-600 hover:underline">View Details</a>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </body>

</x-app-layout>