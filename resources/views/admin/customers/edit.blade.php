@extends('admin.layouts.app')

@section('title', 'Edit Customer - ' . $customer->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Edit Customer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Customer</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $customer->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $customer->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Account Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Account
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Change Password (Optional)</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password">
                            <small class="text-muted">Required if changing password</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation">
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Customer
                        </button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Customer ID:</strong>
                    <div>{{ $customer->id }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Member Since:</strong>
                    <div>{{ $customer->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <div>{{ $customer->updated_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Email Verification:</strong>
                    <div>
                        @if($customer->email_verified_at)
                            <span class="badge bg-success">Verified</span>
                            <div class="text-muted small mt-1">
                                {{ $customer->email_verified_at->format('M d, Y') }}
                            </div>
                        @else
                            <span class="badge bg-warning">Unverified</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Total Orders:</strong>
                    <div>{{ $customer->orders()->count() }}</div>
                </div>
            </div>
        </div>
        
        <!-- Danger Zone -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">Danger Zone</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Once you delete a customer, there is no going back. Please be certain.
                </p>
                <form method="POST" 
                      action="{{ route('admin.customers.destroy', $customer->id) }}" 
                      id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            class="btn btn-outline-danger w-100"
                            onclick="confirmDelete(event, 'deleteForm')">
                        <i class="fas fa-trash me-1"></i> Delete Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection