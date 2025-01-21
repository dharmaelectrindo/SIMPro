<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Task;
use App\Models\Template;
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
    

    public function detail(Request $request)
    {
            $template   = Template::find($request->id);
            $task       = Task::with(['children','templates'])->whereNull('parentID')->where("templateID",$request->id)->get();
            
            return view('modules.master_data.tasks', [
                'title'         => 'SIMPro - Templates',
                'templateName'  => $template->description ?? '',
                'templateID'    => $template->id ?? '',
            ],compact('task'));
    }

    public function edit($id)
    {
            $task   = Task::find($id);
            
            return response()->json($task);
    }

    public function store(Request $request)
    {
        $request->merge([
            'descriptionTask' => remove_extra_spaces($request->input('descriptionTask')),
        ]);

        // validasi
        $validator = Validator::make($request->all(),
        [
           
            'descriptionTask.required' => 'Please Entry Description',
        ],
        [
           
            'sortData.required' => 'Please Entry Squence',
        ],
            
    );

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        
        Task::updateOrCreate(['id' => $request->taskID],
                [
                    'description'     => $request->descriptionTask,
                    'sortData'        => $request->sortData,
                    'parentID'        => $request->parentID ?? NULL,
                    'templateID'      => $request->templateForeign,
                ]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function destroy(Request $request)
    {
        $task = Task::find($request->id);
        if ($task != null) {
            $task->delete();
        }
        return response()->json(['success' => true, 'message' => 'Template deleted successfully.','id' => $request->id]);
    }

}
