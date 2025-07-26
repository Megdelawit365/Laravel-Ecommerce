<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Cart</h1>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($cartItems as $cartItem)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $cartItem->product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($cartItem->product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $cartItem->product->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $cartItem->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 flex items-center space-x-3">
                                <form action="{{route('cart.delete', $cartItem->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href={{route('cart.checkout')}}
            class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">
            Checkout</a>
    </div>
</x-app-layout>