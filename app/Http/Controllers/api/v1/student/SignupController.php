<?php

namespace App\Http\Controllers\api\v1\student;

use App\Models\User;
use App\trait\image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\student\SignupRequest;

class SignupController extends Controller
{
        public function __construct(private User $user) {}

   protected $studentRequest = [
   'name',
   'email',
   'password',
   'phone',
   'city_id',
   'country_id',
   'category_id',
   'role',
   'gender',
   'sudent_jobs_id',
   'parent_relation_id',
   'education_id',
   'affilate_id',
     'parent_id',
   'language'
   ];
    protected $parentRequest = [
    'parent_name',
    'parent_email',
    'parent_password',
    'parent_phone',
    'parent_role',
  
    'parent_relation_id',
    ];
    // This Controller About Create New Student
    use image;
    public function store(SignupRequest $request){
        $newStudent = $request->only($this->studentRequest); // Get Requests
        $image_path = $this->upload($request,'image', 'student/user'); // Upload New Image For Student
        $newStudent['image'] = $image_path;
        $newStudent['role'] = 'student';
         if(isset($request->affilate_code)){ // If Student Append Affiliate Code
            $affiliate = $this->user->where('affilate_code', $request->affilate_code)->first();
            $newStudent['affilate_id'] = $affiliate->id;
         }
      
           
        if($this->parentRequest){
            $newParent = $request->only($this->parentRequest);
            //   $newParent['parent_id'] = $user->id;
              $newParent['role'] = 'parent';
              $parent = $this->user->create([
              'name' => $newParent['parent_name'],
              'email' => $newParent['parent_email'],
              'password' => $newParent['parent_password'],
              'phone' => $newParent['parent_phone'],
              'role' => 'parent',
              'parent_id' => $newParent['parent_id'],
              'parent_relation_id' => $newParent['parent_relation_id'],
              ]); // Start Create Parent
        }
            $newStudent['parent_id'] = $parent->id; // Relational Parent With Student
            $user = $this->user->create($newStudent); // Start Create New Student
        $token = $user->createToken('personal access token')->plainTextToken; // Start Create Token
        $user->token = $token; // Start User Take This Token ;
        return response()->json([
            'success'=>'Welcome,Student Created Successfully',
            'user'=>$user,
            '_token'=>$token,
        ],200);
        
    }
}
