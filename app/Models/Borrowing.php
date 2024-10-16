<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primariKey = 'id';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
    ];

    public function buku()
    {
        return $this->belongsTo(Book::class, 'buku_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
