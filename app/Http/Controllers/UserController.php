<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          $data = ModelsUser::orderBy('id','DESC')->paginate(5);
          return view('users.show_users',compact('data'))
         ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.Add_user',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
   
     public function store(Request $request)
     {
         // استخدام طريقة validate مباشرة على كائن الطلب
         $request->validate([
             'name' => 'required',
             'email' => 'required|email|unique:users,email',
             'password' => 'required|same:confirm-password',
             'roles_name' => 'required|array' // تأكد من أن roles_name هو مصفوفة
         ]);
         
         // الحصول على جميع بيانات الإدخال
         $input = $request->all();
         
         // تشفير كلمة المرور قبل الحفظ
         $input['password'] = Hash::make($input['password']);
         
         // تحويل roles_name إلى سلسلة نصية (JSON أو مفصولة بفواصل)
         $input['roles_name'] = json_encode($input['roles_name']); // أو يمكنك استخدام implode إذا كنت تفضل ذلك
     
         // إنشاء المستخدم
         $user = ModelsUser ::create($input);
         
         // تعيين الدور للمستخدم
         $user->assignRole($request->input('roles_name'));
         
         return redirect()->route('users.index')
             ->with('success', 'تم اضافة المستخدم بنجاح');
     }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = ModelsUser::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = ModelsUser::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     */


public function update(Request $request, string $id)
{
    // Use the validate method directly on the request object
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|same:confirm-password',
        'roles' => 'required'
    ]);
    
    // Get all input data
    $input = $request->all();
    
    // Hash the password if provided
    if (!empty($input['password'])) {
        $input['password'] = Hash::make($input['password']);
    } else {
        // Remove password from input if not provided
        unset($input['password']);
    }
    
    // Find the user by ID
    $user = ModelsUser::find($id);
    
    // Check if the user exists
    if (!$user) {
        return redirect()->route('users.index')->with('error', 'User  not found');
    }
    
    // Update the user details
    $user->update($input);
    
    // Remove existing roles and assign new ones
    DB::table('model_has_roles')->where('model_id', $id)->delete();
    $user->assignRole($request->input('roles'));
    
    return redirect()->route('users.index')
        ->with('success', 'تم تحديث معلومات المستخدم بنجاح');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        ModelsUser::find($request->user_id)->delete();
return redirect()->route('users.index')->with('success','تم حذف المستخدم بنجاح');
    }
}