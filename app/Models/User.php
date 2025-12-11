<?php

namespace App\Models;

use App\Models\Picture;
use App\Models\ProfilePicture;
use App\Models\UserProfilePicture;
use App\Models\Driver;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    private static $nullProfilePicture = null;

    // Exclude sensitive fields from audit logs
    protected $auditExclude = ['password', 'remember_token'];

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
        'profile_picture_id',
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

    /**
     * Returns the full name of the user.
     * 
     * @param useMiddleInitial True if the string should consist of a middle initial, false for middle name.
     * @return string The full name of the user.
     */
    public function getFullName($useMiddleInitial = false): string{
        $name = $this->first_name;
        if($useMiddleInitial == true)
            $name .= " " . $this->getMiddleInitial() ?? " ";
        else
            $name .= " " . $this->middle_name ?? " ";
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

    /**
     * Obtains the Driver account object.
     * 
     * @return Driver? A nullable Driver object.
     */
    public function getDriverAccount(){
        return Driver::where('user_id', $this->id)->first()?->get()[0] ?? null;
    }

        /**
     * Retrieves the Picture object set on a User.
     * 
     * @return ?ProfilePicture The picture set on a user. May return null.
     */
    public function getProfilePicture() : ?ProfilePicture{
        //$userProfilePicture = UserProfilePicture::where('pfp_id', $this->profile_picture_id)->first();
        //return $userProfilePicture->profilePicture;
        $profilePicture = ProfilePicture::where('pfp_id', $this->profile_picture_id)->first();

        if($profilePicture == null){
            if(self::$nullProfilePicture == null){
                self::$nullProfilePicture = new ProfilePicture();
                self::$nullProfilePicture->pfp_xs = '../img/question_mark_xs.png';
                self::$nullProfilePicture->pfp_small = '../img/question_mark_s.png';
                self::$nullProfilePicture->pfp_medium = '../img/question_mark_m.png';
                self::$nullProfilePicture->pfp_large = '../img/question_mark_l.png';
            }
            return self::$nullProfilePicture;
        }

        return $profilePicture;
    }

    /**
     * Define the relationship to profile pictures through the pivot table
     */
    public function profilePictures(){
        return $this->belongsToMany(ProfilePicture::class, 'user_profile_picture', 'user_id', 'pfp_id');
    }

    public function getVehicleDriver(){
        return VehicleDriver::where('driver_id', $this->getDriverAccount()?->id ?? 0)->get();
    }

    public function getRides(){
        return Ride::where('driver_id', $this->id)->get();
    }

    /**
     * Checks if the user consists of a role or a privilege such as member, moderator, staff, and owner (in ascending order).
     * 
     * @param atLeast Checks whether the user account consist of the specified privelege until the highest privelege.
     * @return bool Returns true if the specified or higher privilege exists. Else, false otherwise.
     */
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

    /**
     * Checks whether the user account is a driver or not.
     * 
     * @return bool Returns true if the user's driver account object is not null. Else, false.
     */
    public function isDriver(): bool{
        return $this->getDriverAccount() != null;
    }

        /**
     * Get the user's primary profile picture (for compatibility with views)
     */
    public function getUserProfilePictureAttribute(){
        $profilePicture = $this->getProfilePicture();
        if($profilePicture && !$profilePicture->isNull(ProfilePicture::SIZE_XS_SUFFIX)) {
            return (object) [
                'picture' => (object) [
                    'path' => str_replace('../', '', $profilePicture->pfp_medium ?? $profilePicture->pfp_large ?? $profilePicture->pfp_small ?? $profilePicture->pfp_xs)
                ]
            ];
        }
        return null;
    }

    public function getProfilePictures(){
        return $this->profilePictures()->get();
    }
}
