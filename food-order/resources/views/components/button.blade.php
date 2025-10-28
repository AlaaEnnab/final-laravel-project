<button {{ $attributes->merge(['class' => 'px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700']) }}>
    {{ $slot }}
</button>
