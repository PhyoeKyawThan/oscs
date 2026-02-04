@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if(auth('admin')->user()->avatar)
                    <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                         alt="{{ auth('admin')->user()->name }}" 
                         class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-4x text-primary"></i>
                    </div>
                @endif
                <h5 class="my-3">{{ auth('admin')->user()->name }}</h5>
                <p class="text-muted mb-1">{{ auth('admin')->user()->email }}</p>
                <p class="text-muted mb-4">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</p>
                <div class="d-flex justify-content-center mb-2">
                    <span class="badge bg-primary">{{ auth('admin')->user()->role }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="form-control" name="name" value="{{ old('name', auth('admin')->user()->name) }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="email" class="form-control" name="email" value="{{ old('email', auth('admin')->user()->email) }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Profile Picture</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="file" class="form-control" name="avatar" accept="image/*">
                            <small class="text-muted">Max file size: 2MB. Allowed: JPG, PNG, GIF</small>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Change Password</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Current Password</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="password" class="form-control" name="current_password">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">New Password</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="password" class="form-control" name="new_password">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Confirm Password</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="password" class="form-control" name="new_password_confirmation">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-secondary">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview avatar before upload
    document.querySelector('input[name="avatar"]').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.querySelector('.card-body img');
                if (img) {
                    img.src = e.target.result;
                } else {
                    const iconDiv = document.querySelector('.card-body .bg-primary');
                    if (iconDiv) {
                        iconDiv.innerHTML = `<img src="${e.target.result}" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">`;
                    }
                }
            }
            
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush