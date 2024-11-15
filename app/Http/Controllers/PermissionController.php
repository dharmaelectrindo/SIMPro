<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission menu', ['only' => ['index']]);
        $this->middleware('permission:permission create', ['only' => ['create']]);
        $this->middleware('permission:permission edit', ['only' => ['edit']]);
        $this->middleware('permission:permission delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('permissions')->orderBy('name', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                        if (Auth::user()->can('permission edit')) {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
                        }
                        if (Auth::user()->can('permission delete')) {
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

                return view('modules.user_role_permission.permission.permission', [
                    'title' => 'SIMPro - Permissions',
                ]);
    }


    public function store(Request $request)
    {
        $request->merge([
            'permissionName' => remove_extra_spaces($request->input('permissionName')),
        ]);

        // validasi
        $validator = Validator::make($request->all(), [
            'permissionName' => 'required|unique:permissions,name,'. $request->permissionID],
        [
            'permissionName.required' => 'Permission Name harus diisi.',
            'permissionName.unique' => 'Permission Name sudah ada.',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        Permission::updateOrCreate(['id' => $request->permissionID],
                ['name' => $request->permissionName]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }



    public function edit($id)
    {
        $data = Permission::find($id);
        return response()->json($data);
    }

    

    public function delete(Request $request)
    {
        Permission::find($request->id)->delete();
        return redirect()->back();
    }

}
