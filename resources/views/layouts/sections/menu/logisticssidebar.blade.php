  <!-- Budget Management -->
  <li class="menu-item {{ request()->is('pages/add-project-*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-rocket"></i>
          <div>Budget Management</div>
      </a>
      <ul class="menu-sub">
          <li class="menu-item {{ request()->is('pages/add-project-budget') ? 'active' : '' }}">
              <a href="/pages/add-project-budget" class="menu-link">
                  <div>Add Project Budget</div>
              </a>
          </li>
          <li class="menu-item {{ request()->is('pages/add-project-name') ? 'active' : '' }}">
              <a href="/pages/add-project-name" class="menu-link">
                  <div>Add Project Name</div>
              </a>
          </li>
          <li class="menu-item {{ request()->is('pages/add-business-unit') ? 'active' : '' }}">
              <a href="/pages/add-business-unit" class="menu-link">
                  <div>Add Business Unit</div>
              </a>
          </li>
          <li class="menu-item {{ request()->is('pages/add-business-client') ? 'active' : '' }}">
              <a href="/pages/add-business-client" class="menu-link">
                  <div>Add Client</div>
              </a>
          </li>
      </ul>
  </li>