<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Registration</title>
</head>
<body>
    <h2>Register a New Account</h2>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <br>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <br>
        <div>
            <label>Password (min 8 characters):</label>
            <input type="password" name="password" required>
        </div>
        <br>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <br>
        <div>
            <label>Role:</label>
            <select name="role" required>
                <!-- For now, we allow selecting Manager, this can be removed later -->
                <option value="user">User (Employee)</option>
                <option value="manager">Manager</option>
            </select>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>
    
    <p><a href="/login">Already have an account? Login here.</a></p>
</body>
</html>
