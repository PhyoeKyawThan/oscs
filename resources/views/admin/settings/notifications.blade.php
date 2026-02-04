@extends('admin.layouts.app')

@section('title', 'Notification Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Settings</a></li>
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Notification Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.notifications') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Email Notifications -->
                    <div class="mb-4">
                        <h6 class="mb-3">Email Notifications</h6>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="order_confirmation" 
                                       name="order_confirmation" 
                                       {{ old('order_confirmation', $notificationSettings['order_confirmation'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="order_confirmation">
                                    Order Confirmation Emails
                                </label>
                            </div>
                            <small class="text-muted">Send email to customer when order is placed</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="order_status_update" 
                                       name="order_status_update" 
                                       {{ old('order_status_update', $notificationSettings['order_status_update'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="order_status_update">
                                    Order Status Update Emails
                                </label>
                            </div>
                            <small class="text-muted">Send email to customer when order status changes</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="new_customer_registration" 
                                       name="new_customer_registration" 
                                       {{ old('new_customer_registration', $notificationSettings['new_customer_registration'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="new_customer_registration">
                                    New Customer Registration
                                </label>
                            </div>
                            <small class="text-muted">Send welcome email to new customers</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="low_stock_notification" 
                                       name="low_stock_notification" 
                                       {{ old('low_stock_notification', $notificationSettings['low_stock_notification'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="low_stock_notification">
                                    Low Stock Notifications
                                </label>
                            </div>
                            <small class="text-muted">Send email to admin when product stock is low</small>
                        </div>
                    </div>
                    
                    <!-- Email Addresses -->
                    <div class="mb-4">
                        <h6 class="mb-3">Email Addresses</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="admin_email" 
                                       name="admin_email" 
                                       value="{{ old('admin_email', $notificationSettings['admin_email'] ?? '') }}">
                                <small class="text-muted">Email address for admin notifications</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="notification_email" class="form-label">Notification Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="notification_email" 
                                       name="notification_email" 
                                       value="{{ old('notification_email', $notificationSettings['notification_email'] ?? '') }}">
                                <small class="text-muted">Email address for system notifications</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SMS Notifications -->
                    <div class="mb-4">
                        <h6 class="mb-3">SMS Notifications</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            SMS notifications require integration with an SMS service provider.
                            Contact your administrator to enable this feature.
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="sms_enabled" 
                                       name="sms_enabled" 
                                       disabled 
                                       {{ old('sms_enabled', $notificationSettings['sms_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_enabled">
                                    Enable SMS Notifications
                                </label>
                            </div>
                            <small class="text-muted">Send SMS for order updates (requires setup)</small>
                        </div>
                    </div>
                    
                    <!-- Test Notifications -->
                    <div class="mb-4">
                        <h6 class="mb-3">Test Notifications</h6>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Test emails will be sent to the admin email address above.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" id="testOrderEmail">
                                Test Order Email
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="testStatusEmail">
                                Test Status Email
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="testLowStockEmail">
                                Test Low Stock Email
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Notification Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Notification Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Notification Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Order Confirmations:</strong>
                    <span class="badge bg-{{ $notificationSettings['order_confirmation'] ?? true ? 'success' : 'secondary' }}">
                        {{ $notificationSettings['order_confirmation'] ?? true ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Status Updates:</strong>
                    <span class="badge bg-{{ $notificationSettings['order_status_update'] ?? true ? 'success' : 'secondary' }}">
                        {{ $notificationSettings['order_status_update'] ?? true ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>New Customers:</strong>
                    <span class="badge bg-{{ $notificationSettings['new_customer_registration'] ?? true ? 'success' : 'secondary' }}">
                        {{ $notificationSettings['new_customer_registration'] ?? true ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Low Stock:</strong>
                    <span class="badge bg-{{ $notificationSettings['low_stock_notification'] ?? true ? 'success' : 'secondary' }}">
                        {{ $notificationSettings['low_stock_notification'] ?? true ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Admin Email:</strong>
                    <div>
                        @if($notificationSettings['admin_email'] ?? '')
                            {{ $notificationSettings['admin_email'] }}
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>SMTP Status:</strong>
                    <span class="badge bg-{{ config('mail.mailers.smtp.host') ? 'success' : 'danger' }}">
                        {{ config('mail.mailers.smtp.host') ? 'Configured' : 'Not Configured' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Notification Tips</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Email Templates:</strong>
                    <p class="mb-0 mt-2">
                        Customize email templates to match your brand. 
                        Professional emails build customer trust.
                    </p>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Frequency:</strong>
                    <p class="mb-0 mt-2">
                        Avoid overwhelming customers with too many notifications.
                        Be selective about what warrants an email.
                    </p>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Testing:</strong>
                    <p class="mb-0 mt-2">
                        Always test notifications before going live.
                        Check spam folders and mobile formatting.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Test notification buttons
document.getElementById('testOrderEmail').addEventListener('click', function() {
    const adminEmail = document.getElementById('admin_email').value;
    if (!adminEmail) {
        alert('Please enter an admin email address first.');
        return;
    }
    
    // Show loading
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
    this.disabled = true;
    
    // In a real application, you would make an AJAX call
    setTimeout(() => {
        alert('Test order confirmation email sent to ' + adminEmail);
        this.innerHTML = 'Test Order Email';
        this.disabled = false;
    }, 2000);
});

document.getElementById('testStatusEmail').addEventListener('click', function() {
    const adminEmail = document.getElementById('admin_email').value;
    if (!adminEmail) {
        alert('Please enter an admin email address first.');
        return;
    }
    
    // Show loading
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
    this.disabled = true;
    
    setTimeout(() => {
        alert('Test status update email sent to ' + adminEmail);
        this.innerHTML = 'Test Status Email';
        this.disabled = false;
    }, 2000);
});

document.getElementById('testLowStockEmail').addEventListener('click', function() {
    const adminEmail = document.getElementById('admin_email').value;
    if (!adminEmail) {
        alert('Please enter an admin email address first.');
        return;
    }
    
    // Show loading
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
    this.disabled = true;
    
    setTimeout(() => {
        alert('Test low stock email sent to ' + adminEmail);
        this.innerHTML = 'Test Low Stock Email';
        this.disabled = false;
    }, 2000);
});
</script>
@endpush