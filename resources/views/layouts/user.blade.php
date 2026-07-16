<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow User Workspace</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="dashboard-layout">
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; color: var(--text-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
                <h2 style="font-weight: 700; letter-spacing: 1px; text-transform: none;">StockFlow<span style="color: #3b82f6;">.</span></h2>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                    Workspace
                </a>
                
                <a href="{{ route('user.products.index') }}" class="sidebar-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    Product Directory
                </a>

                <a href="{{ route('user.activity') }}" class="sidebar-link {{ request()->routeIs('user.activity') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                    My Activity
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="topbar">
                <div style="flex: 1;">
                    <h1 style="font-size: 1.5rem; font-weight: 300; letter-spacing: 2px; text-transform: uppercase;">@yield('page_title', 'WORKSPACE')</h1>
                </div>
                
                <div class="topbar-actions">
                    <!-- 1. Dark Mode Toggle -->
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle Dark Mode">
                        <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- Profile Dropdown Container -->
                    <div class="profile-menu-container">
                        <button class="action-icon" id="profileToggle" aria-label="View Profile" title="View Profile">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="dropdown-user-info">
                                <div class="dropdown-name">{{ auth()->check() ? auth()->user()->name : 'User' }}</div>
                                <div class="dropdown-role">{{ auth()->check() ? auth()->user()->role : 'Role' }}</div>
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" /></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC CONTENT -->
            @yield('content')

        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg> 
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="toast error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg> 
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="toast error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                </svg> 
                {{ $error }}
            </div>
            @endforeach
        @endif
    </div>

    <!-- Receive Stock Modal -->
    <div class="modal-overlay" id="receiveStockModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Receive Stock</h2>
                <button class="modal-close" onclick="closeModal('receiveStockModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('user.inventory.receive') }}">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Select Product</label>
                    <div class="form-group" style="margin-bottom: 0;">
                        <select name="product_id" class="form-input" required style="background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color);">
                            <option value="" disabled selected hidden>Select Product</option>
                            @if(isset($allProducts))
                                @foreach($allProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Quantity to Receive</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="number" name="quantity" class="form-input" placeholder="Quantity to Receive" min="1" required></div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">From (Supplier / Sender)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="text" name="reference_party" class="form-input" placeholder="From (Supplier / Sender)"></div>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Transaction Date (Optional Backdate)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="datetime-local" name="transaction_date" class="form-input" title="Leave blank to use current time"></div>
                </div>
                <button type="submit" class="auth-button btn-receive">Receive into Inventory</button>
            </form>
        </div>
    </div>

    <!-- Dispatch Stock Modal -->
    <div class="modal-overlay" id="dispatchStockModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Dispatch Stock</h2>
                <button class="modal-close" onclick="closeModal('dispatchStockModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('user.inventory.dispatch') }}">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Select Product</label>
                    <div class="form-group" style="margin-bottom: 0;">
                        <select name="product_id" class="form-input" required style="background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color);">
                            <option value="" disabled selected hidden>Select Product</option>
                            @if(isset($allProducts))
                                @foreach($allProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->quantity }} in stock)</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Quantity to Dispatch</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="number" name="quantity" class="form-input" placeholder="Quantity to Dispatch" min="1" required></div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">To (Client / Destination)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="text" name="reference_party" class="form-input" placeholder="To (Client / Destination)"></div>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Transaction Date (Optional Backdate)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="datetime-local" name="transaction_date" class="form-input" title="Leave blank to use current time"></div>
                </div>
                <button type="submit" class="auth-button btn-dispatch">Dispatch from Inventory</button>
            </form>
        </div>
    </div>

    @yield('extra_modals')

    <!-- Javascript -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlEl = document.documentElement;
        
        const moonIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />';
        const sunIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />';

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            htmlEl.setAttribute('data-theme', 'dark');
            themeIcon.innerHTML = sunIcon;
        } else if (savedTheme === 'light') {
            htmlEl.removeAttribute('data-theme');
            themeIcon.innerHTML = moonIcon;
        } else {
            themeIcon.innerHTML = moonIcon;
        }

        themeToggle.addEventListener('click', () => {
            themeIcon.classList.remove('animate-rotate');
            if (htmlEl.getAttribute('data-theme') === 'dark') {
                htmlEl.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                themeIcon.innerHTML = moonIcon;
            } else {
                htmlEl.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.innerHTML = sunIcon;
            }
            setTimeout(() => {
                themeIcon.classList.add('animate-rotate');
            }, 10);
        });

        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.getElementById('profileDropdown');

        if(profileToggle) {
            profileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            document.addEventListener('click', (e) => {
                if (!profileDropdown.contains(e.target) && e.target !== profileToggle) {
                    profileDropdown.classList.remove('show');
                }
            });
        }
    </script>
    @yield('extra_scripts')
</body>
</html>
