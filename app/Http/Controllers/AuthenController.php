<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\tb_dudi;
use App\Models\tb_admin;
use App\Models\tb_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
        $user->password = Hash::make($request->password);
        $user->role = 'siswa';
        $user->id_admin = null;
        $user->id_dudi = null;
        $user->id_siswa = $siswa->id;



        $result = $user->save();
        if ($result) {
            // Log activity
            logActivity(
                'create',
                'Siswa Baru Terdaftar',
                "Siswa {$siswa->nama} (NIS: {$siswa->nis}) berhasil mendaftar",
                $user->id
            );
            return back()->with('success', 'Registered successfully.');
        } else {
            return back()->with('fail', 'Something went wrong!');
        }
    }
    public function registrationDudi()
    {
        return view('auth.registrationDudi');
    }
    public function registerUserDudi(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|max:20'
        ]);

        $dudi = new tb_dudi();
        $dudi->nama_dudi = $request->nama_dudi;
        $dudi->nomor_telpon = $request->nomor_telpon;
        $dudi->alamat = $request->alamat;
        $dudi->person_in_charge = $request->person_in_charge;

        $dudi->save();



        $user = new User();
        $user->username = $dudi->nama_dudi;
        $user->password = Hash::make($request->password);
        $user->role = 'dudi';
        $user->id_admin = null;
        $user->id_dudi = $dudi->id;
        $user->id_siswa = null;



        $result = $user->save();
        if ($result) {
            // Log activity
            logActivity(
                'create',
                'DUDI Baru Terdaftar',
                "DUDI {$dudi->nama_dudi} berhasil mendaftar dengan PIC: {$dudi->person_in_charge}",
                $user->id
            );
            return back()->with('success', 'Registered successfully.');
        } else {
            return back()->with('fail', 'Something went wrong.');
        }
    }
    public function registrationAdmin()
    {
        return view('auth.registrationAdmin');
    }
    public function registerUserAdmin(Request $request)
    {
        // Pastikan nama_admin unik di tb_users.username untuk mencegah duplicate key
        $request->validate([
            'nama_admin' => 'required|unique:tb_users,username',
            'password' => 'required|min:8|max:20'
        ]);

        $admin = new tb_admin();
        $admin->nama_admin = $request->nama_admin;
        $admin->no_telpon = $request->no_telpon;
        $admin->alamat = $request->alamat;


        $admin->save();



        $user = new User();
        $user->username = $admin->nama_admin;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->id_admin = $admin->id;
        $user->id_dudi = null;
        $user->id_siswa = null;



        try {
            $result = $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani duplicate key secara ramah apabila validasi gagal karena race condition
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                return back()->with('fail', 'Username sudah digunakan. Gunakan nama lain.');
            }
            throw $e;
        }

        if ($result) {
            // Log activity
            logActivity(
                'create',
                'Admin Baru Terdaftar',
                "Admin {$admin->nama_admin} berhasil terdaftar di sistem",
                $user->id
            );
            return back()->with('success', 'Registered successfully.');
        } else {
            return back()->with('fail', 'Something went wrong!');
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
            'username' => 'required',
            'password' => 'required|min:8|max:20'
        ]);

        // Trim whitespace dari username untuk menghindari error
        $username = trim($request->username);

        $user = User::where('username', '=', $username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('loginId', $user->id);
                $request->session()->put('role', $user->role);

                // Simpan ID dan nama sesuai role
                if ($user->role === 'siswa' && $user->id_siswa) {
                    $siswa = tb_siswa::find($user->id_siswa);
                    if ($siswa) {
                        $request->session()->put('id', $siswa->id);
                        $request->session()->put('id_siswa', $siswa->id);
                        $request->session()->put('nama', $siswa->nama);
                    }
                } elseif ($user->role === 'dudi' && $user->id_dudi) {
                    $dudi = tb_dudi::find($user->id_dudi);
                    if ($dudi) {
                        $request->session()->put('id', $dudi->id);
                        $request->session()->put('id_dudi', $dudi->id);
                        $request->session()->put('nama_dudi', $dudi->nama_dudi);
                    }
                } elseif ($user->role === 'admin' && $user->id_admin) {
                    $admin = tb_admin::find($user->id_admin);
                    if ($admin) {
                        $request->session()->put('id', $admin->id);
                        $request->session()->put('id_admin', $admin->id);
                        $request->session()->put('nama', $admin->nama);
                    }
                }

                // Log activity dengan detail yang lebih spesifik
                $roleText = 'User';
                if ($user->role === 'siswa')
                    $roleText = 'Siswa';
                elseif ($user->role === 'dudi')
                    $roleText = 'DUDI';
                elseif ($user->role === 'admin')
                    $roleText = 'Admin';

                logActivity(
                    'login',
                    "{$roleText} Login",
                    "Login berhasil ke dalam sistem",
                    $user->id
                );

                return redirect('dashboard');
            } else {
                return back()->with('fail', 'Password salah! Pastikan password yang dimasukkan benar.');
            }
        } else {
            return back()->with('fail', 'Username tidak terdaftar.');
        }
    }
    //// Dashboard
    public function dashboard()
    {
        // return "Welcome to your dashabord.";
        $data = null;
        $role = null;
        if (Session::has('loginId')) {
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
            $totalSiswa = DB::table('tb_siswa')->count();

            // Ambil pengajuan PKL siswa jika ada
            $pengajuan = \App\Models\PengajuanPkl::with([
                'dudiPilihan1',
                'dudiPilihan2',
                'dudiPilihan3',
                'dudiMandiriPilihan1',
                'dudiMandiriPilihan2',
                'dudiMandiriPilihan3'
            ])
                ->where('id_siswa', $data->id)
                ->first();

            return view('dashboardSiswa', compact('data', 'pengajuan'));

        } elseif ($role === 'admin') {
            // Ambil aktivitas terkini untuk dashboard admin
            $activities = getRecentActivities(10);

            $totalSiswa = DB::table('tb_siswa')->count();
            $totalDudi = DB::table('tb_dudi')->count();

            $siswaLastMonth = DB::table('tb_siswa')
                ->where('created_at', '<', now()->subDays(30))
                ->count();

            $dudiLastMonth = DB::table('tb_dudi')
                ->where('created_at', '<', now()->subDays(30))
                ->count();

            // Hitung pertumbuhan
            $siswaGrowth = $totalSiswa - $siswaLastMonth;
            $dudiGrowth = $totalDudi - $dudiLastMonth;

            // Hitung siswa ditempatkan dan menunggu
            $siswaDitempatkan = DB::table('tb_siswa')
                ->where('status_penempatan', 'ditempatkan')
                ->orWhere('status_penempatan', 'selesai')
                ->count();

            $siswaMenunggu = DB::table('tb_siswa')
                ->where('status_penempatan', 'belum')
                ->count();

            // Hitung persentase
            $persenDitempatkan = $totalSiswa > 0 ? round(($siswaDitempatkan / $totalSiswa) * 100, 1) : 0;

            return view('dashboardAdmin', compact('data', 'activities', 'totalSiswa', 'totalDudi', 'siswaGrowth', 'dudiGrowth', 'siswaDitempatkan', 'siswaMenunggu', 'persenDitempatkan'));

        } elseif ($role === 'dudi') {
            // Ambil statistik lamaran untuk DUDI
            $idDudi = $data->id;

            // Total lamaran masuk
            $totalLamaran = \App\Models\PengajuanPkl::where(function ($query) use ($idDudi) {
                $query->where(function ($q) use ($idDudi) {
                    $q->where('pilihan_aktif', '1')
                        ->where('id_dudi_pilihan_1', $idDudi);
                })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '2')
                            ->where('id_dudi_pilihan_2', $idDudi);
                    })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '3')
                            ->where('id_dudi_pilihan_3', $idDudi);
                    });
            })->count();

            // Lamaran pending
            $lamaranPending = \App\Models\PengajuanPkl::where(function ($query) use ($idDudi) {
                $query->where(function ($q) use ($idDudi) {
                    $q->where('pilihan_aktif', '1')
                        ->where('id_dudi_pilihan_1', $idDudi);
                })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '2')
                            ->where('id_dudi_pilihan_2', $idDudi);
                    })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '3')
                            ->where('id_dudi_pilihan_3', $idDudi);
                    });
            })
                ->where('status', 'pending')
                ->count();

            // Siswa yang diterima (ditempatkan di DUDI ini)
            $siswaDiterima = DB::table('tb_siswa')
                ->where('id_dudi', $idDudi)
                ->where('status_penempatan', 'ditempatkan')
                ->count();

            return view('dashboardDudi', compact('data', 'totalLamaran', 'lamaranPending', 'siswaDiterima'));

        } else {
            Session::flush();
            return redirect('login')->with('fail', 'Unauthorized access');
        }
    }
    ///Logout
    public function logout()
    {
        $data = array();
        if (Session::has('loginId')) {
            $userId = Session::get('loginId');
            $user = User::find($userId);

            // Log activity sebelum logout
            if ($user) {
                logActivity(
                    'info',
                    'User Logout',
                    "User {$user->username} ({$user->role}) keluar dari sistem",
                    $userId
                );
            }

            Session::pull('loginId');
            return redirect('login');
        }
    }
}
