<x-app-layout>

    <div class="mt-4 py-6 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-xl font-semibold leading-tight text-gray-900">
            Edit order
        </h1>

        <form action="{{route('orders.update', $order->id)}}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Quantity</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Individual price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Total price</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->orderItems as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    ${{ number_format($item->product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    ${{ number_format(($item->product->price) * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex-col space-y-4 w-full">
                <div class="w-full">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <input type="text" name="shipping_address" value={{$order->shipping_address}}
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="w-full flex items-end">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>