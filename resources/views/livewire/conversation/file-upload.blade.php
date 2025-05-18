<div class="relative">
    <input type="file" 
           wire:model="file" 
           class="hidden" 
           id="file-upload" />
    
    <label for="file-upload" 
           class="btn btn-circle btn-ghost">
        <i class="fas fa-paperclip"></i>
    </label>

    @error('file')
        <div class="alert alert-error mt-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror

    @if($uploading)
        <div class="mt-4">
            <progress class="progress progress-primary w-full" value="{{ $progress }}" max="100"></progress>
            <p class="mt-2 text-sm opacity-70">{{ __('Uploading...') }} {{ $progress }}%</p>
        </div>
    @endif
</div> 