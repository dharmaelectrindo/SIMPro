<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Http\Request;
use DataTables;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

 
class UserController extends Controller
{
    public function __construct() 
    {
        $this->middleware('permission:users menu', ['only' => ['users']]);
        $this->middleware('permission:users create', ['only' => ['create','store','getRoles']]);
        $this->middleware('permission:users edit', ['only' => ['edit']]);
        $this->middleware('permission:users delete', ['only' => ['delete']]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('employee', 'organization', 'roles')
                ->select('users.*')
                ->orderBy('name', 'ASC');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('employee', function ($data) {
                    return $data->employee->employee_name ?? '-';
                })
                ->addColumn('organization', function ($data) {
                    return $data->organization->description ?? '-';
                })
                ->addColumn('roles', function ($data) {
                    $badges = '';
                    foreach ($data->roles as $role) {
                        $badges .= '<span class="badge bg-warning">' . $role->name . '</span> ';
                    }
                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Auth::user()->can('users edit')) {
                        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-outline-warning edit"><i class=" uil-edit-alt fw-semibold align-middle me-1"></i>Edit</a>';
                    }
                    if (Auth::user()->can('users delete')) {
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-outline-danger delete"><i class="uil-trash-alt fw-semibold align-middle me-1"></i>Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['employee', 'organization', 'roles', 'action'])
                ->make(true);
        }

        return view('modules.user_role_permission.user.user', [
            'title' => 'SIMPro - Users',
        ]);
    }


    public function login()
    {
        if (Auth::check()) { 
            return redirect('/home');
        }else{
            return view('auth.login');
        }
    }
    

    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'password' => 'Username atau Password salah',
        ]);
    }


    public function change_password(Request $request)
    {
        // validasi
        $validator = Validator::make($request->all(), [
            'old_password'=>[
                'required', function($attribute, $value, $fails){
                    if( !\Hash::check($value, Auth::user()->password) ){
                        return $fail(__('Password yang anda masukan salah'));
                    }
                },
                'min:8',
                'max:30'
            ],
            'new_password' => 'required|min:8|max:30',
            'new_password_confirmation' => 'required|same:new_password'
        ],[
            'old_password.required'=>'Masukan password anda',
            'old_password.min'=>'Password anda minimal 8 karakter',
            'old_password.max'=>'Password anda maksimal 30 karakter',
            'new_password.required'=>'Masukan password baru anda',
            'new_password.min'=>'Password baru minimal 8 karakter',
            'new_password.max'=>'Password baru maksimal 30 karakter',
            'new_password_confirmation.required'=>'Konfirmasi kembali password baru anda',
            'new_password_confirmation.same'=>'Password baru dan konfirmasi password harus sama',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }else{

            $update = User::find(Auth::user()->id)->update(['password'=>Hash::make($request->new_password)]);

            if( !$update ){
                //  return response
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil di perbaharui',
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Password gagal di perbaharui',
                ]);
            }
        }

    } 


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $request->userID,
            'email' => 'required|max:255|unique:users,email,' . $request->userID,
            'password' => 'nullable|string|min:8|max:20',
            'organization' => 'required',
            'roles' => 'required',
        ], [
            'name.required' => 'Name harus diisi.',
            'name.string' => 'Name harus berupa karakter.',
            'name.max' => 'Name maksimal 255 karakter.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah ada.',
            'email.required' => 'Email harus diisi.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 20 karakter.',
            'organizationID.required' => 'Organization harus diisi.',
            'roles.required' => 'Roles harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        // Generate avatar
        $path = 'images/users/';
        $fontPath = public_path('fonts/Poppins-Regular.ttf');
        $char = strtoupper($request->name[0]);
        $newAvatarName = $request->username . '_avatar';
        $dest = $path . $newAvatarName . '.png';

        // Check if image already exists and delete it
        if (Storage::exists($dest)) {
            Storage::delete($dest);
        }

        // Create new avatar
        $createAvatar = makeAvatar($fontPath, $dest, $char, 'png');
        $picture = $createAvatar ? $newAvatarName . '.png' : '';

        // Data for update or create
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'picture' => $picture,
            'organization_id' => $request->organizationID,
        ];

        if (!empty($request->password)) {
            $data['password'] = $request->password;
        }

        // Update or create
        $user = User::updateOrCreate(
            ['id' => $request->userID],
            $data
        );

        $user->syncRoles($request->roles);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }




    public function edit($id)
    {
        $user = User::with('roles', 'organization')->findOrFail($id);

        return response()->json([
            'user' => $user,
        ]);
    }


    public function delete(Request $request)
    {
        // Find user ID
        $user = User::findOrFail($request->id);

        // Delete the users picture if exists
        if ($user->picture) {
            $picturePath = public_path('images/users/' . $user->username. '_avatar.png');
            if (File::exists($picturePath)) {
                unlink($picturePath); // Delete the picture file
            }
        }

        // Delete user
        $user->delete();

        // Redirect success
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function getEmployees(Request $request)
    {
        $employees = Employee::all(['id', 'npk','employee_name']);
        return response()->json($employees);
    }

    public function getOrganizations(Request $request)
    {
        $organizations = Organization::all(['id', 'description']);
        return response()->json($organizations);
    }

    public function getRoles(Request $request)
    {
        $roles = Role::all();
        return response()->json($roles);
    }




}
