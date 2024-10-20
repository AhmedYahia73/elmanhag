<?php

namespace App\Http\Controllers\api\v1\student\subsription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\bundle;
use App\Models\subject;
use App\Models\Live;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(private bundle $bundles, private subject $subjects, private Live $live){}
    public function view(){
        
        // https://bdev.elmanhag.shop/student/subscription
        $bundles = $this->bundles
        ->where('category_id', auth()->user()->category_id)
        ->whereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })
        ->with('subjects')
        ->get(); // Get bundles that havs the same category of student and student buy it
        $bundles = $bundles->where('status', 1)
        ->where('expired_date', '>=', date('Y-m-d'));
        $bundles_subjects = [];
        foreach ($bundles as $item) {
            $bundles_subjects = array_merge($bundles_subjects, 
            $item->subjects->pluck('id')->toArray());
        } 
        $subjects = $this->subjects
        ->where('category_id', auth()->user()->category_id)
        ->whereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })
        ->orWhereIn('id', $bundles_subjects)
        ->where('category_id', auth()->user()->category_id)
        ->get(); // Get subject that havs the same category of student and student does not buy it
        $subjects = $subjects->where('status', 1)
        ->where('expired_date', '>=', date('Y-m-d'));

        $live = $this->live
        ->with(['subject', 'teacher'])
        ->where('category_id', auth()->user()->category_id)
        ->where('date', '>=', date('Y-m-d'))
        ->get(); // Get live

        return response()->json([
            'subjects' => $subjects,
            'live' => $live,
        ]);
    }

    public function check_live($id){
        // https://bdev.elmanhag.shop/student/subscription/check/{id}
       

        $bundles = $this->bundles
        ->where('category_id', auth()->user()->category_id)
        ->whereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })
        ->with('subjects')
        ->get(); // Get bundles that havs the same category of student and student buy it
        $bundles_subjects = [];
        foreach ($bundles as $item) {
            $bundles_subjects = array_merge($bundles_subjects, 
            $item->subjects->pluck('id')->toArray());
        } 
        $subjects = $this->subjects
        ->where('category_id', auth()->user()->category_id)
        ->whereHas('users', function ($query) {
            $query->where('users.id', auth()->user()->id);
        })
        ->orWhereIn('id', $bundles_subjects)
        ->where('category_id', auth()->user()->category_id)
        ->get(); // Get subject that havs the same category of student and student does not buy it

        $bundles = $bundles->where('status', 1)
        ->where('expired_date', '>=', date('Y-m-d'));
        $subjects = $subjects->where('status', 1)
        ->where('expired_date', '>=', date('Y-m-d'));

        $live = $this->live
            ->with(['subject', 'teacher'])
            ->where('category_id', auth()->user()->category_id)
            ->whereHas('students', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })
            ->where('date', '>=', date('Y-m-d'))
            ->where('id', $id)
            ->orWhereIn('subject_id', $subjects->pluck('id'))
            ->where('inculded', 1)
            ->where('category_id', auth()->user()->category_id)
            ->where('date', '>=', date('Y-m-d'))

            ->where('id', $id);
          
        $live->first(); // Get live that havs the same category of student

        if (!empty($live)) {
            return response()->json([
                'success' => 'You are allowed to attend'
            ], 200);
        } else {
            $live = $this->live
                ->where('id', $id)
                ->where('paid', 0);
            if (empty($live)) {
                return response()->json([
                    'faild' => 'You must buy live first'
                ], 400);
            } else {
                return response()->json([
                    'success' => 'You are allowed to attend'
                ], 200);
            }
            
        }
        
    }
}
