<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;


class EmployeeController extends Controller
{
    public function __construct() 
    {
        $this->middleware('permission:employees menu', ['only' => ['index']]);
        $this->middleware('permission:employees create', ['only' => ['create','store']]);
        $this->middleware('permission:employees edit', ['only' => ['edit']]);
        $this->middleware('permission:employees delete', ['only' => ['delete']]);
        $this->middleware('permission:employees import', ['only' => ['import']]);
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table('employees')
                ->whereNull('deleted_at')    
                ->orderBy('employee_name', 'ASC')
                ->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                        if (Auth::user()->can('employees edit')) {
                            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-outline-warning edit"><i class=" uil-edit-alt fw-semibold align-middle me-1"></i>Edit</a>';
                        }
                        if (Auth::user()->can('employees delete')) {
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
                

            $check = DB::table('employees')->pluck('id')->first();

            return view('modules.master_data.employee', [
                'title' => 'SIMPro - Employees',
                'check' => $check
            ]);
    } 


    public function store(Request $request)
    {
        // Remove extra spaces from input
        $request->merge([
            'npk' => remove_extra_spaces($request->input('npk')),
            'employeeName' => remove_extra_spaces($request->input('employeeName')),
            'employeePosition' => remove_extra_spaces($request->input('employeePosition')),
            'email' => remove_extra_spaces($request->input('email')),
            'mobileNumber' => remove_extra_spaces($request->input('mobileNumber')),
        ]);

        // Validation
        $validator = Validator::make($request->all(), [
            'npk' => 'required|unique:employees,npk,' . $request->employeeID,
            'employeeName' => 'required',
            'employeePosition' => 'required',
            'email' => 'required|email',
            'mobileNumber' => 'required|regex:/^[0-9]+$/',
        ], [
            'npk.required' => 'NPK harus diisi.',
            'npk.unique' => 'NPK sudah ada.',
            'employeeName.required' => 'Employee Name harus diisi.',
            'employeePosition.required' => 'Employee Position harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'mobileNumber.required' => 'Mobile Number harus diisi.',
            'mobileNumber.regex' => 'Mobile Number harus berupa angka.',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        // Update or create
        Employee::updateOrCreate(
            ['id' => $request->employeeID],
            [
                'npk' => $request->npk,
                'employee_name' => $request->employeeName,
                'employee_position' => $request->employeePosition,
                'email' => $request->email,
                'mobile_number' => $request->mobileNumber,
                'user_mdf' => Auth::user()->id,
            ]
        );

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }




    public function edit($id)
    {
        $data = Employee::find($id);

        //  return response
        return response()->json($data);
    }

    

    public function destroy($id)
    {
        $data = Employee::findOrFail($id); 
        $data->delete();
        
        //  return response
        return response()->json([
            'success' => true, 
            'message' => 'Data berhasil dihapus.']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:10000'
        ]);

        Excel::import(new EmployeeImport, $request->file('file'));

        //  return response
        return response()->json(['message' => 'Data berhasil di import'], 200);
    }


}
