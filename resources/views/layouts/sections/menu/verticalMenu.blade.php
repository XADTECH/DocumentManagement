<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/xad/xad.jfif') }}" alt="XAD Image" style="height:80px">
            <span class="app-brand-text menu-text fw-bold ms-2">XAD Technology</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ps ps--active-y">

        @if (Auth::user()->role == 'Admin')

        @include('layouts.sections.menu.adminsidebar')
        @endif

        @if (Auth::user()->role == 'Project Manager')
        @include('layouts.sections.menu.pmosidebar')
        @endif


        @if (Auth::user()->role == 'Finance Manager' )
        @include('layouts.sections.menu.financesidebar')
        @endif

        @if (Auth::user()->role == 'Logistics' )
        @include('layouts.sections.menu.logisticssidebar')
        @endif
        @if (Auth::user()->role == 'Cashier' )
        @include('layouts.sections.menu.cashiersidebar')
        @endif

    </ul>

</aside>