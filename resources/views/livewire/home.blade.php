<form wire:submit.prevent='sendMessage' class="fieldset">
    <legend class="fieldset-legend">Page title</legend>
    <input type="text" wire:model='text' class="input text-white" placeholder="a text ..." />
    @error('text')
        <span class=" alert-error ">{{$message}}</span>
    @enderror
        <input type='file' wire:model='file' class=" file-input">
    @error('file')
        <span class=" alert-error ">{{$message}}</span>
    @enderror
    <p class="label">For Test Back-End Structure</p>
    <button class=" btn btn-primary">ارسال</button>
</form>