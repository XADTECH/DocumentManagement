<li
    class="menu-item {{ request()->is('pages/cashflow/create') || request()->is('pages/allocate-cash') || request()->is('pages/cash-receive-amount') || request()->is('pages/plan-cash-report') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-money"></i>
        <div>Fund Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('pages/cashflow/create') ? 'active' : '' }}">
            <a href="/pages/cashflow/create" class="menu-link">
                <div>Inflow / OutFlow</div>
            </a>
        </li>
    </ul>
</li>

<!-- payment Order -->
<li class="menu-item {{ request()->is('pages/add-budget-project-payment-order') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-dollar"></i>
        <div>Payment Order</div>
    </a>
    <ul class="menu-sub">
        <li
            class="menu-item {{ request()->is('pages/add-budget-project-payment-order') ? 'active' : '' }}">
            <a href="/pages/add-budget-project-payment-order" class="menu-link">
                <div>Add Payment Order</div>
            </a>
        </li>
    </ul>
</li>

<!-- Bank Management -->
<li class="menu-item {{ request()->is('pages/add-bank-detail') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
        <div>Bank Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('pages/add-bank-detail') ? 'active' : '' }}">
            <a href="/pages/add-bank-detail" class="menu-link">
                <div>Add Bank</div>
            </a>
        </li>
    </ul>
</li>

<li
    class="menu-item {{ request()->is('pages/budget-lists') || request()->is('pages/cash-flow-list') || request()->routeIs('show-allocated-budgets') || request()->is('filter-purchase-orders') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-pen"></i>
        <div>Report</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('pages/budget-lists') ? 'active' : '' }}">
            <a href="/pages/budget-lists" class="menu-link">
                <div>Budget Report</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('pages/cash-flow-list') ? 'active' : '' }}">
            <a href="/pages/cash-flow-list" class="menu-link">
                <div>Cash Flow Report</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('filter-purchase-orders') ? 'active' : '' }}">
            <a href="/filter-purchase-orders" class="menu-link">
                <div>PO Report</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('show-allocated-budgets') ? 'active' : '' }}">
            <a href="{{ route('show-allocated-budgets') }}" class="menu-link">
                <div>Track Budget</div>
            </a>
        </li>
    </ul>
</li>