@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;background:linear-gradient(135deg,var(--beige),white);">
    <div style="width:100%;max-width:420px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:64px;height:64px;background:linear-gradient(135deg,var(--forest),var(--ocean));border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <svg width="30" height="30" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 style="color:var(--earth);font-size:1.8rem;margin:0 0 .4rem;">Reset Password</h1>
            <p style="color:var(--text-muted);font-size:.9rem;margin:0;">Enter your new password below.</p>
        </div>

        <div style="background:white;border-radius:20px;box-shadow:var(--shadow-lg);padding:2.5rem;">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $request->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label">New Password</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimum 8 characters" required>
                        <button type="button" onclick="togglePwd('password','eye1')" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">
                            <svg id="eye1" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Reset Password</button>
            </form>
        </div>
    </div>
</div>

<script>
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
