@extends('layouts.manager')

@section('page_title', 'SYSTEM SETTINGS')

@section('content')
<div style="display: flex; gap: 2rem; max-width: 1200px; flex-wrap: wrap;">
    <!-- Sub-navigation Sidebar -->
    <div style="width: 250px; flex-shrink: 0; flex-grow: 1; max-width: 100%;">
        <div class="stat-card" style="padding: 1rem 0; display: block;">
            <a href="{{ route('settings.profile') }}" style="display: block; padding: 0.75rem 1.5rem; color: {{ request()->routeIs('settings.profile') ? '#3b82f6' : 'var(--text-color)' }}; text-decoration: none; border-left: 3px solid {{ request()->routeIs('settings.profile') ? '#3b82f6' : 'transparent' }}; font-weight: {{ request()->routeIs('settings.profile') ? '600' : '400' }}; background: {{ request()->routeIs('settings.profile') ? 'rgba(59, 130, 246, 0.05)' : 'transparent' }}; transition: all 0.2s;">
                User Profile
            </a>
            <a href="{{ route('settings.security') }}" style="display: block; padding: 0.75rem 1.5rem; color: {{ request()->routeIs('settings.security') ? '#3b82f6' : 'var(--text-color)' }}; text-decoration: none; border-left: 3px solid {{ request()->routeIs('settings.security') ? '#3b82f6' : 'transparent' }}; font-weight: {{ request()->routeIs('settings.security') ? '600' : '400' }}; background: {{ request()->routeIs('settings.security') ? 'rgba(59, 130, 246, 0.05)' : 'transparent' }}; transition: all 0.2s;">
                Security & Password
            </a>
            <a href="{{ route('settings.team') }}" style="display: block; padding: 0.75rem 1.5rem; color: {{ request()->routeIs('settings.team') ? '#3b82f6' : 'var(--text-color)' }}; text-decoration: none; border-left: 3px solid {{ request()->routeIs('settings.team') ? '#3b82f6' : 'transparent' }}; font-weight: {{ request()->routeIs('settings.team') ? '600' : '400' }}; background: {{ request()->routeIs('settings.team') ? 'rgba(59, 130, 246, 0.05)' : 'transparent' }}; transition: all 0.2s;">
                Team Management
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div style="flex-grow: 999; flex-basis: 0; min-width: 300px;">
        @yield('settings_content')
    </div>
</div>
@endsection
