<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id' ,
        'name' ,
        'kana',
        'email' ,
        'password' ,
        'user_info' ,
        'role_id' ,
        'shop_id' ,
        'postcode',
        'address' ,
        'tel' ,
        'mailService' ,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function scopeSearchUsers($query, $input = null)
        {
            if(!empty($input)){
                if(User::where('name', 'like', '%'.$input . '%' )
                ->orWhere('user_info', 'like', '%'.$input . '%')->exists())
                {
                return $query->where('name', 'like', '%'.$input . '%' )
                ->orWhere('user_info', 'like', '%'.$input . '%');
                }
            }
        }

    protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ];
        }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

}
