
<nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">
    <div class="logo d-flex justify-content-center">
       <a href="{{url('/dashboard')}}" class="duaa-sidebar-brand">
        <span class="duaa-brand-text">DARZI</span>
        <span class="duaa-brand-sub">SHOP</span>
      </a>
       <div class="sidebar_close_icon d-lg-none">
          <i class="ti-close"></i>
       </div>
    </div>
    <ul id="sidebar_menu" class="metismenu ">
      @auth
        @if(auth()->user()->user_type != 3 && auth()->user()->user_type != 4 && auth()->user()->user_type != 5)
      <li class="">
        <a href="{{url('/dashboard')}}" aria-expanded="false" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/dashboard.svg')}}" alt />
          </div>
          <span>Dashboard</span>
        </a>
      </li>
      @auth
          @if(auth()->user()->user_type == 1)
      <li class>
        <a href="{{route('stock_category.index')}}" aria-expanded="false" class="{{ request()->is('stock_category*') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Stock Categories</span>
        </a>
      </li>
      @endauth
      @endif
      
       <li class>
        <a href="{{route('stock_sub_category.index')}}" aria-expanded="false" class="{{ request()->is('stock_sub_category*') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Stock Sub Categories</span>
        </a>
      </li>
      
      <li class>
        <a href="{{route('stock_unit.index')}}" aria-expanded="false" class="{{ request()->is('stock_unit*') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Stock Units</span>
        </a>
      </li>
      <li class>
        <a href="{{route('stocks.index')}}" aria-expanded="false" class="{{ request()->is('stocks*') && !request()->is('stocks/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Stocks</span>
        </a>
      </li>
      
       <li class>
        <a href="{{route('expense')}}" aria-expanded="false" class="{{ request()->is('expense*') && !request()->is('expense') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Expenses</span>
        </a>
      </li>
      
      <li class>
        <a href="{{route('customers.index')}}" aria-expanded="false" class="{{ (request()->is('customers*') || request()->is('customer*')) && !request()->is('customers/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/18.svg')}}" alt />
          </div>
          <span>Customers</span>
        </a>
      </li>
      <li class>
        <a href="{{route('customers.create')}}" aria-expanded="false" class="{{ request()->is('customers/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/18.svg')}}" alt /><sup class="sidebar_add_icon"><i class="fas fa-plus"></i></sup>
          </div>
          <span>Add Customers</span>
        </a>
      </li>
      {{-- <li class>
        <a href="{{route('tailors.index')}}" aria-expanded="false" class="{{ (request()->is('tailors*') || request()->is('tailor*')) && !request()->is('tailors/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/18.svg')}}" alt />
          </div>
          <span>Tailors</span>
        </a>
      </li>
      <li class>
        <a href="{{route('tailors.create')}}" aria-expanded="false" class="{{ request()->is('tailors/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/18.svg')}}" alt /><sup class="sidebar_add_icon"><i class="fas fa-plus"></i></sup>
          </div>
          <span>Add Tailor</span>
        </a>
      </li> --}}
      <li class>
        <a href="{{route('sales.index')}}" aria-expanded="false" class="{{ request()->is('sales*') && !request()->is('sales/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Sales</span>
        </a>
      </li>
            <li class>
        <a href="{{route('assets.index')}}" aria-expanded="false" class="{{ request()->is('assets*') && !request()->is('sales/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt />
          </div>
          <span>Assets</span>
        </a>
      </li>
        @endif
      @endauth
      <li class>
        <a href="{{route('stocks.create')}}" aria-expanded="false" class="{{ request()->is('stocks/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt /><sup class="sidebar_add_icon"><i class="fas fa-plus"></i></sup>
          </div>
          <span>Add Stocks</span>
        </a>
      </li>
      @auth
        @if(auth()->user()->user_type != 5)
      <li class>
        <a href="{{route('sales.create')}}" aria-expanded="false" class="{{ request()->is('sales/create') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/5.svg')}}" alt /><sup class="sidebar_add_icon"><i class="fas fa-plus"></i></sup>
          </div>
          <span>Add Sales</span>
        </a>
      </li>
        @endif
      @endauth
      @auth
          @if(auth()->user()->user_type == 1)
      <li class="{{ request()->is('report*') ? 'mm-active' : '' }}">
        <a class="has-arrow" href="#" aria-expanded="false" class="{{ request()->is('report*') ? 'active' : '' }}">
          <div class="icon_menu">
            <img src="{{asset('admin-assets/img/menu-icon/19.svg')}}" alt />
          </div>
          <span>Reports</span>
        </a>
        <ul class="mm-collapse {{ request()->is('report*') ? 'mm-show' : '' }}">
          <li><a href="{{route('report.stock')}}" class="{{ request()->is('report/stock') ? 'active' : '' }}">Stocks</a></li>
          <li><a href="{{route('report.assets')}}" class="{{ request()->is('report/stock') ? 'active' : '' }}">Used Assets</a></li>
        </ul>
      </li>
      @endif
      @endauth
      

    </ul>
</nav>