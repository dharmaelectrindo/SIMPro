<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct() 
    {
        $this->middleware('permission:projects menu', ['only' => ['users']]);
        $this->middleware('permission:projects create', ['only' => ['create','store']]);
        $this->middleware('permission:projects edit', ['only' => ['edit']]);
        $this->middleware('permission:projects delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('projects')->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';                 
                    if (Auth::user()->can('projects edit')) {
                        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-outline-warning edit"><i class=" uil-edit-alt fw-semibold align-middle me-1"></i>Edit</a>';
                    }
                    if (Auth::user()->can('projects delete')) {
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-outline-danger delete"><i class="uil-trash-alt fw-semibold align-middle me-1"></i>Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }

        return view('modules.project.project', [
            'title' => 'SIMPro - Projects',
        ]);
    }


}
