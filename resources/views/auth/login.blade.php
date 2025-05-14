<x-auth>
    {{-- <div class="auth-logo">
        <a href="/"><img src="./assets/compiled/svg/logo.svg" alt="Logo"></a>
    </div> --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>
    @endif
    @if (session()->has('loginError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session()->get('loginError') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>
    @endif
    <h1 class="auth-title">Log in.</h1>
    <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

    <form action="/login" method="POST">
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror"
                placeholder="Username" name="username" id="username" autofocus value="{{ old('username') }}">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl" placeholder="Password" name="password"
                id="password" required>
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray-600">Don't have an account? <a href="/register" class="font-bold">Sign
                up</a>.</p>
    </div>
</x-auth>
