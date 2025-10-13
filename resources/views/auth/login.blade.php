<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Telkom Schools</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/style.css')
</head>

<body>
    <div class="container">
        <!-- Panel kiri -->
        <div class="form-box">
            <div class="logo">
                <img src="{{ asset('img/telkom-logo.png') }}" alt="Logo Telkom Schools">
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
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Masukkan Password" />
                    <span id="togglePassword">
                        <i class="far fa-eye"></i>
                    </span>
                </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing password toggle...');

            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");

            if (togglePassword && passwordInput) {
                const icon = togglePassword.querySelector("i");

                // Show/hide icon based on input
                passwordInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        togglePassword.classList.add('show');
                        console.log('Password icon shown');
                    } else {
                        togglePassword.classList.remove('show');
                        console.log('Password icon hidden');
                    }
                });

                // Toggle password visibility
                if (icon) {
                    togglePassword.addEventListener("click", function() {
                        console.log('Toggle password clicked!');

                        const isPassword = passwordInput.type === "password";
                        passwordInput.type = isPassword ? "text" : "password";

                        // Ganti ikon
                        if (isPassword) {
                            icon.classList.remove("far", "fa-eye");
                            icon.classList.add("far", "fa-eye-slash");
                            console.log('Password shown');
                        } else {
                            icon.classList.remove("far", "fa-eye-slash");
                            icon.classList.add("far", "fa-eye");
                            console.log('Password hidden');
                        }
                    });
                    console.log('Password toggle initialized successfully!');
                }
            }
        });
    </script>
</body>

</html>
