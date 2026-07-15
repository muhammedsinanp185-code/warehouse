@extends('layouts.manager')

@section('page_title', 'DASHBOARD')

@section('content')
            <div class="stat-grid">
                <!-- Stat Card 1 -->
                <div class="stat-card" onclick="window.location.href='{{ route('manager.products.index') }}'" style="cursor: pointer;">
                    <div class="stat-icon icon-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Total Products</h4>
                        <h2>{{ number_format($totalProducts) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="stat-card">
                    <div class="stat-icon icon-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="stat-details" style="min-width: 0; flex: 1; overflow: hidden;">
                        <h4>Inventory Value</h4>
                        <h2 id="inventoryValueText" style="font-size: 1.8rem; white-space: nowrap;">₹{{ number_format($inventoryValue, 2) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="stat-card" onclick="window.location.href='{{ route('manager.low-stock') }}'" style="cursor: pointer;" title="Click to view low stock items">
                    <div class="stat-icon icon-red">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Low Stock Items</h4>
                        <h2>{{ number_format($lowStockCount) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="stat-card">
                    <div class="stat-icon icon-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Today's Activity</h4>
                        <h2>{{ number_format($todayActivity) }}</h2>
                    </div>
                </div>
            </div>



            <!-- Graph Card -->
            <div class="dashboard-card" style="margin-bottom: 2rem; background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; padding: 1.5rem; backdrop-filter: blur(10px);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 class="dashboard-section-title" style="margin-bottom: 0;">Stock Movement Trends</h3>
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-color); cursor: pointer;">
                            <input type="checkbox" id="compareCheckbox" style="cursor: pointer;">
                            Compare to previous
                        </label>
                        <select id="chartTimeRange" class="form-input" style="width: 130px; padding: 0.3rem 1.5rem 0.3rem 0.5rem; border-radius: 6px; background-color: var(--glass-bg-10); color: var(--text-color); border: 1px solid var(--glass-border-20); font-size: 0.85rem; outline: none; cursor: pointer;">
                            <option value="day" selected>Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="3months">3 Months</option>
                            <option value="6months">6 Months</option>
                            <option value="custom">Custom</option>
                        </select>
                        <div id="customDateContainer" style="display: none; align-items: center; gap: 0.5rem;">
                            <input type="date" id="customStartDate" style="padding: 0.3rem; border-radius: 6px; background: var(--glass-bg-10); color: var(--text-color); border: 1px solid var(--glass-border-20); font-size: 0.85rem;">
                            <span style="color: var(--text-muted); font-size: 0.85rem;">to</span>
                            <input type="date" id="customEndDate" style="padding: 0.3rem; border-radius: 6px; background: var(--glass-bg-10); color: var(--text-color); border: 1px solid var(--glass-border-20); font-size: 0.85rem;">
                            <button id="applyCustomDateBtn" style="padding: 0.3rem 0.8rem; background: var(--btn-bg); color: #fff; border: none; border-radius: 6px; font-size: 0.85rem; cursor: pointer;">Apply</button>
                        </div>
                    </div>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="movementsChart"></canvas>
                </div>
            </div>

            <!-- Active Shifts Table -->
            <h3 class="dashboard-section-title">Active Team Shifts</h3>
            <div class="table-container" style="margin-bottom: 2rem;">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Role</th>
                            <th>Clock In Time</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeShifts as $shift)
                        <tr>
                            <td style="font-weight: 500;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 5px #10b981;"></div>
                                    {{ $shift->user->name }}
                                </div>
                            </td>
                            <td style="text-transform: capitalize; color: var(--text-muted);">{{ $shift->user->role }}</td>
                            <td>{{ $shift->started_at->format('M d, Y - g:i A') }}</td>
                            <td style="color: var(--text-muted);">{{ $shift->started_at->diffForHumans(null, true) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No employees are currently clocked in.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Recent Activity Table -->
            <h3 class="dashboard-section-title">Recent Stock Movements</h3>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMovements as $movement)
                        <tr>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                @if($movement->type == 'in')
                                    <span class="badge badge-in">Stock In</span>
                                @else
                                    <span class="badge badge-out">Stock Out</span>
                                @endif
                            </td>
                            <td>
                                {{ $movement->type == 'in' ? '+' : '-' }} {{ $movement->quantity }}
                            </td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No recent stock movements found. Start adding inventory!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
@endsection

@section('extra_modals')
    <!-- The Receive and Dispatch modals are handled in the master layout since they are used everywhere -->
@endsection

@section('extra_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('movementsChart').getContext('2d');
        let movementsChart = null;

        const timeRangeSelect = document.getElementById('chartTimeRange');

        // Create gradient for Stock In
        const gradientIn = ctx.createLinearGradient(0, 0, 0, 300);
        gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Green
        gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0)');

        // Create gradient for Stock Out
        const gradientOut = ctx.createLinearGradient(0, 0, 0, 300);
        gradientOut.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); // Red
        gradientOut.addColorStop(1, 'rgba(239, 68, 68, 0)');

        let currentChartData = null;

        function fetchChartData() {
            const range = timeRangeSelect.value;
            const compare = document.getElementById('compareCheckbox').checked ? 1 : 0;
            let url = `/manager/api/chart-data?range=${range}&compare=${compare}`;

            if (range === 'custom') {
                const start = document.getElementById('customStartDate').value;
                const end = document.getElementById('customEndDate').value;
                if (!start || !end) return;
                url += `&start=${start}&end=${end}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    currentChartData = data;
                    renderChart(data);
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }

        function renderChart(data) {
            if (movementsChart) {
                movementsChart.destroy();
            }

            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            // Hardcode exactly what color Canvas needs to draw for each mode (CSS vars break canvas rendering sometimes)
            const textColor = isDark ? '#94a3b8' : '#64748b'; 
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            const tooltipBg = isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)';
            const tooltipBorder = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = "'Inter', sans-serif";

            let datasets = [
                {
                    label: 'Stock Received',
                    data: data.received,
                    exactTimes: data.received_exact,
                    borderColor: '#10b981',
                    backgroundColor: gradientIn,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointRadius: 2,
                    pointHoverRadius: 4
                },
                {
                    label: 'Stock Dispatched',
                    data: data.dispatched,
                    exactTimes: data.dispatched_exact,
                    borderColor: '#ef4444',
                    backgroundColor: gradientOut,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointRadius: 2,
                    pointHoverRadius: 4
                }
            ];

            if (data.compare_received) {
                datasets.push({
                    label: 'Previous Received',
                    data: data.compare_received,
                    exactTimes: null,
                    borderColor: 'rgba(16, 185, 129, 0.4)',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.4,
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 0
                });
                datasets.push({
                    label: 'Previous Dispatched',
                    data: data.compare_dispatched,
                    exactTimes: null,
                    borderColor: 'rgba(239, 68, 68, 0.4)',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.4,
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 0
                });
            }

            movementsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                boxHeight: 6
                            }
                        },
                        tooltip: {
                            backgroundColor: tooltipBg,
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: tooltipBorder,
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                afterLabel: function(context) {
                                    let exact = context.dataset.exactTimes;
                                    if (exact && exact[context.dataIndex]) {
                                        return exact[context.dataIndex];
                                    }
                                    return null;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: { color: textColor }
                        },
                        y: {
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            beginAtZero: true,
                            ticks: { 
                                color: textColor,
                                precision: 0 
                            }
                        }
                    }
                }
            });
        }

        // Event listener for dropdown change
        timeRangeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('customDateContainer').style.display = 'flex';
            } else {
                document.getElementById('customDateContainer').style.display = 'none';
                fetchChartData();
            }
        });

        document.getElementById('compareCheckbox').addEventListener('change', function() {
            // Only fetch if it's not custom, or if custom has valid dates
            if (timeRangeSelect.value !== 'custom' || (document.getElementById('customStartDate').value && document.getElementById('customEndDate').value)) {
                fetchChartData();
            }
        });

        document.getElementById('applyCustomDateBtn').addEventListener('click', function() {
            fetchChartData();
        });

        // Auto-scale inventory value text if too long
        const invEl = document.getElementById('inventoryValueText');
        if (invEl) {
            let fontSize = 1.8;
            while(invEl.scrollWidth > invEl.parentElement.clientWidth && fontSize > 0.7) {
                fontSize -= 0.1;
                invEl.style.fontSize = fontSize + 'rem';
            }
        }

        // Listen for theme toggle to redraw canvas lines/text
        document.addEventListener('themeChanged', function() {
            if (currentChartData) {
                renderChart(currentChartData);
            }
        });

        // Initial fetch
        fetchChartData();
    });
</script>
@endsection