<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{

    public function __construct() 
    {
        $this->middleware('permission:employees menu', ['only' => ['index']]);
        $this->middleware('permission:employees create', ['only' => ['create','store']]);
        $this->middleware('permission:employees edit', ['only' => ['edit']]);
        $this->middleware('permission:employees delete', ['only' => ['delete']]);
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table('employees')->orderBy('employee_name', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                        if (Auth::user()->can('employees edit')) {
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
                        }
                        if (Auth::user()->can('employees delete')) {
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
                

            return view('modules.master_data.employee', [
                'title' => 'SIMPro - Employees',
            ]);
    } 


}
