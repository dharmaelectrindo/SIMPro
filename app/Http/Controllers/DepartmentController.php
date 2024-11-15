<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Department;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:departments menu', ['only' => ['index']]);
        $this->middleware('permission:departments create', ['only' => ['create','store']]);
        $this->middleware('permission:departments edit', ['only' => ['edit']]);
        $this->middleware('permission:departments delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('departments')->orderBy('department_name', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
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
                

            return view('modules.master_data.department', [
                'title' => 'SIMPro - Permission',
            ]);
    }


    public function store(Request $request)
    {
        $request->merge([
            'departmentName' => remove_extra_spaces($request->input('departmentName')),
        ]);

        // validasi
        $validator = Validator::make($request->all(), [
            'departmentName' => 'required|unique:departments,department_name,'. $request->departmentID],
        [
            'departmentName.required' => 'Department Name harus diisi.',
            'departmentName.unique' => 'Department Name sudah ada.',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        Department::updateOrCreate(['id' => $request->departmentID],
                [
                    'department_name' => $request->departmentName,
                    'user_id' => Auth::user()->id,
                ]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }



    public function edit($id)
    {
        $data = Department::find($id);
        return response()->json($data);
    }

    

    public function delete(Request $request)
    {
        Department::find($request->id)->delete();
        return redirect()->back();
    }



}
