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
        $this->middleware('permission:roles menu', ['only' => ['index']]);
        $this->middleware('permission:roles create', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        $this->middleware('permission:roles edit', ['only' => ['edit']]);
        $this->middleware('permission:roles delete', ['only' => ['delete']]);
        $this->middleware('permission:roles assign-permissions', ['only' => ['assign-permissions']]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('roles')->orderBy('name', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                        if (Auth::user()->can('roles assign-permissions')) {
                            $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-toggle="tooltip" data-original-title="Add Permission" class="btn btn-sm btn-outline-success assign-permissions"><i class="uil-key-skeleton-alt fw-semibold align-middle me-1"></i>Assign Permissions</a>';
                        }
                        if (Auth::user()->can('roles edit')) {
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-outline-warning edit"><i class=" uil-edit-alt fw-semibold align-middle me-1"></i>Edit</a>';
                        }
                        if (Auth::user()->can('roles delete')) {
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-outline-danger delete"><i class="uil-trash-alt fw-semibold align-middle me-1"></i>Delete</a>';
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


    public function destroy($id)
    {
        $roles = Role::findOrFail($id); 
        $roles->delete();
        
        return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
    }

    

    public function assignPermissionToRole($id)
    {
        $role = Role::findOrFail($id);
        
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0]; 
        });

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->toArray();

        // Return success response
        return response()->json([
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }


    public function givePermissionToRole(Request $request)
    {
        $roleId = $request->input('rolePermissionID');
        $permission = $request->input('permissions');

        try { 
            $role = Role::findOrFail($roleId);
            $role->syncPermissions($permission);

            // Return success response
            return response()->json(['message' => 'Permissions updated successfully']);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
