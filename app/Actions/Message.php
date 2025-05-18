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
        $file = null;
        if ($event->file) {
            $file = self::upload($event->file);
            $fileId = $file->id;
        }

        $message = MessageModel::create([
            'text' => $event->text,
            'conversation_id' => $event->conversation_id,
            'sender_id' => $event->sender_id,
            'file_id' => $fileId,
            'reply_to_id' => $event->reply_to_id,
            'forwarded_from_id' => $event->forwarded_from_id,
            'status' => 'sent',
        ]);
        Log::debug('On ActionMessage/store Fun => ' . $message->text);

        return $message->toArray();
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

    public static function update($event)
    {
        $message = MessageModel::findOrFail($event->message_id);
        $fileId = $message->file_id;
        if ($event->file) {
            $file = self::upload($event->file);
            $fileId = $file->id;
        }
        $message->update([
            'text' => $event->text,
            'file_id' => $fileId,
            'status' => 'edited',
        ]);
        return $message->toArray();
    }

    public static function delete($event)
    {
        $message = MessageModel::findOrFail($event->message_id);
        $message->delete();
    }
}
