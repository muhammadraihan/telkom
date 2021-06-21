<aside class="page-sidebar">
    <div class="page-logo">
        <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative"
            data-toggle="modal" data-target="#modal-shortcut">
            <img src="{{asset('img/telkom_logo.png')}}" alt="{{env('APP_NAME','')}}" aria-roledescription="logo">
            <span class="page-logo-text mr-1">{{env('APP_NAME','')}}</span>
            <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
            <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
        </a>
    </div>
    <!-- BEGIN PRIMARY NAVIGATION -->
    <nav id="js-primary-nav" class="primary-nav" role="navigation">
        <div class="nav-filter">
            <div class="position-relative">
                <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control" tabindex="0">
                <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                    data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                    <i class="fal fa-chevron-up"></i>
                </a>
            </div>
        </div>
        {{-- <div class="info-card">
            <img src="{{asset('img/avatar').'/'.Auth::user()->avatar}}" class="profile-image rounded-circle"
        alt="Dr. Codex Lantern">
        <div class="info-card-text">
            <a href="#" class="d-flex align-items-center text-white">
                <span class="text-truncate text-truncate-sm d-inline-block">
                    {{Auth::user()->name}}
                </span>
            </a>
            <span class="d-inline-block text-truncate text-truncate-sm">Toronto, Canada</span>
        </div>
        <img src="{{asset('img/card-backgrounds/cover-2-lg.png')}}" class="cover" alt="cover">
        <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle"
            data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
            <i class="fal fa-angle-down"></i>
        </a>
        </div> --}}
        <ul id="js-nav-menu" class="nav-menu">
            <li>
                <a href="{{route('backoffice.dashboard')}}" title="Dashboard" data-filter-tags="dashboard">
                    <i class="fal fa-desktop"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            @hasanyrole('superadmin|gudang')

            @endhasanyrole
            @hasanyrole('superadmin|cs')
            <li class="">
                <a href="#" title="Theme Settings" data-filter-tags="theme settings">
                    <i class="fal fa-user-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.theme_settings">Customer Management</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('unit.index')}}" title="Unit" data-filter-tags="unit">
                            <i class="fal fa-user-plus"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Unit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('witel.index')}}" title="Witel"
                            data-filter-tags="witel">
                            <i class="fal fa-users"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Witel</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|gudang')
            <li>
                <a href="{{route('gudang.index')}}" title="Gudang Job Order Managements"
                    data-filter-tags="gudang job order managements">
                    <i class="fal fa-boxes"></i>
                    <span class="nav-link-text" data-i18n="nav.users_managements">Gudang Job Order Management</span>
                </a>
            </li>
            <li>
                <a href="{{route('itemreplace.index')}}" title="Item Replace Managements"
                    data-filter-tags="item replace managements">
                    <i class="fal fa-retweet"></i>
                    <span class="nav-link-text" data-i18n="nav.users_managements">Item Replace Management</span>
                </a>
            </li>
            <li class="">
                <a href="#" title="Theme Settings" data-filter-tags="theme settings">
                    <i class="fal fa-list"></i>
                    <span class="nav-link-text" data-i18n="nav.theme_settings">Module Management</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('category.index')}}" title="Category Managements"
                            data-filter-tags="category managements">
                            <i class="fal fa-suitcase"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('name.index')}}" title="Module Name Managements"
                            data-filter-tags="name managements">
                            <i class="fal fa-suitcase"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Name</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('brand.index')}}" title="Module Brand Managements"
                            data-filter-tags="brand managements">
                            <i class="fal fa-suitcase"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Brand</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('type.index')}}" title="Module Type Managements"
                            data-filter-tags="type managements">
                            <i class="fal fa-suitcase"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Type</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('stock.index')}}" title="Module Stock Managements"
                            data-filter-tags="stock managements">
                            <i class="fal fa-suitcase"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Stock</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{route('material.index')}}" title="Material Managements"
                    data-filter-tags="material managements">
                    <i class="fal fa-retweet"></i>
                    <span class="nav-link-text" data-i18n="nav.users_managements">Material Management</span>
                </a>
            </li>
            <li class="">
                <a href="#" title="Theme Settings" data-filter-tags="theme settings">
                    <i class="fal fa-list"></i>
                    <span class="nav-link-text" data-i18n="nav.theme_settings">Stock Item Management</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('accessory.index')}}" title="accessory Managements"
                            data-filter-tags="accessory managements">
                            <i class="fal fa-clipboard-check"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Accessory</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|teknisi')
            <li>
                <a href="{{route('teknisi.index')}}" title="Technician Job Order Managements"
                    data-filter-tags="technician job order managements">
                    <i class="fal fa-cogs"></i>
                    <span class="nav-link-text" data-i18n="nav.users_managements">Technician Job Order Management</span>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|cs')
            <li>
                <a href="{{route('ticketing.index')}}" title="Ticketing Managements"
                    data-filter-tags="ticketing managements">
                    <i class="fal fa-ticket-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.users_managements">Ticketing Management</span>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin')
            <li class="nav-title">ACL & Settings</li>
            <li class="">
                <a href="#" title="Theme Settings" data-filter-tags="theme settings">
                    <i class="fal fa-cog"></i>
                    <span class="nav-link-text" data-i18n="nav.theme_settings">Access Control List</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('users.index')}}" title="Users Managements"
                            data-filter-tags="users managements">
                            <span class="nav-link-text" data-i18n="nav.users_managements">Users Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('permissions.index')}}" title="Permissions Managements"
                            data-filter-tags="permissions managements">
                            <span class="nav-link-text" data-i18n="nav.permissions_managements">Permissions
                                Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('roles.index')}}" title="Roles Managements"
                            data-filter-tags="roles managements">
                            <span class="nav-link-text" data-i18n="nav.roles_managements">Roles
                                Management</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="{{route('logs')}}" title="System Log" data-filter-tags="System Log">
                    <i class="fal fa-shield-check"></i>
                    <span class="nav-link-text" data-i18n="nav.system_log">System Logs</span>
                </a>
            </li>
            @endhasanyrole
        </ul>
        <div class="filter-message js-filter-message bg-success-600"></div>
    </nav>
    <!-- END PRIMARY NAVIGATION -->
    <!-- NAV FOOTER -->
    <div class="nav-footer shadow-top">
        <a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify"
            class="hidden-md-down">
            <i class="ni ni-chevron-right"></i>
            <i class="ni ni-chevron-right"></i>
        </a>
    </div> <!-- END NAV FOOTER -->
</aside>