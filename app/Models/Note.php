<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{

    // Ao deletar as notas são marcadas como deletadas no Banco de Dados
    // mesmo usando o Hard Delete
    use SoftDeletes;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
