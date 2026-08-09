
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
    <ul id="sidebar_menu" class="metismenu">
      @auth
        @php
          $userType = auth()->user()->user_type;
          $isMainUser = !in_array($userType, [3, 4, 5]);
          $isAdmin = $userType == 1;
          $canAddSales = $userType != 5;
          $stockOpen = request()->is('stock_category*')
            || request()->is('stock_sub_category*')
            || request()->is('stock_unit*')
            || request()->is('stocks*')
            || request()->is('expense*');
          $customerOpen = request()->is('customers*') || request()->is('customer*');
          $salesOpen = request()->is('sales*');
          $assetsOpen = request()->is('assets*');
          $businessOpen = request()->is('business-expenses*');
          $cashOpen = request()->is('cash*');
          $reportOpen = request()->is('report*');
        @endphp

        @if($isMainUser)
          <li>
            <a href="{{ url('/dashboard') }}" aria-expanded="false" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/dashboard.svg') }}" alt />
              </div>
              <span>Dashboard</span>
            </a>
          </li>

          {{-- Stocks --}}
          <li class="{{ $stockOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $stockOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Stocks</span>
            </a>
            <ul class="mm-collapse {{ $stockOpen ? 'mm-show' : '' }}">
              @if($isAdmin)
                <li>
                  <a href="{{ route('stock_category.index') }}" class="{{ request()->is('stock_category*') ? 'active' : '' }}">
                    Stock Categories
                  </a>
                </li>
              @endif
              <li>
                <a href="{{ route('stock_sub_category.index') }}" class="{{ request()->is('stock_sub_category*') ? 'active' : '' }}">
                  Stock Sub Categories
                </a>
              </li>
              <li>
                <a href="{{ route('stock_unit.index') }}" class="{{ request()->is('stock_unit*') ? 'active' : '' }}">
                  Stock Units
                </a>
              </li>
              <li>
                <a href="{{ route('stocks.index') }}" class="{{ request()->is('stocks') || request()->is('stocks/*/edit') ? 'active' : '' }}">
                  Stocks
                </a>
              </li>
              <li>
                <a href="{{ route('stocks.create') }}" class="{{ request()->is('stocks/create') ? 'active' : '' }}">
                  Add Stocks
                </a>
              </li>
              <li>
                <a href="{{ route('expense') }}" class="{{ request()->is('expense') || request()->is('expense/create') ? 'active' : '' }}">
                  Expenses
                </a>
              </li>
            </ul>
          </li>

          {{-- Customers --}}
          <li class="{{ $customerOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $customerOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/18.svg') }}" alt />
              </div>
              <span>Customers</span>
            </a>
            <ul class="mm-collapse {{ $customerOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('customers.index') }}" class="{{ (request()->is('customers') || request()->is('customer*') || request()->is('customers/*/edit')) && !request()->is('customers/create') ? 'active' : '' }}">
                  Customers
                </a>
              </li>
              <li>
                <a href="{{ route('customers.create') }}" class="{{ request()->is('customers/create') ? 'active' : '' }}">
                  Add Customers
                </a>
              </li>
            </ul>
          </li>

          {{-- Sales --}}
          <li class="{{ $salesOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $salesOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Sales</span>
            </a>
            <ul class="mm-collapse {{ $salesOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('sales.index') }}" class="{{ request()->is('sales') || request()->is('sales/*/edit') || request()->is('sales/upload-images*') || request()->is('sales/print*') ? 'active' : '' }}">
                  Sales
                </a>
              </li>
              @if($canAddSales)
                <li>
                  <a href="{{ route('sales.create') }}" class="{{ request()->is('sales/create') ? 'active' : '' }}">
                    Add Sales
                  </a>
                </li>
              @endif
            </ul>
          </li>

          {{-- Assets --}}
          <li class="{{ $assetsOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $assetsOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Assets</span>
            </a>
            <ul class="mm-collapse {{ $assetsOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('assets.index') }}" class="{{ request()->is('assets') || request()->is('assets/create') ? 'active' : '' }}">
                  Assets
                </a>
              </li>
              <li>
                <a href="{{ route('assets.used.create') }}" class="{{ request()->is('assets/used*') ? 'active' : '' }}">
                  Assets Use
                </a>
              </li>
            </ul>
          </li>

          {{-- Business Expenses --}}
          <li class="{{ $businessOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $businessOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Business Expenses</span>
            </a>
            <ul class="mm-collapse {{ $businessOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('business-expenses.index') }}" class="{{ request()->is('business-expenses') || request()->is('business-expenses/*/edit') ? 'active' : '' }}">
                  Business Expenses
                </a>
              </li>
              <li>
                <a href="{{ route('business-expenses.create') }}" class="{{ request()->is('business-expenses/create') ? 'active' : '' }}">
                  Add Business Expense
                </a>
              </li>
            </ul>
          </li>

          {{-- Cash --}}
          <li class="{{ $cashOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $cashOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Cash</span>
            </a>
            <ul class="mm-collapse {{ $cashOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('cash.index') }}" class="{{ request()->is('cash') || request()->is('cash/*/edit') ? 'active' : '' }}">
                  Cash Records
                </a>
              </li>
              <li>
                <a href="{{ route('cash.create') }}" class="{{ request()->is('cash/create') ? 'active' : '' }}">
                  Add Cash
                </a>
              </li>
            </ul>
          </li>
        @else
          {{-- Limited users --}}
          <li>
            <a href="{{ route('stocks.create') }}" aria-expanded="false" class="{{ request()->is('stocks/create') ? 'active' : '' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
              </div>
              <span>Add Stocks</span>
            </a>
          </li>
          @if($canAddSales)
            <li>
              <a href="{{ route('sales.create') }}" aria-expanded="false" class="{{ request()->is('sales/create') ? 'active' : '' }}">
                <div class="icon_menu">
                  <img src="{{ asset('admin-assets/img/menu-icon/5.svg') }}" alt />
                </div>
                <span>Add Sales</span>
              </a>
            </li>
          @endif
        @endif

        @if($isAdmin)
          <li class="{{ $reportOpen ? 'mm-active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="{{ $reportOpen ? 'true' : 'false' }}">
              <div class="icon_menu">
                <img src="{{ asset('admin-assets/img/menu-icon/19.svg') }}" alt />
              </div>
              <span>Reports</span>
            </a>
            <ul class="mm-collapse {{ $reportOpen ? 'mm-show' : '' }}">
              <li>
                <a href="{{ route('report.stock') }}" class="{{ request()->is('report/stock*') ? 'active' : '' }}">Stocks</a>
              </li>
              <li>
                <a href="{{ route('report.assets') }}" class="{{ request()->is('report/assets*') ? 'active' : '' }}">Used Assets</a>
              </li>
              <li>
                <a href="{{ route('report.business.expense') }}" class="{{ request()->is('report/business-expense*') ? 'active' : '' }}">Business Expense</a>
              </li>
            </ul>
          </li>
        @endif
      @endauth
    </ul>
</nav>
