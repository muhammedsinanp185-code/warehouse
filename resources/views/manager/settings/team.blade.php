@extends('manager.settings.layout')

@section('settings_content')
<div class="stat-card" style="display: block; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: var(--text-color); margin: 0;">Employee Management</h2>
        <button type="button" class="btn-action btn-add" onclick="openModal('addEmployeeModal')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18" style="margin-right: 0.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Employee
        </button>
    </div>
    
    <div class="table-container" style="box-shadow: none; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 0;">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td style="font-weight: 500;">{{ $employee->name }}</td>
                    <td style="color: var(--text-muted);">{{ $employee->email }}</td>
                    <td>
                        @if($employee->role == 'manager')
                            <span class="badge" style="padding: 0.2rem 0.5rem; background: rgba(16, 185, 129, 0.2); color: #10b981; border-color: rgba(16, 185, 129, 0.3);">Manager</span>
                        @else
                            <span class="badge" style="padding: 0.2rem 0.5rem; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border-color: rgba(59, 130, 246, 0.3);">User</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="action-icon" style="color: #ef4444;" onclick="openDeleteEmployeeModal({{ $employee->id }}, '{{ addslashes($employee->name) }}')" title="Delete Account">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">No other employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('extra_modals')
    <!-- Add Employee Modal -->
    <div class="modal-overlay" id="addEmployeeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Employee</h2>
                <button class="modal-close" onclick="closeModal('addEmployeeModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('settings.employees.store') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" class="form-input" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <select name="role" class="form-input" required style="background: transparent; -webkit-appearance: none; color: var(--text-color);">
                        <option value="" disabled selected hidden>Select Role</option>
                        <option value="user" style="color: black;">User (Warehouse Employee)</option>
                        <option value="manager" style="color: black;">Manager (Full Access)</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <input type="password" name="password" class="form-input" placeholder="Temporary Password" required minlength="8">
                </div>
                <button type="submit" class="auth-button">Create Account</button>
            </form>
        </div>
    </div>

    <!-- Delete Employee Modal -->
    <div class="modal-overlay" id="deleteEmployeeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Employee</h2>
                <button class="modal-close" onclick="closeModal('deleteEmployeeModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <p style="color: var(--text-color); margin-bottom: 2rem;">
                Are you sure you want to delete <strong id="delete_employee_name"></strong>'s account? <br>
                <span style="color: #ef4444; font-size: 0.9rem;">They will lose all access to the system immediately.</span>
            </p>
            <form method="POST" id="deleteEmployeeForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="auth-button" style="background: #ef4444; color: white; border: none;">Revoke Access & Delete</button>
            </form>
        </div>
    </div>
@endsection

@section('extra_scripts')
<script>
    function openDeleteEmployeeModal(id, name) {
        document.getElementById('delete_employee_name').innerText = name;
        document.getElementById('deleteEmployeeForm').action = '/manager/settings/employees/' + id;
        openModal('deleteEmployeeModal');
    }
</script>
@endsection
