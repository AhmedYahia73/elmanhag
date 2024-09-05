<?php

namespace App\Http\Controllers\api\v1\admin\role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\admin\role\RoleRequest;

use App\Models\AdminPosition;
use App\Models\AdminRole;

class RoleController extends Controller
{
    public function __construct(private AdminPosition $admin_position, private AdminRole $admin_role){}

    public function show(){
        $admin_position = $this->admin_position
        ->with('roles')
        ->get();
        $roles = ['students', 'teachers', 'admins', 'categories',
        'subjects', 'bundles', 'questions', 'hw', 'revisions', 'exams', 'live',
        'discounts', 'promocode', 'pop up', 'reviews', 'pendding payments', 'payments',
        'affilate', 'support', 'reports', 'settings', 'notice board'];

        return response()->json([
            'admin_position' => $admin_position,
            'roles' => $roles,
        ]);
    }

    public function add(RoleRequest $request){
        // Keys
        // name
        // roles[]
        $admin_position = $this->admin_position
        ->create(['name' => $request->name]);

        foreach ($request->roles as $item) {
            $this->admin_role
            ->create([
                'role' => $item,
                'admin_position_id' => $admin_position->id
            ]);
        }

        return response()->json([
            'success' => 'You add data success'
        ]);
    }
}