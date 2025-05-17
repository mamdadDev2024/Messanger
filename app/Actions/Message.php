<?php

namespace App\Actions;

use App\Models\File;
use App\Models\Message as MessageModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Message
{
    public static function store($event)
    {
        $fileId = null;
        if ($event->file) {
            $file = self::upload($event->file);
            $fileId = $file->id;
        }

        $message = MessageModel::create([
            'text' => $event->text,
            'conversation_id' => $event->conversation_id,
            'type' => $event->conversation_type,
            'file_id' => $fileId,
        ]);
        Log::debug('On ActionMessage/store Fun => ' . $message->text);

    }

    public static function upload($file)
    {
        $path = Storage::put('uploads', $file['content']);
        return File::create([
            'type' => $file['type'],
            'size' => $file['size'],
            'url' => $path,
        ]);
        Log::debug('On ActionMessage/Upload Fun => ' . $file->url);
    }
}
