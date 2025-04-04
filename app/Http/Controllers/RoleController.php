<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

use DB;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $roles = Role::orderBy('id','DESC')->paginate(5);
    return view('roles.index',compact('roles'))
    ->with('i', ($request->input('page', 1) - 1) * 5);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permission' => 'required',
    ]);

    $role = Role::create(['name' => $request->input('name')]);
    $role->syncPermissions($request->input('permission'));

    return redirect()->route('roles.index')
        ->with('success', 'تم إنشاء الدور بنجاح');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();
        return view('roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // Use the validate method directly on the request object
        $request->validate([
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        // Find the role by ID
        $role = Role::find($id);
    
        // Check if the role exists
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found');
        }
    
        // Update the role's name and permissions
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            DB::table("roles")->where('id',$id)->delete();
            return redirect()->route('roles.index')
            ->with('success','Role deleted successfully');
    }
}