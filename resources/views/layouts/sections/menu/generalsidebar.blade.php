<li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
    <a href="/dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
    </a>
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


