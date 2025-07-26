<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{$product->name}}
        </h2>
    </x-slot>
    <p>Price: {{$product->price}}</p>
    <p>{{$product->stock}} Left</p>
</x-app-layout>