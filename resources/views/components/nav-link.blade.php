@props(['active' => false, 'accent' => false])

<a {{ $attributes->class([
    'flex items-center px-4 py-2 rounded-lg transition-colors',
    'text-blue-600 bg-blue-50 dark:bg-blue-900/20' => $active,
    'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' => !$active && !$accent,
    'text-white bg-blue-600 hover:bg-blue-700' => $accent
]) }}>
    {{ $slot }}
</a>