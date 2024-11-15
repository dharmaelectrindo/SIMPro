<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Organization;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:organizations menu', ['only' => ['index']]);
        $this->middleware('permission:organizations create', ['only' => ['create','store']]);
        $this->middleware('permission:organizations edit', ['only' => ['edit']]);
        $this->middleware('permission:organizations delete', ['only' => ['delete']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('organizations')->orderBy('organizations_code', 'ASC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                            if (Auth::user()->can('organizations edit')) {
                                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
                            }
                            if (Auth::user()->can('organizations delete')) {
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
                

            return view('modules.master_data.organizations', [
                'title' => 'SIMPro - Organizations',
            ]);
    }
    public function store(Request $request)
    {
        $request->merge([
            'description' => remove_extra_spaces($request->input('description')),
        ]);

        // validasi
        $validator = Validator::make($request->all(), [
            'organizationsCode' => 'required|unique:organizations',
            'description' => 'required|max:100',
            'organizationsLevel' => 'required',
        ],
        [
            'organizationsCode.required' => 'Please Entry Code',
            'organizationsCode.unique' => 'Code Already.',
            'description.required' => 'Please Entry Description',
        ],
            
    );

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        Department::updateOrCreate(['id' => $request->organizationsID],
                [
                    'organizations_code' => $request->organizationsCode,
                    'organizations_level' => $request->organizationsLevel,
                    'odescriptions' => $request->descriptions,
                    'usrmdf' => Auth::user()->id,
                ]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }



    public function edit($id)
    {
        $data = Organization::find($id);
        return response()->json($data);
    }

    

    public function delete(Request $request)
    {
        Department::find($request->id)->delete();
        return redirect()->back();
    }


}
