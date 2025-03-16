@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-purple-600 border-2 bg-zinc-900 text-white focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm']) !!}>
