<?php

namespace App\Livewire;

use App\Events\Message\Sent;
use Livewire\Component;
use Livewire\WithFileUploads;

class Home extends Component
{
    use WithFileUploads;  // این Trait برای آپلود فایل لازم است

    public $text = '';
    public $file;

    public function render()
    {
        return view('livewire.home');
    }

    public function sendMessage()
    {
        // اول اعتبارسنجی
        $this->validate([
            'text' => 'required|string',
            'file' => 'nullable|file|max:10240', 
        ]);

        $fileDetails = null;

        if ($this->file) {

            $path = $this->file->storeAs('public','uploads');

            $fileDetails = [
                'url' => $path, 
                'type' => $this->file->getMimeType(),
                'size' => $this->file->getSize()
            ];
        }

        broadcast(new Sent(
            $this->text,
            'group',
            1,
            1,
            $fileDetails,
            10
        ));
        $this->reset(['text', 'file']);
    }
}
