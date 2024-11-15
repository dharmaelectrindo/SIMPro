<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role menu', ['only' => ['index']]);
        $this->middleware('permission:role create', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        $this->middleware('permission:role edit', ['only' => ['edit']]);
        $this->middleware('permission:role delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('roles')->orderBy('name', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                        if (Auth::user()->can('role add-permission')) {
                            $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-toggle="tooltip" data-original-title="Add Permission" class="btn btn-sm btn-success add-permission"><i class="ri-key-line fw-semibold align-middle me-1"></i> Add/Update Permission </a>';
                        }
                        if (Auth::user()->can('role edit')) {
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
                        }
                        if (Auth::user()->can('role delete')) {
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-danger delete"><i class="ri-close-line fw-semibold align-middle me-1"></i> Delete </a>';
                        }
                        return $btn;
                    })
                    ->addColumn('created_at', function($row)
                        {
                            $date = date("d/m/Y", strtotime($row->created_at));
                            return $date;
                        })
                    ->rawColumns(['action','created_at'])
                    ->make(true);
                }
                

            return view('modules.user_role_permission.role.role', [
                'title' => 'SIMPro - Roles',
            ]);
    }



    public function store(Request $request)
    {
        $request->merge([
            'roleName' => remove_extra_spaces($request->input('roleName')),
        ]);

        // validasi
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|unique:roles,name,'. $request->roleID],
        [
            'roleName.required' => 'Role Name harus diisi.',
            'roleName.unique' => 'Role Name sudah ada.',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        Role::updateOrCreate(['id' => $request->roleID],
                ['name' => $request->roleName]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }


    public function edit($id)
    {
        $data = Role::find($id);
        return response()->json($data);
    }

    

    public function delete(Request $request)
    {
        Role::find($request->id)->delete();
        return redirect()->back();
    }


    // public function addPermissionToRole($id)
    // {
    //     $permissions = Permission::get();
    //     $role = Role::find($id);
    //     $rolePermissions = DB::table('role_has_permissions')
    //                             ->where('role_has_permissions.role_id', $role->id)
    //                             ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
    //                             ->all();

    //     return response()->json($role,$permission,$rolePermissions);
    // }

    // public function addPermissionToRole($id)
    // {
    //     $role = Role::find($id);
    //     $permissions = Permission::get();
    //     $rolePermissions = DB::table('role_has_permissions')
    //         ->where('role_has_permissions.role_id', $role->id)
    //         ->pluck('role_has_permissions.permission_id')
    //         ->all();

    //     $data = [
    //         'role' => $role,
    //         'permissions' => $permissions,
    //         'rolePermissions' => $rolePermissions
    //     ];

    //     return response()->json($data);
    // }

    public function addPermissionToRole($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id')
            ->all();
        // $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }


    public function givePermissionToRole(Request $request)
    {
        $roleId = $request->input('rolePermissionID'); // Adjust based on your form field name
        $permission = $request->input('permissions'); // Adjust based on your form field name

        // try {
            $role = Role::findOrFail($roleId); // Find the role by ID
            $role->syncPermissions($permission); // Assign the permissions to the role

            return response()->json(['message' => 'Permissions updated successfully']);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }


    // public function givePermissionToRole(Request $request)
    // {
    //     $role = Role::findOrFail($request->id);
    //     $role->syncPermissions($request->permission);
    //     return redirect()->back();
    // }

    public function show($id)
    {

    }

    public function update($id)
    {

    }



}
