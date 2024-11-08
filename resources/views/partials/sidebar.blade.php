<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/brand-logos/desktop-dark.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/brand-logos/toggle-dark.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="index.html" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('images/brand-logos/desktop-logo.png') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/brand-logos/toggle-logo.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="pages-profile.html">
                <img src="{{ Auth::user()->picture }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2">{{ auth()->user()->name }}</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a href="index.html" class="side-nav-link">
                    <i class="uil-chart-pie-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMasterData" aria-expanded="false" aria-controls="sidebarMasterData" class="side-nav-link">
                    <i class="uil-briefcase"></i>
                    <span> Master Data </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMasterData">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="/users">Employee</a>
                        </li>
                        <li>
                            <a href="/roles">Department</a>
                        </li>
                        <li>
                            <a href="/permissions">Template</a>
                        </li>
                        <li>
                            <a href="/permissions">Task</a>
                        </li>
                        <li>
                            <a href="/temp">Template</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="/projects" class="side-nav-link">
                    <i class="uil-briefcase"></i>
                    <span> Projects </span>
                </a>
            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUserManagement" aria-expanded="false" aria-controls="sidebarUserManagement" class="side-nav-link">
                    <i class="uil-users-alt"></i>
                    <span> User Management </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUserManagement">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="/users">User</a>
                        </li>
                        <li>
                            <a href="/roles">Role</a>
                        </li>
                        <li>
                            <a href="/permissions">Permission</a>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Help Box -->
            <div class="help-box text-white text-center">
                <a href="javascript: void(0);" class="float-end close-btn text-white">
                    {{-- <i class="mdi mdi-close"></i> --}}
                </a>
                <img src="hyper/src/assets/images/svg/help-icon.svg" height="90" alt="Helper Icon Image" />
                <h5 class="mt-3">User Detail</h5>
                <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>
            </div>
            <!-- end Help Box -->


        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->