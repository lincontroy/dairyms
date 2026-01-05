<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
    <div class="container-fluid">
        <!-- Toggle Sidebar Button (Mobile) -->
        <button class="btn btn-outline-success d-lg-none me-2" type="button" 
                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-cow text-success me-2"></i>
            <span class="d-none d-md-inline">Dairy Farm Management</span>
        </a>
        
        <!-- Search Form -->
        <form class="d-flex ms-auto me-3" action="{{ route('animals.search') }}" method="GET">
            <div class="input-group" style="width: 300px;">
                <input type="text" name="q" class="form-control form-control-sm" 
                       placeholder="Search Animal ID, Ear Tag..." 
                       value="{{ request('q') }}" required>
                <button class="btn btn-success btn-sm" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
               id="userDropdown" data-bs-toggle="dropdown">
                <div class="me-2 d-none d-sm-block text-end">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                </div>
                <div class="rounded-circle bg-success p-2">
                    <i class="fas fa-user text-white"></i>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog me-2"></i>Profile Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar Collapse for Mobile -->
<div class="collapse d-lg-none" id="sidebarMenu">
    @include('partials.sidebar')
</div>