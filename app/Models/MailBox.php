<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailBox extends Model
{
    use HasFactory;

    protected $table = "mail_box";
    protected $fillable = [
        'sender_id',
        'recipient',
        'mail_text',
        'subject',
        'is_read',
        'reply_to'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'recipient');
    }

    public function sender(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function repliedLetter(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MailBox::class, 'id', 'reply_to');
    }

    public function recipientObject(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'recipient');
    }

    public function usersSentMails(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'sender_id');
    }
}
