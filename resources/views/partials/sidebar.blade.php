<div class="sidebar">
    <div class="sidebar-sticky">
        <!-- Brand Logo -->
        <div class="text-center mb-4">
            <div class="rounded-circle bg-white p-3 d-inline-block mb-2">
                <i class="fas fa-cow fa-2x text-success"></i>
            </div>
            <h5 class="text-white mb-1">Dairy Farm</h5>
            <small class="text-light">Management System</small>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('animals') || request()->is('animals/*') && !request()->is('animals/*/*') ? 'active' : '' }}" 
                   href="{{ route('animals.index') }}">
                    <i class="fas fa-cow"></i> Animal Registry
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('animals/*/breeding*') ? 'active' : '' }}" 
                   href="#">
                    <i class="fas fa-dna"></i> Breeding Records
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('animals/*/milk*') ? 'active' : '' }}" 
                   href="#">
                    <i class="fas fa-wine-bottle"></i> Milk Production
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('animals/*/health*') ? 'active' : '' }}" 
                   href="#">
                    <i class="fas fa-heartbeat"></i> Health Records
                </a>
            </li>
            
            @if(auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" 
                   href="#">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
            @endif
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" 
                   href="#">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" 
                   href="{{ route('profile.edit') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
        
        <!-- Sidebar Footer -->
        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-center text-light">
            <small>© {{ date('Y') }} Dairy Farm<br>v1.0.0</small>
        </div>
    </div>
</div>