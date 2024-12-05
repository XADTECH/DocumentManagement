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