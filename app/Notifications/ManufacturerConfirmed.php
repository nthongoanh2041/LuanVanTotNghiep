<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ManufacturerConfirmed extends Notification
{
    use Queueable;

    public $request;
    public $status;

    public function __construct($request, $status)
    {
        $this->request = $request;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // lưu DB
    }

   public function toDatabase($notifiable)
{
    return [
        'message' => "{$this->status} yêu cầu cho sản phẩm: "
            . ($this->request->product->name ?? 'Không xác định'),
        'request_id' => $this->request->id,
        'status' => $this->status,
    ];
}
}
