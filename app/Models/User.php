<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\subject;
use App\Models\LoginHistory;
use App\Models\StudentJob;
use App\Models\bundle;
use App\Models\category;
use App\Models\country;
use App\Models\city;
use App\Models\homework;
use App\Models\ParentRelation;
use App\Models\Education;
use App\Models\Payout;
use App\Models\AffilateAccount;
use App\Models\Bonus;
use App\Models\AdminPosition;
use App\Models\PersonalAccessToken;
use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable,AuthAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'name',
        'phone',
        'role',
        'gender',
        'sudent_jobs_id',
        'affiliate_id',
        'email',
        'email_verified_at',
        'password',
        'country_id',
        'city_id',
        'parent_id',
        'affilate_id',
        'remember_token',
        'parent_relation_id',
        'category_id',
        'education_id',
        'image',
        'affilate_code',
        'admin_position_id',
        'status',
    ];

    protected $appends = ['image_link'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $image_url;

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

    public function getImageUrl (){
        if(isset($this->image)){
            return $this->image = url('storage/'.$this->image);
        }
        return 'storage/'.$this->name;
    }

    public function admin_position(){
        return $this->belongsTo(AdminPosition::class, 'admin_position_id');
    }

    public function parent_relation(){
        return $this->belongsTo(ParentRelation::class, 'parent_relation_id');
    }

    public function recorded_live(){
        return $this->belongsToMany(LiveRecorded::class, 'user_live_recorded', 'user_id', 'recorded_id');
    }

    public function signups(){
        return $this->hasMany(User::class, 'affilate_id');
    }

    public function income(){
        return $this->hasOne(AffilateAccount::class, 'affilate_id');
    }

    public function last_login(){
        return $this->hasOne(LoginHistory::class, 'user_id')
        ->orderByDesc('id');
    }

    public function logins(){
        return $this->hasOne(PersonalAccessToken::class, 'tokenable_id')
        ->orderByDesc('id');
    }

    public function bonuses(){
        return $this->belongsToMany(Bonus::class, 'affilate_bonuses', 'affilate_id', 'bonus_id');
    }

    public function country(){
        return $this->belongsTo(country::class);
    }

    public function education(){
        return $this->belongsTo(Education::class);
    }

    public function city(){
        return $this->belongsTo(city::class);
    }

    public function getImageLinkAttribute(){
            if($this->gender == 'male' && is_null($this->image)){
            return url('storage/' .'default.png');
            }elseif($this->gender == 'female' && is_null($this->image)){
            return url('storage/' .'female.png');
        }else{
            return url('storage/' . $this->attributes['image']);
        }
    }

    public function getcreatedAtAttribute($datetime){
        return date('d-m-Y', strtotime($datetime));
    }

    public function subjects(){
        return $this->belongsToMany(subject::class, 'students_subjects');
    }

    public function bundles(){
        return $this->belongsToMany(bundle::class, 'students_bundles');
    }
   

    public function category(){
        return $this->belongsTo(category::class, 'category_id');
    }

    public function parents(){
        return $this->belongsTo(User::class,'parent_id');
    }

    public function childreen(){
        return $this->hasMany(User::class,'parent_id')
        ->with('category');
    }

    public function teacher_subjects(){
        return $this->belongsToMany(subject::class, 'teacher_subject');
    }
    public function studentJobs(){
        return $this->belongsTo(StudentJob::class,'sudent_jobs_id');
    }
    public function user_homework(){
        return $this->belongsToMany(homework::class,'users_homework')
        ->withPivot(['score', 'lesson_id']);
    }
    public function liveSessions(){
        return $this->hasMany(Live::class, 'teacher_id');
    }

     public function payout_history(){
        return $this->hasMany(Payout::class,'affilate_id')->where('status','!=',Null)->with('method');
     }

     public function affiliate_history(){
        return $this->hasMany(AffilateHistory::class,'affilate_id')->with('student');
     }

     public function student_job(){
         return $this->belongsTo(StudentJob::class, 'sudent_jobs_id');
     }

      public function live():BelongsToMany{
        return $this->belongsToMany(Live::class, 'user_live');
      }

      public function services():BelongsToMany{
        return $this->belongsToMany(Live::class, 'user_Live');
      }

      public function revision():BelongsToMany{
        return $this->belongsToMany(Revision::class, 'user_revision');
      }

}
