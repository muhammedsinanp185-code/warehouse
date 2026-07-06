@extends('manager.settings.layout')

@section('settings_content')
<div class="stat-card" style="display: block; padding: 2rem;">
    <h2 style="margin-bottom: 1.5rem; color: var(--text-color);">Change Password</h2>
    <form method="POST" action="{{ route('settings.password') }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem; max-width: 400px;">
            <div class="form-group" style="margin: 0;">
                <input type="password" name="current_password" class="form-input" placeholder="Current Password" required style="margin: 0;">
            </div>
            <div class="form-group" style="margin: 0;">
                <input type="password" name="password" class="form-input" placeholder="New Password" required style="margin: 0;">
            </div>
            <div class="form-group" style="margin: 0;">
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm New Password" required style="margin: 0;">
            </div>
        </div>
        <button type="submit" class="btn-action" style="background: rgba(255,255,255,0.1); color: var(--text-color); border: 1px solid rgba(255,255,255,0.2);">Update Password</button>
    </form>
</div>
@endsection
