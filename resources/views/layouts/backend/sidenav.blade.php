<!-- Sidebar -->
<div class="sidebar">
  <div class="brand">
    @php
      $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
      $companyName = $companySettings['name'] ?? 'eCommerce';
      $companyLogo = $companySettings['logo'] ?? null;
    @endphp
    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
      @if($companyLogo)
        <img src="{{ asset('storage/' . $companyLogo) }}" alt="" style="max-height: 32px; border-radius: 4px;">
      @else
        <span class="logo-box" style="width:32px;height:32px;background:#1a73e8;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
      @endif
    </a>
  </div>
  <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="#" data-bs-toggle="collapse" data-bs-target="#productsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-box-seam"></i> Products</a>
  <div class="collapse {{ request()->routeIs(['admin.products.*', 'admin.categories.*', 'admin.sub-categories.*', 'admin.brands.*', 'admin.attributes.*', 'admin.attribute-values.*']) ? 'show' : '' }}" id="productsSubmenu">
    <a href="{{ route('admin.products.index') }}" class="ps-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> All Products</a>
    <a href="{{ route('admin.products.create') }}" class="ps-4 {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i> Add Product</a>
    <a href="{{ route('admin.categories.index') }}" class="ps-4 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
    <a href="{{ route('admin.sub-categories.index') }}" class="ps-4 {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Sub Categories</a>
    <a href="{{ route('admin.brands.index') }}" class="ps-4 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Brands</a>
    <a href="{{ route('admin.attributes.index') }}" class="ps-4 {{ request()->routeIs('admin.attributes.index', 'admin.attributes.create', 'admin.attributes.edit') ? 'active' : '' }}"><i class="bi bi-palette"></i> Attributes</a>
    <a href="{{ route('admin.attribute-values.index') }}" class="ps-4 {{ request()->routeIs('admin.attribute-values.index', 'admin.attributes.values.*') ? 'active' : '' }}"><i class="bi bi-list-ul"></i> Attribute Values</a>
  </div>
  <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Orders</a>
  <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><i class="bi bi-ticket-perforated"></i> Coupons</a>
  <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><i class="bi bi-star"></i> Reviews</a>
  <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"><i class="bi bi-file-text"></i> Pages</a>
  <a href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu" aria-expanded="false" class="dropdown-toggle {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Reports</a>
  <div class="collapse {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}" id="reportsSubmenu">
    <a href="{{ route('admin.reports.sales') }}" class="ps-4 {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Sales Report</a>
    <a href="{{ route('admin.reports.stock') }}" class="ps-4 {{ request()->routeIs('admin.reports.stock') ? 'active' : '' }}"><i class="bi bi-boxes"></i> Stock Report</a>
  </div>
  <a href="{{ route('admin.settings.homepage') }}" class="{{ request()->routeIs('admin.settings.homepage') ? 'active' : '' }}"><i class="bi bi-house"></i> Homepage Settings</a>
  <a href="{{ route('admin.settings.company') }}" class="{{ request()->routeIs('admin.settings.company') ? 'active' : '' }}"><i class="bi bi-building"></i> Company Settings</a>
</div>
