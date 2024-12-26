<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:templates menu', ['only' => ['index']]);
        $this->middleware('permission:templates create', ['only' => ['create','store']]);
        $this->middleware('permission:templates edit', ['only' => ['edit']]);
        $this->middleware('permission:templates delete', ['only' => ['delete']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =  Template::with('user')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';                 
                            if (Auth::user()->can('templates edit')) {
                                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-warning edit"><i class="ri-edit-line fw-semibold align-middle me-1"></i> Edit </a>';
                            }
                            if (Auth::user()->can('templates delete')) {
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
                
            return view('modules.master_data.templates', [
                'title' => 'SIMPro - Templates'
            ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'description' => remove_extra_spaces($request->input('description')),
        ]);

        // validasi
        $validator = Validator::make($request->all(), [
            'description' => 'required|unique:templates,description,'. $request->templateID,
           
        ],
        [
           
            'description.required' => 'Please Entry Description',
        ],
            
    );

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        Template::updateOrCreate(['id' => $request->templateID],
                [
                    'description' => $request->description,
                ]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }



    public function edit($id)
    {
        $data = Template::find($id);
        return response()->json($data);
    }

    

    public function destroy(Request $request)
    {
        $Template = Template::find($request->id);
        if ($Template != null) {
            $Template->delete();
        }
        return response()->json(['success' => true, 'message' => 'Template deleted successfully.','id' => $request->id]);
    }

}
