<li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
    <a href="/dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
    </a>
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


