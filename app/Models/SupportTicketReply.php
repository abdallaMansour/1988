<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'admin_id',
        'message',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function isFromUser(): bool
    {
        return $this->user_id !== null;
    }

    public function isFromAdmin(): bool
    {
        return $this->admin_id !== null;
    }
}
