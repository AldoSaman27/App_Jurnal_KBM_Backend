<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "nip",
        "hari_tanggal",
        "jam_pembelajaran",
        "kelas",
        "kehadiran",
        "uraian_kegiatan",
        "materi",
        "tujuan_pembelajaran",
        "foto_kegiatan",
        "created_at",
        "updated_at",
    ];
}
