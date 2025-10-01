<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Telkom Schools</title>
    <link rel="stylesheet" href="../../css/style.css" />
    @vite('resources/css/style.css')
</head>

<body>
    <div class="container">
        <!-- Panel kiri -->
        <div class="form-box">
            <div class="logo">
                <img src="{{ asset('img/WhatsApp Image 2025-09-19 at 08.49.57_8928faaa.png') }}" alt="Logo Telkom Schools">
            </div>

            <form action="{{ route('login-user') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if (Session::has('fail'))
                    <div class="alert alert-danger">
                        {{ Session::get('fail') }}
                    </div>
                @endif

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan Username" />
                <span class="text-danger">
                    @error('username')
                        {{ $message }}
                    @enderror
                </span>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password" />
                <span class="text-danger">
                    @error('password')
                        {{ $message }}
                    @enderror
                </span>


                <div class="remember">
                    <input type="checkbox" id="remember" />
                    <label for="remember">Remember me</label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-success">Login</button>
                </div>
                <br>
                <a href="registration">Registration</a>
            </form>


        </div>

        <!-- Bagian kanan -->
        <div class="gambar">
            <img src="img/gambar.png" alt="Illustration" />
        </div>
    </div>
</body>

</html>
