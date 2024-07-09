<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <--- Add this line
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table ='users';
    protected $fillable = ['nom','email','numero','quartier','statut','password'];

    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    public function reparateur()
    {
        return $this->hasOne(Reparateur::class, 'user_id');
    }


    public function createToken(string $tokenName): self
    {
        $token = $this->createTokenPayload($tokenName);
        $this->tokens()->create($token);

        return $this;
    }

    protected function createTokenPayload(string $tokenName): array
    {
        $token = Str::random(60);

        return [
            'name' => $tokenName,
            'token' => $token,
            'abilities' => ['*'],
        ];
    }


}