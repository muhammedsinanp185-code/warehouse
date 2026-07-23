@extends('layouts.manager')

@section('page_title', 'REPORTS CENTER')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <!-- Top Search Header (Zoho Style) -->
    <div style="margin-bottom: 2rem; text-align: center;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-color); margin: 0 0 1rem 0;">Reports Center</h1>
        <div style="position: relative; max-width: 550px; margin: 0 auto;">
            <input type="text" id="reportSearch" placeholder="Search reports..." onkeyup="filterReports()" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); border-radius: 30px; color: var(--text-color); font-size: 0.95rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='var(--glass-border-20)'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
    </div>

    <!-- 2-Column Grid: Category Sidebar + Reports Tables -->
    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 2rem; align-items: start;">
        
        <!-- Left Category Sidebar -->
        <div class="table-container" style="padding: 1.25rem; min-height: auto; position: sticky; top: 1.5rem; z-index: 10;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.75px; margin-bottom: 1rem;">Report Category</div>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.35rem;" id="categoryMenu">
                <li>
                    <a href="#sales-section" onclick="scrollToSection(event, 'sales-section', this)" data-section="sales-section" class="cat-link active" style="display: flex; align-items: center; padding: 0.6rem 0.85rem; border-radius: 8px; color: #3b82f6; background: var(--glass-bg-10); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                        Sales
                    </a>
                </li>
                <li>
                    <a href="#inventory-section" onclick="scrollToSection(event, 'inventory-section', this)" data-section="inventory-section" class="cat-link" style="display: flex; align-items: center; padding: 0.6rem 0.85rem; border-radius: 8px; color: var(--text-color); font-weight: 500; text-decoration: none; font-size: 0.9rem; transition: background 0.2s;" onmouseover="if(!this.classList.contains('active')) this.style.background='var(--glass-bg-05)'" onmouseout="if(!this.classList.contains('active')) this.style.background='transparent'">
                        Inventory
                    </a>
                </li>
                <li>
                    <a href="#purchases-section" onclick="scrollToSection(event, 'purchases-section', this)" data-section="purchases-section" class="cat-link" style="display: flex; align-items: center; padding: 0.6rem 0.85rem; border-radius: 8px; color: var(--text-color); font-weight: 500; text-decoration: none; font-size: 0.9rem; transition: background 0.2s;" onmouseover="if(!this.classList.contains('active')) this.style.background='var(--glass-bg-05)'" onmouseout="if(!this.classList.contains('active')) this.style.background='transparent'">
                        Purchases & Payables
                    </a>
                </li>
                <li>
                    <a href="#activity-section" onclick="scrollToSection(event, 'activity-section', this)" data-section="activity-section" class="cat-link" style="display: flex; align-items: center; padding: 0.6rem 0.85rem; border-radius: 8px; color: var(--text-color); font-weight: 500; text-decoration: none; font-size: 0.9rem; transition: background 0.2s;" onmouseover="if(!this.classList.contains('active')) this.style.background='var(--glass-bg-05)'" onmouseout="if(!this.classList.contains('active')) this.style.background='transparent'">
                        Activity & Movement
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content Area: Grouped Reports -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <!-- Category 1: Sales -->
            <div id="sales-section" class="report-section-card">
                <div class="table-container" style="min-height: auto;">
                    <div style="padding: 1rem 1.25rem; background: var(--glass-bg-10); border-bottom: 1px solid var(--glass-border-20); display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-color);">Sales</h3>
                        <span style="background: rgba(59, 130, 246, 0.2); color: #3b82f6; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">6 Reports</span>
                    </div>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Report Name</th>
                                <th style="width: 25%;">Last Visited</th>
                                <th style="width: 25%;">Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="report-row" data-name="sales by customer">
                                <td>
                                    <a href="{{ route('manager.reports.sales-by-customer') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: #f59e0b;">★</span> Sales by Customer
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="sales by item">
                                <td>
                                    <a href="{{ route('manager.reports.sales-by-item') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Sales by Item
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="order fulfillment by item">
                                <td>
                                    <a href="{{ route('manager.reports.order-fulfillment') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Order Fulfillment By Item
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">-</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="packing history">
                                <td>
                                    <a href="{{ route('manager.reports.packing-history') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Packing History
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">-</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="sales order list details">
                                <td>
                                    <a href="{{ route('manager.sales_orders.index') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Sales Order Summary
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Category 2: Inventory -->
            <div id="inventory-section" class="report-section-card">
                <div class="table-container" style="min-height: auto;">
                    <div style="padding: 1rem 1.25rem; background: var(--glass-bg-10); border-bottom: 1px solid var(--glass-border-20); display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-color);">Inventory</h3>
                        <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">3 Reports</span>
                    </div>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Report Name</th>
                                <th style="width: 25%;">Last Visited</th>
                                <th style="width: 25%;">Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="report-row" data-name="inventory valuation summary">
                                <td>
                                    <a href="{{ route('manager.inventory-report') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: #f59e0b;">★</span> Inventory Valuation Summary
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="low stock alert report">
                                <td>
                                    <a href="{{ route('manager.low-stock') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: #f59e0b;">★</span> Low Stock Alert Report
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="product catalog stock">
                                <td>
                                    <a href="{{ route('manager.products.index') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Active Product Catalog & Stock
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Category 3: Purchases & Payables -->
            <div id="purchases-section" class="report-section-card">
                <div class="table-container" style="min-height: auto;">
                    <div style="padding: 1rem 1.25rem; background: var(--glass-bg-10); border-bottom: 1px solid var(--glass-border-20); display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-color);">Purchases & Payables</h3>
                        <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">2 Reports</span>
                    </div>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Report Name</th>
                                <th style="width: 25%;">Last Visited</th>
                                <th style="width: 25%;">Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="report-row" data-name="purchases by vendor">
                                <td>
                                    <a href="{{ route('manager.reports.purchases-by-vendor') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: #f59e0b;">★</span> Purchases by Vendor
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="purchase order summary">
                                <td>
                                    <a href="{{ route('manager.purchase-orders.index') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Purchase Order Summary
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Category 4: Activity & Movement -->
            <div id="activity-section" class="report-section-card">
                <div class="table-container" style="min-height: auto;">
                    <div style="padding: 1rem 1.25rem; background: var(--glass-bg-10); border-bottom: 1px solid var(--glass-border-20); display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-color);">Activity & Movement</h3>
                        <span style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">2 Reports</span>
                    </div>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Report Name</th>
                                <th style="width: 25%;">Last Visited</th>
                                <th style="width: 25%;">Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="report-row" data-name="overall inventory stock movement audit log">
                                <td>
                                    <a href="{{ route('manager.inventory') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: #f59e0b;">★</span> Inventory Movement Audit Log
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                            <tr class="report-row" data-name="dispatched items report history">
                                <td>
                                    <a href="{{ route('manager.inventory.dispatched') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--text-muted);">☆</span> Dispatched Stock History
                                    </a>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">Today</td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;">System Generated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let isClickScrolling = false;

    function filterReports() {
        const query = document.getElementById('reportSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.report-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function setActiveLink(activeEl) {
        document.querySelectorAll('.cat-link').forEach(link => {
            link.classList.remove('active');
            link.style.background = 'transparent';
            link.style.color = 'var(--text-color)';
            link.style.fontWeight = '500';
        });
        if (activeEl) {
            activeEl.classList.add('active');
            activeEl.style.background = 'var(--glass-bg-10)';
            activeEl.style.color = '#3b82f6';
            activeEl.style.fontWeight = '600';
        }
    }

    function scrollToSection(e, sectionId, clickedEl) {
        if (e) e.preventDefault();
        
        // Immediately highlight the clicked menu item
        setActiveLink(clickedEl);
        
        isClickScrolling = true;
        const target = document.getElementById(sectionId);
        if (target) {
            const yOffset = -20;
            const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }

        setTimeout(() => {
            isClickScrolling = false;
        }, 700);
    }

    // ScrollSpy feature to sync category sidebar with current visible section during manual scroll
    window.addEventListener('scroll', function() {
        if (isClickScrolling) return;

        const sections = document.querySelectorAll('.report-section-card');
        let currentSectionId = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.offsetHeight;
            if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionTop + sectionHeight) {
                currentSectionId = section.getAttribute('id');
            }
        });
        
        if (currentSectionId) {
            const targetLink = document.querySelector(`.cat-link[data-section="${currentSectionId}"]`);
            if (targetLink && !targetLink.classList.contains('active')) {
                setActiveLink(targetLink);
            }
        }
    });
</script>
@endsection
