<div class="leftside-menu menuitem-active">

    <!-- Brand Logo Light -->
    <a href="/home" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/brand-logos/desktop-dark.png') }}" alt=" darklogo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/brand-logos/toggle-dark.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="/home" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('images/brand-logos/desktop-logo.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/brand-logos/toggle-logo.png') }}" alt="small logo">
        </span> 
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Show Full Sidebar"
        data-bs-original-title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100 show" id="leftside-menu-container" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
                        style="height: 100%; overflow: scroll hidden;">
                        <div class="simplebar-content" style="padding: 0px;">

                            <!--- Sidemenu -->
                            <ul class="side-nav">

                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false"
                                        aria-controls="sidebarDashboards" class="side-nav-link">
                                        <i class="uil-chart-pie-alt"></i>
                                        <span> Dashboards </span>
                                    </a>
                                    <div class="collapse" id="sidebarDashboards">
                                        <ul class="side-nav-second-level">
                                            <li>
                                                <a href="annual-report">Annual Report</a>
                                            </li>
                                            <li>
                                                <a href="monthly-report">Monthly Report</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#sidebarUserManagement" aria-expanded="false"
                                        aria-controls="sidebarUserManagement" class="side-nav-link">
                                        <i class="uil-users-alt"></i>
                                        <span> User Management </span>
                                    </a>
                                    <div class="collapse" id="sidebarUserManagement">
                                        <ul class="side-nav-second-level">
                                            <li>
                                                <a href="users">User</a>
                                            </li>
                                            <li>
                                                <a href="roles">Role</a>
                                            </li>
                                            <li>
                                                <a href="permissions">Permission</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                

                                

                                

                                @if(Auth::User()->role == 'SUPERUSER' or Auth::User()->role == 'ADMINISTRATOR')
                                <li class="side-nav-item">
                                    <a href="users" class="side-nav-link">
                                        <i class="uil-user"></i>
                                        <span> Users </span>
                                    </a>
                                </li>
                                @endif



                            </ul>
                            <!-- End Sidebar -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 1000px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                style="width: 32px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
        </div>
    </div>
</div>
