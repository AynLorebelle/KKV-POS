@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-accent focus:border-accent focus:ring-2 focus:ring-primary rounded-lg shadow-sm bg-white text-accent px-4 py-2 font-medium']) }}>
