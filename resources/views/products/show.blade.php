<x-app-layout>

    <div class="mt-4 py-6 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold leading-tight text-gray-900">
            {{$product->name}}
        </h2>
        <p class="text-lg">Price: <span class="font-semibold text-gray-900">${{$product->price}}</span></p>
        <p class="text-lg">{{$product->stock}} Left</p>

        <form action="{{route('cart.store', $product->id)}}" method="POST" class="mt-4">
            @csrf
            @method('POST')

            <div class="mt-4 flex space-x-4">
                <div class="w-1/2">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{$product->stock}}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <input type="hidden" name="product_id" value="{{$product->id}}">

                <div class="w-1/2 flex items-end">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add to Cart
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>