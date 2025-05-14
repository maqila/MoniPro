<x-auth>
    {{-- <div class="auth-logo">
        <a href="/"><img src="./assets/compiled/svg/logo.svg" alt="Logo"></a>
    </div> --}}
    <h1 class="auth-title">Sign Up</h1>
    <p class="auth-subtitle mb-5">Input your data to register to Dashboard MoniPro.</p>

    <form action="/register" method="POST">
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('name') is-invalid @enderror"
                placeholder="Name" name="name" id="name" autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }} </div>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror"
                placeholder="Email" name="email" id="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }} </div>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-envelope"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror"
                placeholder="Username" name="username" id="username">
            @error('username')
                <div class="invalid-feedback">{{ $message }} </div>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                placeholder="Password" name="password" id="password">
            @error('password')
                <div class="invalid-feedback">{{ $message }} </div>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>Already have an account? <a href="/" class="font-bold">Log
                in</a>.</p>
    </div>
</x-auth>
