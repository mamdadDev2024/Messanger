@props(['user', 'size' => 'md', 'status' => false])

<div class="relative">
    <div @class([
        'rounded-full flex items-center justify-center bg-primary-500 text-white',
        'w-8 h-8 text-sm' => $size === 'sm',
        'w-12 h-12 text-lg' => $size === 'md',
        'w-16 h-16 text-xl' => $size === 'lg'
    ])>
        {{ substr($user->name, 0, 1) }}
    </div>
    @if($status)
        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></div>
    @endif
</div>