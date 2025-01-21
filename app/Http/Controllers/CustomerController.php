<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use DataTables;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers menu', ['only' => ['index']]);
        $this->middleware('permission:customers create', ['only' => ['create']]);
        $this->middleware('permission:customers edit', ['only' => ['edit']]);
        $this->middleware('permission:customers delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('customers')->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';                 
                    if (Auth::user()->can('customers edit')) {
                        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-outline-warning edit"><i class=" uil-edit-alt fw-semibold align-middle me-1"></i>Edit</a>';
                    }
                    if (Auth::user()->can('customers delete')) {
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-outline-danger delete"><i class="uil-trash-alt fw-semibold align-middle me-1"></i>Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }

        return view('modules.master_data.customer', [
            'title' => 'SIMPro - Customers',
        ]);
    }


    public function store(Request $request)
    {
        // Menghilangkan spasi tambahan
        $request->merge([
            'customerCode' => remove_extra_spaces($request->input('customerCode')),
            'customerName' => remove_extra_spaces($request->input('customerName')),
        ]);

        // Validasi
        $validator = Validator::make($request->all(), [
            'customerCode' => ['required', 'unique:customers,customer_code,' . $request->customerID],
            'customerName' => ['required', 'unique:customers,customer_name,' . $request->customerID],
        ], [
            'customerCode.required' => 'Customer Code harus diisi.',
            'customerCode.unique' => 'Customer Code sudah ada.',
            'customerName.required' => 'Customer Name harus diisi.',
            'customerName.unique' => 'Customer Name sudah ada.',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        // Simpan atau perbarui data
        $customer = Customer::updateOrCreate(['id' => $request->customerID], [
            'customer_code' => $request->customerCode,
            'customer_name' => $request->customerName,
        ]);

        //  return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }



    public function edit($id)
    {
        $data = Customer::find($id);
        return response()->json($data);
    }

    

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id); 
        $customer->delete();
        
        return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
    }



}
