@extends('manager.settings.layout')

@section('settings_content')
<div class="stat-card" style="display: block; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: var(--text-color); margin: 0;">Profile Information</h2>
        <button type="button" class="btn-action" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2);" onclick="openModal('editProfileModal')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="margin-right: 0.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
            Edit Profile
        </button>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; color: var(--text-color);">
        <div>
            <p style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Full Name</p>
            <p style="font-size: 1.1rem; font-weight: 500;">{{ $user->name }}</p>
        </div>
        <div>
            <p style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Email Address</p>
            <p style="font-size: 1.1rem; font-weight: 500;">{{ $user->email }}</p>
        </div>
        <div>
            <p style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Account Role</p>
            <p style="font-size: 1.1rem; font-weight: 500;">
                <span class="badge" style="padding: 0.2rem 0.5rem; background: rgba(16, 185, 129, 0.2); color: #10b981; border-color: rgba(16, 185, 129, 0.3);">{{ ucfirst($user->role) }}</span>
            </p>
        </div>
        <div>
            <p style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Member Since</p>
            <p style="font-size: 1.1rem; font-weight: 500;">{{ $user->created_at->format('F d, Y') }}</p>
        </div>
    </div>
</div>
@endsection

@section('extra_modals')
    <div class="modal-overlay" id="editProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="modal-close" onclick="closeModal('editProfileModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('settings.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="text" name="name" class="form-input" placeholder="Full Name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <input type="email" name="email" class="form-input" placeholder="Email Address" value="{{ old('email', $user->email) }}" required>
                </div>
                <button type="submit" class="auth-button">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
