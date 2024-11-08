<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="#"><img src="https://via.placeholder.com/100x200" alt="logo" width="100" height="200" /></a>
        <a class="sidebar-brand brand-logo-mini" href="#"><img src="https://via.placeholder.com/100x200" alt="logo mini" width="100" height="200" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('home')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-view-dashboard-outline"></i> <!-- Dashboard icon -->
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('machine.blade')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-cogs"></i> <!-- Machines icon -->
                </span>
                <span class="menu-title">Machines</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('inventory.blade')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-warehouse"></i> <!-- Inventory icon -->
                </span>
                <span class="menu-title">Inventory</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('item.blade')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-cube-outline"></i> <!-- Items icon -->
                </span>
                <span class="menu-title">Items</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('transaction.blade')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-currency-usd"></i> <!-- Transactions icon -->
                </span>
                <span class="menu-title">Transactions</span>
            </a>
        </li>
    </ul>
</nav>
