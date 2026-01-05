@extends('layouts.app')

@section('title', 'Edit Profile - Dairy Farm Management')
@section('page-title', 'Edit Profile')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Profile Settings</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Update Your Profile
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ Storage::url(auth()->user()->profile_picture) }}" 
                                         alt="Profile Picture" 
                                         class="rounded-circle border border-3 border-success"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle border border-3 border-success d-flex align-items-center justify-content-center"
                                         style="width: 150px; height: 150px; background-color: #f0f0f0;">
                                        <i class="fas fa-user fa-4x text-success"></i>
                                    </div>
                                @endif
                                
                                <label for="profile_picture" class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2"
                                       style="cursor: pointer; transform: translate(25%, 25%);">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" 
                                           id="profile_picture" 
                                           name="profile_picture" 
                                           class="d-none"
                                           accept="image/*">
                                </label>
                            </div>
                            
                            <div class="mt-2">
                                <small class="text-muted">Click the camera icon to change picture</small>
                            </div>
                            
                            @error('profile_picture')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-id-card me-2"></i>
                                Personal Information
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', auth()->user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', auth()->user()->phone) }}"
                                           placeholder="+255 xxx xxx xxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="{{ ucfirst(auth()->user()->role) }}" 
                                           disabled readonly>
                                    <small class="text-muted">Contact administrator to change role</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="2">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <div class="mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-key me-2"></i>
                                Change Password
                            </h6>
                            <p class="text-muted mb-3">
                                Leave password fields blank if you don't want to change your password.
                            </p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" 
                                               name="current_password">
                                        <button class="btn btn-outline-secondary toggle-password" 
                                                type="button" 
                                                data-target="current_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        <button class="btn btn-outline-secondary toggle-password" 
                                                type="button" 
                                                data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <small>Minimum 8 characters</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                        <button class="btn btn-outline-secondary toggle-password" 
                                                type="button" 
                                                data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-bell me-2"></i>
                                Notification Preferences
                            </h6>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="email_notifications" 
                                       name="email_notifications" 
                                       value="1"
                                       {{ old('email_notifications', auth()->user()->email_notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Email Notifications
                                </label>
                                <small class="text-muted d-block">Receive email alerts for important events</small>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="sms_notifications" 
                                       name="sms_notifications" 
                                       value="1"
                                       {{ old('sms_notifications', auth()->user()->sms_notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_notifications">
                                    SMS Notifications
                                </label>
                                <small class="text-muted d-block">Receive SMS alerts (requires phone number)</small>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="health_alerts" 
                                       name="health_alerts" 
                                       value="1"
                                       {{ old('health_alerts', auth()->user()->health_alerts) ? 'checked' : '' }}>
                                <label class="form-check-label" for="health_alerts">
                                    Health Alerts
                                </label>
                                <small class="text-muted d-block">Get notified about animal health issues</small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .toggle-password {
        border-left: none;
    }
    
    .toggle-password:hover {
        background-color: #f8f9fa;
    }
    
    .form-check-input:checked {
        background-color: var(--farm-green);
        border-color: var(--farm-green);
    }
    
    .form-check-input:focus {
        border-color: var(--farm-green-light);
        box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
    
    input:disabled, textarea:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    
    /* Profile picture upload hover effect */
    label[for="profile_picture"]:hover {
        background-color: var(--farm-green-light) !important;
        transform: translate(25%, 25%) scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview profile picture before upload
        const profilePicInput = document.getElementById('profile_picture');
        const profilePicImg = document.querySelector('img[alt="Profile Picture"]');
        const profilePicPlaceholder = document.querySelector('.fa-user.fa-4x').parentElement;
        
        profilePicInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (profilePicImg) {
                        profilePicImg.src = e.target.result;
                    } else if (profilePicPlaceholder) {
                        // Replace placeholder with image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Picture';
                        img.className = 'rounded-circle border border-3 border-success';
                        img.style = 'width: 150px; height: 150px; object-fit: cover;';
                        
                        profilePicPlaceholder.parentElement.replaceChild(img, profilePicPlaceholder);
                    }
                }
                
                reader.readAsDataURL(file);
            }
        });
        
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 0) {
                    // Format for Tanzania numbers (adjust as needed)
                    if (value.startsWith('255')) {
                        value = '+' + value;
                    } else if (value.startsWith('0')) {
                        value = '+255' + value.substring(1);
                    }
                }
                
                e.target.value = value;
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password && password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                document.getElementById('password_confirmation').focus();
            }
        });
        
        // Auto-hide success/error messages from previous page
        if (window.location.search.includes('updated')) {
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    });
</script>
@endpush