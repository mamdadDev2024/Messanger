<?php

namespace App\Livewire\Conversation;

use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class FileUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $conversationId;
    public $uploading = false;
    public $progress = 0;

    protected $listeners = [
        'upload:started' => 'handleUploadStarted',
        'upload:finished' => 'handleUploadFinished',
        'upload:errored' => 'handleUploadErrored',
        'upload:progress' => 'handleUploadProgress',
    ];

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $this->uploading = true;
    }

    public function handleUploadStarted()
    {
        $this->uploading = true;
        $this->progress = 0;
    }

    public function handleUploadProgress($progress)
    {
        $this->progress = $progress;
    }

    public function handleUploadFinished()
    {
        $this->uploading = false;
        $this->progress = 100;

        $originalName = $this->file->getClientOriginalName();
        $extension = $this->file->getClientOriginalExtension();
        $mimeType = $this->file->getMimeType();
        
        $filename = Str::uuid() . '.' . $extension;
        $path = $this->file->storeAs('uploads/' . date('Y/m'), $filename, 'public');
        
        $file = File::create([
            'url' => $path,
            'type' => $extension,
            'size' => $this->file->getSize(),
            'mime_type' => $mimeType,
            'original_name' => $originalName,
            'class' => 'pending'
        ]);

        $this->emit('fileUploaded', $file->id);
        $this->reset('file');
    }

    public function handleUploadErrored()
    {
        $this->uploading = false;
        $this->addError('file', 'Upload failed');
    }

    public function render()
    {
        return view('livewire.conversation.file-upload');
    }
} 