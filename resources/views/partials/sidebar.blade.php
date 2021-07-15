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
        <ul id="js-nav-menu" class="nav-menu">
            <li>
                <a href="{{route('backoffice.dashboard')}}" title="Dashboard" data-filter-tags="dashboard">
                    <i class="fal fa-desktop"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            @hasanyrole('superadmin|admin')
            <li class="">
                <a href="#" title="Customer" data-filter-tags="customer settings">
                    <i class="fal fa-user-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.customer_settings">Customer</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('unit.index')}}" title="Unit" data-filter-tags="unit">
                            <i class="fal fa-user-plus"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Unit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('witel.index')}}" title="Witel" data-filter-tags="witel">
                            <i class="fal fa-users"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Witel</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin')
            <li class="">
                <a href="#" title="Reference Data" data-filter-tags="reference data">
                    <i class="fal fa-book"></i>
                    <span class="nav-link-text" data-i18n="nav.reference_data">Reference Data</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('accessory.index')}}" title="Accessory Data"
                            data-filter-tags="accessory managements">
                            <i class="fal fa-clipboard-check"></i>
                            <span class="nav-link-text" data-i18n="nav.accessory_data">Accessory</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('category.index')}}" title="Category Managements"
                            data-filter-tags="category managements">
                            <i class="fal fa-box"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements"> Module
                                Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('name.index')}}" title="Module Name Managements"
                            data-filter-tags="name managements">
                            <i class="fal fa-box"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Name</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('brand.index')}}" title="Module Brand Managements"
                            data-filter-tags="brand managements">
                            <i class="fal fa-box"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Brand</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('type.index')}}" title="Module Type Managements"
                            data-filter-tags="type managements">
                            <i class="fal fa-box"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module Type</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin|supervisi|repair')
            <li>
                <a href="#" title="Repair Department" data-filter-tags="repair departmen">
                    <i class="fal fa-cogs"></i>
                    <span class="nav-link-text" data-i18n="nav.repair_department">Repair Department</span>
                </a>
                <ul>
                    @hasanyrole('superadmin|admin|supervisi')
                    <li>
                        <a href="{{route('repair.index')}}" title="Repair Management"
                            data-filter-tags="repair management">
                            <i class="fal fa-clipboard-list"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Repair List</span>
                        </a>
                    </li>
                    @endhasanyrole
                    @hasanyrole('superadmin|admin|repair')
                    <li>
                        <a href="{{route('repair-job.index')}}" title="Repair Task" data-filter-tags="repair task">
                            <i class="fal fa-wrench"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_task">Repair Job</span>
                        </a>
                    </li>
                    @endhasanyrole
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin')
            <li>
                <a href="#" title="Reporting" data-filter-tags="report">
                    <i class="fal fa-clipboard-list"></i>
                    <span class="nav-link-text" data-i18n="nav.report">Reporting</span>
                </a>
                <ul>
                    @hasanyrole('superadmin|admin')
                    <li>
                        <a href="{{route('report.repair-module-tech')}}" title="Repair Module By Tech"
                            data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Repair Module By Tech</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('report.repair-module-vendor')}}" title="Repair Module By Vendor"
                            data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Repair Module By Vendor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('report.replace-module')}}" title="Module Replace" data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Module Replace</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('report.module-handle')}}" title="Module Handle" data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">All Module Handle</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('report.total-module-per-witel')}}" title="Total Module Per Witel"
                            data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Total Module Per Witel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('report.total-module-by-witel')}}" title="Total Module By Witel"
                            data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Total Module By Witel</span>
                        </a>
                    </li>
                    <li>
                        <a href="" title="Module Repair" data-filter-tags="repair management">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Total Module Handle</span>
                        </a>
                    </li>
                    <li>
                        <a href="" title="Module Repair" data-filter-tags="repair management">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Total Module Percentage</span>
                        </a>
                    </li>
                    <li>
                        <a href="" title="Witel" data-filter-tags="">
                            <i class="fal fa-file-excel"></i>
                            <span class="nav-link-text" data-i18n="nav.repair_management">Total Repair</span>
                        </a>
                    </li>
                    @endhasanyrole
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin')
            <li class="">
                <a href="#" title="Module & Material Stock" data-filter-tags="stock">
                    <i class="fal fa-inventory"></i>
                    <span class="nav-link-text" data-i18n="nav.module_material">Stock</span>
                </a>
                <ul>
                    <li>
                        <a href="{{route('material.index')}}" title="Material Managements"
                            data-filter-tags="material managements">
                            <i class="fal fa-microchip"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Material</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('stock.index')}}" title="Module Stock Managements"
                            data-filter-tags="stock managements">
                            <i class="fal fa-hdd"></i>
                            <span class="nav-link-text" data-i18n="nav.users_managements">Module</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin|customer-care')
            <li>
                <a href="{{route('ticketing.index')}}" title="Ticketing Management"
                    data-filter-tags="ticketing management">
                    <i class="fal fa-ticket-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.ticketing_managements">Ticket Complain</span>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin|warehouse')
            <li>
                <a href="{{route('warehouse.index')}}" title="Warehouse" data-filter-tags="Warehouse">
                    <i class="fal fa-warehouse"></i>
                    <span class="nav-link-text" data-i18n="nav.warehouse">Warehouse</span>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('superadmin|admin')
            <li class="nav-title">ACL & Settings</li>
            <li class="">
                <a href="#" title="ACL & Settings" data-filter-tags="acl settings">
                    <i class="fal fa-cog"></i>
                    <span class="nav-link-text" data-i18n="nav.acl_settings">Access Control List</span>
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