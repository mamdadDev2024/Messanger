<div class="relative flex items-center gap-2">
    <input 
        type="file" 
        wire:model="file" 
        class="hidden" 
        id="file-upload" 
        x-ref="fileInput"
    />

    <label 
        for="file-upload" 
        class="flex items-center justify-center w-10 h-10 rounded-full text-gray-600 dark:text-gray-300 
               hover:bg-gray-200 dark:hover:bg-gray-700 bg-white dark:bg-gray-800 
               transition-all duration-200 ease-in-out shadow-sm cursor-pointer"
        x-data="{ hovering: false }"
        @mouseenter="hovering = true" 
        @mouseleave="hovering = false"
    >
        <i class="fas fa-paperclip text-lg transition-transform duration-300" 
           :class="hovering ? 'rotate-12 scale-110 text-blue-500 dark:text-blue-400' : ''">
        </i>
    </label>

    @error('file')
        <div class="flex items-center gap-2 mt-2 text-red-600 dark:text-red-400 text-sm bg-red-100 dark:bg-red-800 rounded px-3 py-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror

    @if($uploading)
        <div class="absolute top-full left-0 w-60 mt-3">
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 text-center">
                {{ __('در حال آپلود...') }} {{ $progress }}%
            </p>
        </div>
    @endif
</div>
