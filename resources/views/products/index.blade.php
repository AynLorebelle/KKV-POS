<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Product</a>
                    </div>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <table class="table-auto w-full text-left whitespace-no-wrap border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm rounded-tl rounded-bl border-b">Barcode</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm border-b">Name</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm border-b">Price</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm rounded-tr rounded-br border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $product->barcode }}</td>
                                <td class="px-4 py-3">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($products->isEmpty())
                            <tr><td colspan="4" class="px-4 py-3 text-center">No products found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
