<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Task;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tasks menu', ['only' => ['index']]);
        $this->middleware('permission:tasks create', ['only' => ['create','store']]);
        $this->middleware('permission:tasks edit', ['only' => ['edit']]);
        $this->middleware('permission:tasks delete', ['only' => ['delete']]);
    }
    public function index(Request $request)
    {
        //if ($request->ajax()) {
            $task = Task::with('children')->where('parentID', null)->get();
            // return Datatables::of($data)
            //         ->addIndexColumn()
            //         ->addColumn('action', function($row){
            //             $btn = '';                 
            //                 if (Auth::user()->can('tasks edit')) {
            //                     $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
            //                 }
            //                 if (Auth::user()->can('tasks delete')) {
            //                     $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-danger delete"><i class="ri-close-line fw-semibold align-middle me-1"></i> Delete </a>';
            //                 }
            //             return $btn;
            //         })
            //         ->addColumn('created_at', function($row)
            //             {
            //                 $date = date("d/m/Y", strtotime($row->created_at));
            //                 return $date;
            //             })
            //         ->rawColumns(['action','created_at'])
            //         ->make(true);
          //       }
                
            return view('modules.master_data.tasks', [
                'title' => 'SIMPro - Templates'
            ],compact('task'));
    }

}
