<li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
    <a href="/dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
    </a>
</li>

<li class="menu-item {{ request()->is('departments') || request()->is('departments') ? 'active open' : '' }}">
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

<li class="menu-item {{ request()->is('subcategories') || request()->is('add-subcategories') ? 'active open' : '' }}">
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

<li class="menu-item {{ request()->is('document-types') || request()->is('add-document-type') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>Document Type Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('document-types') ? 'active' : '' }}">
            <a href="/document-types" class="menu-link">
                <div>Document Type List</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('document-types/add') ? 'active' : '' }}">
            <a href="{{ route('document-types.create') }}" class="menu-link">
                <div>Add Document Type</div>
            </a>
        </li>

    </ul>
</li>


<li class="menu-item {{ request()->is('add-document') ? 'active' : '' }}">
    <a href="/add-document" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>Upload Document</div>
    </a>
</li>

<li class="menu-item 
    {{ request()->routeIs('documents.myDocuments') 
       || request()->routeIs('documents.pending')
       || request()->routeIs('documents.approved')
       || request()->routeIs('documents.rejected') 
       ? 'active open' : '' 
    }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-file"></i>
    <div>Document Management</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ request()->routeIs('documents.myDocuments') ? 'active' : '' }}">
      {{-- Notice the route() helper here --}}
      <a href="{{ route('documents.myDocuments') }}" class="menu-link">
        <div>List</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('documents.pending') ? 'active' : '' }}">
      <a href="{{ route('documents.pending') }}" class="menu-link">
        <div>Pending Documents</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('documents.approved') ? 'active' : '' }}">
      <a href="{{ route('documents.approved') }}" class="menu-link">
        <div>Approved Documents</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('documents.rejected') ? 'active' : '' }}">
      <a href="{{ route('documents.rejected') }}" class="menu-link">
        <div>Rejected Documents</div>
      </a>
    </li>
  </ul>
</li>

{{-- <li class="menu-item {{ request()->is('approvals') ? 'active' : '' }}">
    <a href="/approvals" class="menu-link">
        <i class="menu-icon tf-icons bx bx-detail"></i>
        <div>Documents For Approvals</div>
    </a>
</li> --}}



<!-- User Management -->
<li class="menu-item {{ request()->is('/users') || request()->is('/add-user') ? 'active open' : '' }}">
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

{{-- <li class="menu-item {{ request()->is('approvals-history') ? 'active' : '' }}">
    <a href="/approvals-history" class="menu-link">
        <i class="menu-icon tf-icons bx bx-detail"></i>
        <div>Approvals History</div>
    </a>
</li> --}}

<li class="menu-item {{ request()->is('settings') ? 'active' : '' }}">
    <a href="/settings" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div>Settings</div>
    </a>
</li>
