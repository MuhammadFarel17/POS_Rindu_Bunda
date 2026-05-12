<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'username',
        'photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * 🔒 Auto hash password
     */
   public function setPasswordAttribute(string $value)
    {
        if (!empty($value)) {
            // cegah double hash    
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // cegah double hash
            if (strlen($value) < 60) {
                $this->attributes['password'] = bcrypt($value);
            } else {
                $this->attributes['password'] = $value;
            }
        }
    }

    /**
     * 🔥 WAJIB untuk Filament login
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active === true;
    }
}
}
}
}
}
