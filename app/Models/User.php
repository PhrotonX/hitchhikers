<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
        'gender',
        'birthdate',
        'user_type',
        'email',
        'password',
        'phone',
        'account_status',
    ];

    protected $guarded = [
        'id',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullName(): string{
        $name = $this->first_name;
        $name .= " " . $this->getMiddleInitial() ?? " ";
        $name .= " $this->last_name";
        $name .= " $this->ext_name" ?? "";

        return $name;
    }

    public function getMiddleInitial(): ?string{
        if(isset($this->middle_name)){
            return $this->middle_name[0] . '.';
        }else{
            return null;
        }
    }

    public function isPrivileged(string $atLeast = null) : bool{
        // There's probably a better way to deal with this.
        switch($this->user_type){
            case "member":
                if($atLeast == "member"){
                    return true;
                }
                return false;
            case "moderator":
                if($atLeast == "moderator" || $atLeast == "member"){
                    return true;
                }
                return false;
            case "staff":
                if($atLeast == "staff" || $atLeast == "moderator" || $atLeast == "member"){
                    return true;
                }
                return false;
            case "owner":
                if($atLeast == "owner" || $atLeast == "staff" || $atLeast == "moderator" || $atLeast == "member"){
                    return true;
                }
                return false;
            default:
                return false;
                break;
        }
    }

    
}
