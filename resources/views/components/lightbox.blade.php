@once
@push('scripts')
<div x-data="{ open: false, src: '' }" 
     x-show="open"
     class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
     @keydown.escape.window="open = false">
    
    <img :src="src" class="max-w-full max-h-full object-contain">
    
    <button @click="open = false" 
            class="absolute top-4 right-4 text-white text-2xl">
        &times;
    </button>
</div>
@endpush
@endonce