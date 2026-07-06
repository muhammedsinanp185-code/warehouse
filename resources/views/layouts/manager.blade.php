<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow Manager</title>
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
                <a href="{{ route('manager.dashboard') }}" class="sidebar-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                    Dashboard
                </a>
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-link sidebar-dropdown-toggle {{ request()->is('manager/products*') ? 'active open' : '' }}" onclick="toggleSubmenu('productsSubmenu', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        <span style="flex: 1;">Products</span>
                        <svg class="dropdown-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px; transition: transform 0.3s ease; {{ request()->is('manager/products*') ? 'transform: rotate(180deg);' : '' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <div class="sidebar-submenu" id="productsSubmenu" style="display: {{ request()->is('manager/products*') || request()->is('manager/categories*') ? 'flex' : 'none' }};">
                        <a href="#" class="submenu-link">
                            <span class="submenu-dot"></span> Category
                        </a>
                    </div>
                </div>
                <a href="{{ route('manager.inventory') }}" class="sidebar-link {{ request()->routeIs('manager.inventory') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                    Inventory
                </a>
                <a href="{{ route('manager.settings') }}" class="sidebar-link {{ request()->is('manager/settings*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="topbar">
                <div style="flex: 1;">
                    <h1 style="font-size: 1.5rem; font-weight: 300; letter-spacing: 2px; text-transform: uppercase;">@yield('page_title', 'DASHBOARD')</h1>
                </div>
                
                <div class="topbar-actions">
                    <!-- 1. Dark Mode Toggle -->
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle Dark Mode">
                        <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- 2. Notification Icon -->
                    <button class="action-icon" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span class="notification-badge"></span>
                    </button>

                    <!-- 3. Profile Dropdown Container -->
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

    <!-- Add Product Modal (Available Globally) -->
    <div class="modal-overlay" id="addProductModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button class="modal-close" onclick="closeModal('addProductModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="form-group"><input type="text" name="name" class="form-input" placeholder="Product Name (e.g. iPhone 15)" required></div>
                <div class="form-group"><input type="text" name="sku" class="form-input" placeholder="SKU (e.g. PHN-IPH-15)" required></div>
                <div class="form-group"><input type="number" step="0.01" name="price" class="form-input" placeholder="Price ($)" required></div>
                <div class="form-group"><input type="number" name="quantity" class="form-input" placeholder="Initial Quantity" min="0" required></div>
                <div class="form-group" style="margin-bottom: 2rem;"><input type="number" name="min_stock_level" class="form-input" placeholder="Low Stock Alert Threshold" min="0" required></div>
                <button type="submit" class="auth-button btn-add">Save Product</button>
            </form>
        </div>
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
            <form method="POST" action="{{ route('inventory.receive') }}">
                @csrf
                <div class="form-group">
                    <select name="product_id" class="form-input" required style="background: transparent; -webkit-appearance: none; color: var(--text-color);">
                        <option value="" disabled selected hidden>Select Product</option>
                        @if(isset($allProducts))
                            @foreach($allProducts as $product)
                                <option value="{{ $product->id }}" style="color: black;">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" name="quantity" class="form-input" placeholder="Quantity to Receive" min="1" required>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <input type="text" name="reference_party" class="form-input" placeholder="From (Supplier / Sender)">
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
            <form method="POST" action="{{ route('inventory.dispatch') }}">
                @csrf
                <div class="form-group">
                    <select name="product_id" class="form-input" required style="background: transparent; -webkit-appearance: none; color: var(--text-color);">
                        <option value="" disabled selected hidden>Select Product</option>
                        @if(isset($allProducts))
                            @foreach($allProducts as $product)
                                <option value="{{ $product->id }}" style="color: black;">{{ $product->name }} ({{ $product->quantity }} in stock)</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" name="quantity" class="form-input" placeholder="Quantity to Dispatch" min="1" required>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <input type="text" name="reference_party" class="form-input" placeholder="To (Client / Destination)">
                </div>
                <button type="submit" class="auth-button btn-dispatch">Dispatch from Inventory</button>
            </form>
        </div>
    </div>

    @yield('extra_modals')

    <!-- Javascript -->
    <script>
        // --- Modal Logic ---
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        
        // --- Dark Mode Logic ---
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

        // --- Profile Dropdown Logic ---
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

        // --- Submenu Toggle Logic ---
        function toggleSubmenu(id, el) {
            const submenu = document.getElementById(id);
            const chevron = el.querySelector('.dropdown-chevron');
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'flex';
                chevron.style.transform = 'rotate(180deg)';
                el.classList.add('open');
            } else {
                submenu.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
                el.classList.remove('open');
            }
        }
    </script>
    @yield('extra_scripts')
</body>
</html>
