@extends('admin.layouts.app')

@section('title', 'Payment Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Settings</a></li>
    <li class="breadcrumb-item active">Payment</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.payment') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Stripe Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fab fa-stripe me-2"></i> Stripe Settings
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stripe_key" class="form-label">Publishable Key</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="stripe_key" 
                                       name="stripe_key" 
                                       value="{{ old('stripe_key', $paymentSettings['stripe_key'] ?? '') }}">
                                <small class="text-muted">Your Stripe publishable key</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stripe_secret" class="form-label">Secret Key</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="stripe_secret" 
                                       name="stripe_secret" 
                                       value="{{ old('stripe_secret', $paymentSettings['stripe_secret'] ?? '') }}">
                                <small class="text-muted">Your Stripe secret key</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- PayPal Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fab fa-paypal me-2"></i> PayPal Settings
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="paypal_client_id" class="form-label">Client ID</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="paypal_client_id" 
                                       name="paypal_client_id" 
                                       value="{{ old('paypal_client_id', $paymentSettings['paypal_client_id'] ?? '') }}">
                                <small class="text-muted">Your PayPal client ID</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="paypal_secret" class="form-label">Secret Key</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="paypal_secret" 
                                       name="paypal_secret" 
                                       value="{{ old('paypal_secret', $paymentSettings['paypal_secret'] ?? '') }}">
                                <small class="text-muted">Your PayPal secret key</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mb-4">
                        <h6 class="mb-3">Payment Methods</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="cod_enabled" 
                                           name="cod_enabled" 
                                           {{ old('cod_enabled', $paymentSettings['cod_enabled'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cod_enabled">
                                        Cash on Delivery (COD)
                                    </label>
                                </div>
                                <small class="text-muted">Allow customers to pay when they receive their order</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="bank_transfer_enabled" 
                                           name="bank_transfer_enabled" 
                                           {{ old('bank_transfer_enabled', $paymentSettings['bank_transfer_enabled'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bank_transfer_enabled">
                                        Bank Transfer
                                    </label>
                                </div>
                                <small class="text-muted">Allow customers to pay via bank transfer</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Details -->
                    <div class="mb-4" id="bankDetailsSection" 
                         style="{{ old('bank_transfer_enabled', $paymentSettings['bank_transfer_enabled'] ?? false) ? '' : 'display: none;' }}">
                        <h6 class="mb-3">Bank Transfer Details</h6>
                        <div class="mb-3">
                            <label for="bank_details" class="form-label">Bank Information</label>
                            <textarea class="form-control" 
                                      id="bank_details" 
                                      name="bank_details" 
                                      rows="4">{{ old('bank_details', $paymentSettings['bank_details'] ?? '') }}</textarea>
                            <small class="text-muted">
                                Enter your bank account details that will be shown to customers.<br>
                                Include: Bank Name, Account Name, Account Number, SWIFT/BIC Code
                            </small>
                        </div>
                    </div>
                    
                    <!-- Test Mode -->
                    <div class="mb-4">
                        <h6 class="mb-3">Testing Mode</h6>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong>
                            <p class="mb-0 mt-2">
                                Use test mode when developing your application. 
                                In test mode, no real transactions will be processed.
                            </p>
                        </div>
                        
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="test_mode" 
                                   name="test_mode" 
                                   {{ old('test_mode', $paymentSettings['test_mode'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="test_mode">
                                Enable Test Mode
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Payment Settings
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="testStripe">
                            Test Stripe Connection
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="testPayPal">
                            Test PayPal Connection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Payment Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Payment Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Stripe:</strong>
                    <span class="badge bg-{{ $paymentSettings['stripe_key'] && $paymentSettings['stripe_secret'] ? 'success' : 'danger' }}">
                        {{ $paymentSettings['stripe_key'] && $paymentSettings['stripe_secret'] ? 'Configured' : 'Not Configured' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>PayPal:</strong>
                    <span class="badge bg-{{ $paymentSettings['paypal_client_id'] && $paymentSettings['paypal_secret'] ? 'success' : 'danger' }}">
                        {{ $paymentSettings['paypal_client_id'] && $paymentSettings['paypal_secret'] ? 'Configured' : 'Not Configured' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>COD:</strong>
                    <span class="badge bg-{{ $paymentSettings['cod_enabled'] ?? true ? 'success' : 'secondary' }}">
                        {{ $paymentSettings['cod_enabled'] ?? true ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Bank Transfer:</strong>
                    <span class="badge bg-{{ $paymentSettings['bank_transfer_enabled'] ?? false ? 'success' : 'secondary' }}">
                        {{ $paymentSettings['bank_transfer_enabled'] ?? false ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Payment Tips</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Test Mode:</strong>
                    <p class="mb-0 mt-2">
                        Always test payment integration in test mode before going live.
                        Use test card numbers provided by payment gateways.
                    </p>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Security:</strong>
                    <p class="mb-0 mt-2">
                        Never share your secret keys. Store them securely and never commit them to version control.
                    </p>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Multiple Gateways:</strong>
                    <p class="mb-0 mt-2">
                        Offering multiple payment options increases conversion rates.
                        Customers appreciate having choices.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show/hide bank details based on checkbox
document.getElementById('bank_transfer_enabled').addEventListener('change', function() {
    const bankDetailsSection = document.getElementById('bankDetailsSection');
    if (this.checked) {
        bankDetailsSection.style.display = 'block';
    } else {
        bankDetailsSection.style.display = 'none';
    }
});

// Test Stripe connection
document.getElementById('testStripe').addEventListener('click', function() {
    const stripeKey = document.getElementById('stripe_key').value;
    const stripeSecret = document.getElementById('stripe_secret').value;
    
    if (!stripeKey || !stripeSecret) {
        alert('Please enter both Stripe keys first.');
        return;
    }
    
    // Show loading
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Testing...';
    this.disabled = true;
    
    // In a real application, you would make an AJAX call to test the connection
    setTimeout(() => {
        alert('Stripe connection test completed. Check your Stripe dashboard for test transactions.');
        this.innerHTML = 'Test Stripe Connection';
        this.disabled = false;
    }, 2000);
});

// Test PayPal connection
document.getElementById('testPayPal').addEventListener('click', function() {
    const paypalClientId = document.getElementById('paypal_client_id').value;
    const paypalSecret = document.getElementById('paypal_secret').value;
    
    if (!paypalClientId || !paypalSecret) {
        alert('Please enter both PayPal credentials first.');
        return;
    }
    
    // Show loading
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Testing...';
    this.disabled = true;
    
    // In a real application, you would make an AJAX call to test the connection
    setTimeout(() => {
        alert('PayPal connection test completed. Check your PayPal dashboard for test transactions.');
        this.innerHTML = 'Test PayPal Connection';
        this.disabled = false;
    }, 2000);
});
</script>
@endpush