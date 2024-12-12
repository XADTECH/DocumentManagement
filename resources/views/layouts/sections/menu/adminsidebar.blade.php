<li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
    <a href="/dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
    </a>
</li>

<li
    class="menu-item {{ request()->is('departments') || request()->is('departments') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Department Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('departments') ? 'active' : '' }}">
            <a href="/departments" class="menu-link">
                <div>Deparment List</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('add-department') ? 'active' : '' }}">
            <a href="/add-departments" class="menu-link">
                <div>Add Deparment</div>
            </a>
        </li>
    </ul>
</li>

<li
    class="menu-item {{ request()->is('subcategories') || request()->is('add-subcategories') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-category"></i>
        <div>Subcategory Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('subcategories') ? 'active' : '' }}">
            <a href="/subcategories" class="menu-link">
                <div>Subcategory List</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('add-subcategory') ? 'active' : '' }}">
            <a href="/add-subcategory" class="menu-link">
                <div>Add Subcategory</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->is('upload-document') ? 'active' : '' }}">
    <a href="/upload-document" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>Upload Document</div>
    </a>
</li>
<li class="menu-item {{ request()->is('documents') ? 'active' : '' }}">
    <a href="/documents" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>My Documents</div>
    </a>
</li>

<li class="menu-item {{ request()->is('approvals') ? 'active' : '' }}">
    <a href="/approvals" class="menu-link">
        <i class="menu-icon tf-icons bx bx-detail"></i>
        <div>Documents For Approvals</div>
    </a>
</li>



<!-- User Management -->
<li
    class="menu-item {{ request()->is('/users') || request()->is('/add-user') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>User Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('/users') ? 'active' : '' }}">
            <a href="/users" class="menu-link">
                <div>Users List</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('/add-user') ? 'active' : '' }}">
            <a href="/add-user" class="menu-link">
                <div>Add User</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->is('approvals-history') ? 'active' : '' }}">
    <a href="/approvals-history" class="menu-link">
        <i class="menu-icon tf-icons bx bx-detail"></i>
        <div>Approvals History</div>
    </a>
</li>

<li class="menu-item {{ request()->is('settings') ? 'active' : '' }}">
    <a href="/settings" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div>Settings</div>
    </a>
</li>