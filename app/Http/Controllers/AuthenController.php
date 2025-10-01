<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\tb_siswa;
use App\Models\tb_admin;
use App\Models\tb_dudi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class AuthenController extends Controller
{
    //Registration
    public function registrationSiswa()
    {
        return view('auth.registrationSiswa');
    }
    public function registerUserSiswa(Request $request)
    {
        // $request->validate([
        //     'name'=>'required',
        //     'email'=>'required|email:users',
        //     'password'=>'required|min:8|max:12'
        // ]);

        $siswa = new tb_siswa();
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->kelas = $request->kelas;
        $siswa->jenis_kelamin = $request->jenis_kelamin;
        $siswa->angkatan = $request->angkatan;
        $siswa->jurusan = $request->jurusan;
        $siswa->save();
        

         $user = new User();
         $user->username = $siswa->nis;
         $user->password = $request->password;
         $user->role = 'siswa';
         $user->id_admin = null;
         $user->id_dudi =null;
         $user->id_siswa = $siswa->id;



        $result = $user->save();
        if($result){
            return back()->with('success','Registered successfully.');
        } else {
            return back()->with('fail','Something went wrong!');
        }
    }
    public function registrationDudi()
    {
        return view('auth.registrationDudi');
    }
    public function registerUserDudi(Request $request)
    {
         $request->validate([
             'password'=>'required|min:8|max:12'
         ]);

        $dudi = new tb_dudi();
        $dudi->nama_dudi = $request->nama_dudi;
        $dudi->nomor_telpon = $request->nomor_telpon;
        $dudi->alamat = $request->alamat;
        $dudi->person_in_charge = $request->person_in_charge;

        $dudi->save();
        

         $user = new User();
         $user->username = $dudi->nama_dudi;
         $user->password = $request->password;
         $user->role = 'dudi';
         $user->id_admin = null;
         $user->id_dudi = $dudi->id;
         $user->id_siswa = null;



        $result = $user->save();
        if($result){
            return back()->with('success','Registered successfully.');
        } else {
            return back()->with('fail','Something went wrong.');
        }
    }
    public function registrationAdmin()
    {
        return view('auth.registrationAdmin');
    }
    public function registerUserAdmin(Request $request)
    {
        $request->validate([
            'password'=>'required|min:8|max:12'
        ]);

        $admin = new tb_admin();
        $admin->nama_admin = $request->nama_admin;
        $admin->no_telpon = $request->no_telpon;
        $admin->alamat = $request->alamat;
        
        $admin->save();
        

         $user = new User();
         $user->username = $admin->nama_admin;
         $user->password = $request->password;
         $user->role = 'admin';
         $user->id_admin = $admin->id;
         $user->id_dudi =null;
         $user->id_siswa = null;



        $result = $user->save();
        if($result){
            return back()->with('success','Registered successfully.');
        } else {
            return back()->with('fail','Something went wrong!');
        }
    }
    
    
    ////Login
    public function login()
    {
        return view('auth.login');
    }
    public function loginUser(Request $request)
    {
        $request->validate([            
            'username'=>'required',
            'password'=>'required|min:8|max:20'
        ]);

        $user = User::where('username','=',$request->username)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                $request->session()->put('role', $user->role);
                return redirect('dashboard');
            } else {
                return back()->with('fail','Password does not match!');
            }
        } else {
            return back()->with('fail','This email is not registered.');
        }        
    }
    //// Dashboard
    public function dashboard()
    {
        // return "Welcome to your dashabord.";
        $data = null;
        $role = null;
        if(Session::has('loginId')){
            $user = User::where('id', Session::get('loginId'))->first();
            if ($user) {
                $role = $user->role;
                if ($role === 'siswa' && $user->id_siswa) {
                    $data = tb_siswa::find($user->id_siswa);
                } elseif ($role === 'admin' && $user->id_admin) {
                    $data = tb_admin::find($user->id_admin);
                } elseif ($role === 'dudi' && $user->id_dudi) {
                    $data = tb_dudi::find($user->id_dudi);
                } else {
                    $data = $user;
                }
            }
        }
        if ($role === 'siswa') {
            return view('dashboardSiswa', compact('data'));
        } elseif ($role === 'admin') {
            return view('dashboardAdmin', compact('data'));
        } elseif ($role === 'dudi') {
            return view('dashboardDudi', compact('data'));
        } else {
            return view('dashboard', compact('data', 'role'));
        }
    }
    ///Logout
    public function logout()
    {
        $data = array();
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('login');
        }
    }
}
