<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparateur extends Model
{
 
    use HasFactory;

    protected $table ='reparateurs';
    protected $fillable = ['nom','email','numero','quartier','password','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
