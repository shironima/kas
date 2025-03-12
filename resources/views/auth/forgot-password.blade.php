<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lupa Password - KAS RT</title>

    <!-- Argon Dashboard CSS -->
    <link href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-image: url('{{ asset('assets/img/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            position: relative;
        }
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        .main-content {
            z-index: 2;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="bg-overlay"></div>

    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-white shadow border-0">
                        <div class="card-header bg-transparent pb-3 text-center">
                            <h4 class="text-dark">Lupa Password?</h4>
                            <p class="text-muted mb-0">
                                Masukkan email Anda untuk mengatur ulang password.
                            </p>
                        </div>
                        <div class="card-body px-lg-5 py-lg-4">

                            <!-- Success Message -->
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                
                                <!-- Email Input -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="email" name="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email') }}" required autofocus 
                                               placeholder="name@example.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-send"></i> Kirim Link Reset Password
                                    </button>
                                </div>

                                <!-- Back to Login Link -->
                                <div class="text-center mt-3">
                                    <a href="{{ route('login') }}" class="text-muted">
                                        <small><i class="bi bi-arrow-left"></i> Kembali ke halaman login</small>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Optional Footer Text -->
                    <div class="text-center mt-3 text-white">
                        <small>&copy; {{ date('Y') }} KAS RT. All Rights Reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Argon Dashboard JS -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
</body>
</html>
