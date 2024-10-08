<?php

namespace App\Http\Controllers\api\v1\admin\student;

use App\trait\image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\api\admin\student\StudentRequest;
use App\Http\Requests\api\admin\student\UpdateStudentRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupNotificationMail;

use App\Models\User;
use App\Models\category;
use App\Models\Education;
use App\Models\StudentJob;

class CreateStudentController extends Controller
{
    public function __construct(private User $user, private category $category, 
    private Education $education, private StudentJob $student_job ){

    }
    protected $studentRequest = [
        'name',
        'phone',
        'role',
        'email',
        'gender',
        'sudent_jobs_id',
        'category_id',
        'education_id',
        'password',
        'country_id',
        'city_id',
        'status',
    ];
    // This Controller About Student
    use image;
    public function store(StudentRequest $request){
        // https://bdev.elmanhag.shop/admin/student/add
        // name, phone, email, parent_name, parent_phone, parent_email, parent_password,
        // category_id, education_id, password, country_id, city_id=, status=1, relation_id
        // gender, sudent_jobs_id
        $newStudent =  $request->only($this->studentRequest); // Take only Request From Protected studentRequest names 
        $newStudent['status'] = 1;
        $parent =  $this->user->where('email', $request->parent_email)
        ->first();
        if (!empty($parent) && $parent->role == 'parent') {
            $newStudent['parent_id'] = $parent->id;
        } 
        elseif (!empty($parent)) {
            return response()->json([
                'faild' => 'Parent Email exists his role not as parent'
            ]);
        }
        else {
            $parent = $this->user->create([
                'name' => $request->parent_name,
                'email' => $request->parent_email,
                'password' => $request->parent_password,
                'phone' => $request->parent_phone,
                'role' => 'parent',
                'parent_relation_id' => $request->relation_id,
            ]); // Start Create Parent
            $newStudent['parent_id'] = $parent->id;
        }

        if ($newStudent['gender'] == 'male') {
            $newStudent['image'] = 'default.png';
        } 
        else {
            $newStudent['image'] = 'female.png';
        }
        
        $newStudent['role'] = 'student'; // Type Of User
        $user = $this->user->create($newStudent); // Start Create New Studetn
        $user->category = $this->category
        ->where('id', $user->category_id )
        ->first()->name;
        $user->education = $this->education
        ->where('id', $user->education_id  )
        ->first()->name;
        $user->job = $this->student_job
        ->where('id', $user->sudent_jobs_id)
        ->first()->job;
        $user->parent = $request->parent_name;
        $user->parent_phone = $request->parent_phone;
        $subject = "Signup Notification Mail";
        $view = "Signup";
         Mail::to('elmanhagedu@gmail.com')->send(new SignupNotificationMail($user,$subject,$view));
        return response()->json(['success'=>'Student Created Successfully'],200); 
    }
    
    public function modify(UpdateStudentRequest $request, $id){
        // https://bdev.elmanhag.shop/admin/student/update/{id}
        // name, phone, email, parent_name, parent_phone, parent_email, relation_id,
        // parent_password, category_id, education_id, password, country_id, city_id, status
        // gender, sudent_jobs_id
        // Take only Request From Protected studentRequest names 
        $student =  $request->only($this->studentRequest); 
        // Get User Data
        $user = $this->user->where('id', $id)
        ->where('role', 'student')
        ->first();
        $student_email = $this->user->where('id', '!=', $id)
        ->where('email', $request->email)
        ->first();
        $parent_email = $this->user->where('id', '!=', $user->parent_id)
        ->where('email', $request->parent_email)
        ->first();
        // Check if student email is exist
        if (!empty($student_email)) {
            return response()->json([
                'faild' => 'Student email is exist'
            ]);
        }
        // Check if parent email is exist
        if (!empty($parent_email)) {
            return response()->json([
                'faild' => 'Parent email is exist'
            ]);
        }

        // Update Image
        if ( !empty($user) ) {
            if (is_file($request->image)) {
                $image =  $this->upload($request,'image','student/user'); // Upload teacher image
              
                // If new image is found delete old image
                if ( !empty($image) && $image != null ) { 
                    $data['image'] = $image;
                    $this->deleteImage($user->image['path']); // Delete old teacher image
                }
            }

            $user->update($student); // Start Create New Studetn
            $parent = $this->user->where('id', $user->parent_id)
            ->first();
            $parent->name = $request->parent_name;
            $parent->phone = $request->parent_phone;
            $parent->email = $request->parent_email;
            if ($request->filled('parent_password')) {
                $parent->password = $request->parent_password;
            }
            $parent->parent_relation_id = $request->relation_id;
            $parent->save();
            return response()->json(['success'=>'Student Updated Successfully'],200); 
        }
        else{
            return response()->json(['faild'=>'Student Is not Found'],400); 
        }
    }

    public function delete( $id ){
        // Get User Data
        $user = $this->user->where('id', $id)
        ->where('role', 'student')
        ->first();

        // Remove User
        if ( !empty($user) ) {
            if ($user->image != 'default.png' && $user->image != 'female.png') {
                $this->deleteImage($user->image);
            }
            $user->delete();
            return response()->json(['success'=>'Student Deleted Successfully'],200); 
        }
        else{
            return response()->json(['faild'=>'Student Is not Found'],400); 
        }
    }

    public function status(Request $request, $id){
        // https://bdev.elmanhag.shop/admin/student/status/{id}
        // Keys
        // status
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }

        $this->user->where('id', $id)
        ->where('role', 'student')
        ->update([
            'status' => $request->status
        ]);

        if ($request->status == 0) {
            return response()->json([
                'success' => 'banned'
            ]);
        } else {
            return response()->json([
                'success' => 'active'
            ]);
        }
        
    }
   
}
