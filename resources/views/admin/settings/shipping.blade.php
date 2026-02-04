@extends('admin.layouts.app')

@section('title', 'Shipping Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Settings</a></li>
    <li class="breadcrumb-item active">Shipping</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Shipping Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.shipping') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Shipping Costs -->
                    <div class="mb-4">
                        <h6 class="mb-3">Shipping Costs</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="free_shipping_threshold" class="form-label">Free Shipping Threshold</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           class="form-control" 
                                           id="free_shipping_threshold" 
                                           name="free_shipping_threshold" 
                                           value="{{ old('free_shipping_threshold', $shippingSettings['free_shipping_threshold'] ?? 100) }}">
                                </div>
                                <small class="text-muted">Order total required for free shipping (0 = no free shipping)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="standard_shipping_cost" class="form-label">Standard Shipping Cost *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           class="form-control" 
                                           id="standard_shipping_cost" 
                                           name="standard_shipping_cost" 
                                           value="{{ old('standard_shipping_cost', $shippingSettings['standard_shipping_cost'] ?? 5.99) }}" 
                                           required>
                                </div>
                                <small class="text-muted">Cost for standard shipping (5-7 business days)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="express_shipping_cost" class="form-label">Express Shipping Cost *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           class="form-control" 
                                           id="express_shipping_cost" 
                                           name="express_shipping_cost" 
                                           value="{{ old('express_shipping_cost', $shippingSettings['express_shipping_cost'] ?? 12.99) }}" 
                                           required>
                                </div>
                                <small class="text-muted">Cost for express shipping (1-2 business days)</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Units -->
                    <div class="mb-4">
                        <h6 class="mb-3">Measurement Units</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weight_unit" class="form-label">Weight Unit *</label>
                                <select class="form-select" id="weight_unit" name="weight_unit" required>
                                    <option value="kg" {{ old('weight_unit', $shippingSettings['weight_unit'] ?? 'kg') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                    <option value="lb" {{ old('weight_unit', $shippingSettings['weight_unit'] ?? 'kg') == 'lb' ? 'selected' : '' }}>Pounds (lb)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="dimension_unit" class="form-label">Dimension Unit *</label>
                                <select class="form-select" id="dimension_unit" name="dimension_unit" required>
                                    <option value="cm" {{ old('dimension_unit', $shippingSettings['dimension_unit'] ?? 'cm') == 'cm' ? 'selected' : '' }}>Centimeters (cm)</option>
                                    <option value="in" {{ old('dimension_unit', $shippingSettings['dimension_unit'] ?? 'cm') == 'in' ? 'selected' : '' }}>Inches (in)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Zones -->
                    <div class="mb-4">
                        <h6 class="mb-3">Shipping Zones</h6>
                        <div id="shippingZones">
                            @php
                                $zones = old('shipping_zones', $shippingSettings['shipping_zones'] ?? []);
                                if (empty($zones)) {
                                    $zones = [['name' => 'Domestic', 'countries' => ['US'], 'cost' => 5.99]];
                                }
                            @endphp
                            
                            @foreach($zones as $index => $zone)
                            <div class="card border mb-3 zone-item" data-index="{{ $index }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Zone Name</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="shipping_zones[{{ $index }}][name]" 
                                                   value="{{ $zone['name'] ?? '' }}" 
                                                   required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Shipping Cost</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       step="0.01" 
                                                       min="0" 
                                                       class="form-control" 
                                                       name="shipping_zones[{{ $index }}][cost]" 
                                                       value="{{ $zone['cost'] ?? 0 }}" 
                                                       required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Countries</label>
                                            <select class="form-select select2" 
                                                    name="shipping_zones[{{ $index }}][countries][]" 
                                                    multiple 
                                                    style="width: 100%;">
                                                @foreach(['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'JP', 'CN'] as $country)
                                                    <option value="{{ $country }}" 
                                                            {{ in_array($country, $zone['countries'] ?? []) ? 'selected' : '' }}>
                                                        {{ $country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Hold Ctrl/Cmd to select multiple countries</small>
                                        </div>
                                        
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-zone">
                                                <i class="fas fa-trash"></i> Remove Zone
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addZone">
                            <i class="fas fa-plus me-1"></i> Add Shipping Zone
                        </button>
                    </div>
                    
                    <!-- Processing Time -->
                    <div class="mb-4">
                        <h6 class="mb-3">Order Processing</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="processing_time" class="form-label">Processing Time (Days)</label>
                                <input type="number" 
                                       min="0" 
                                       max="30" 
                                       class="form-control" 
                                       id="processing_time" 
                                       name="processing_time" 
                                       value="{{ old('processing_time', $shippingSettings['processing_time'] ?? 1) }}">
                                <small class="text-muted">Days to process order before shipping (0 = same day)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="delivery_time" class="form-label">Delivery Time (Days)</label>
                                <input type="number" 
                                       min="1" 
                                       max="60" 
                                       class="form-control" 
                                       id="delivery_time" 
                                       name="delivery_time" 
                                       value="{{ old('delivery_time', $shippingSettings['delivery_time'] ?? 5) }}">
                                <small class="text-muted">Estimated delivery time for standard shipping</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Shipping Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Shipping Preview</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Free Shipping:</strong>
                    <div>
                        @php
                            $threshold = old('free_shipping_threshold', $shippingSettings['free_shipping_threshold'] ?? 100);
                        @endphp
                        @if($threshold > 0)
                            Orders over ${{ number_format($threshold, 2) }}
                        @else
                            No free shipping
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Standard Shipping:</strong>
                    <div>$<span id="previewStandardCost">{{ old('standard_shipping_cost', $shippingSettings['standard_shipping_cost'] ?? 5.99) }}</span></div>
                </div>
                
                <div class="mb-3">
                    <strong>Express Shipping:</strong>
                    <div>$<span id="previewExpressCost">{{ old('express_shipping_cost', $shippingSettings['express_shipping_cost'] ?? 12.99) }}</span></div>
                </div>
                
                <div class="mb-3">
                    <strong>Processing Time:</strong>
                    <div>
                        @php
                            $processingTime = old('processing_time', $shippingSettings['processing_time'] ?? 1);
                        @endphp
                        {{ $processingTime }} {{ $processingTime == 1 ? 'day' : 'days' }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Delivery Time:</strong>
                    <div>
                        @php
                            $deliveryTime = old('delivery_time', $shippingSettings['delivery_time'] ?? 5);
                        @endphp
                        {{ $deliveryTime }} {{ $deliveryTime == 1 ? 'day' : 'days' }}
                    </div>
                </div>
                
                <div class="border-top pt-3">
                    <h6>Example Order:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Total:</span>
                        <span>$75.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Standard Shipping:</span>
                        <span id="previewShippingExample">$5.99</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="previewTotalExample">$80.99</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Shipping Tips</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Free Shipping Threshold:</strong>
                    <p class="mb-0 mt-2">
                        Offering free shipping over a certain amount can increase average order value.
                    </p>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>International Shipping:</strong>
                    <p class="mb-0 mt-2">
                        Consider customs, taxes, and regulations when shipping internationally.
                        Be clear about who pays import duties.
                    </p>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Clear Communication:</strong>
                    <p class="mb-0 mt-2">
                        Always provide estimated delivery times. Customers appreciate transparency about shipping.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select countries',
        allowClear: true
    });
});

// Update previews
document.getElementById('standard_shipping_cost').addEventListener('input', updateShippingPreview);
document.getElementById('express_shipping_cost').addEventListener('input', updateShippingPreview);

function updateShippingPreview() {
    const standardCost = parseFloat(document.getElementById('standard_shipping_cost').value) || 0;
    const expressCost = parseFloat(document.getElementById('express_shipping_cost').value) || 0;
    
    document.getElementById('previewStandardCost').textContent = standardCost.toFixed(2);
    document.getElementById('previewExpressCost').textContent = expressCost.toFixed(2);
    document.getElementById('previewShippingExample').textContent = '$' + standardCost.toFixed(2);
    document.getElementById('previewTotalExample').textContent = '$' + (75 + standardCost).toFixed(2);
}

// Shipping Zones Management
let zoneIndex = {{ count($zones) }};
document.getElementById('addZone').addEventListener('click', function() {
    const zonesContainer = document.getElementById('shippingZones');
    const newZone = `
        <div class="card border mb-3 zone-item" data-index="${zoneIndex}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Zone Name</label>
                        <input type="text" class="form-control" name="shipping_zones[${zoneIndex}][name]" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shipping Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" name="shipping_zones[${zoneIndex}][cost]" required>
                        </div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label class="form-label">Countries</label>
                        <select class="form-select select2" name="shipping_zones[${zoneIndex}][countries][]" multiple style="width: 100%;">
                            <option value="US">US</option>
                            <option value="CA">CA</option>
                            <option value="GB">GB</option>
                            <option value="AU">AU</option>
                            <option value="DE">DE</option>
                            <option value="FR">FR</option>
                            <option value="JP">JP</option>
                            <option value="CN">CN</option>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple countries</small>
                    </div>
                    
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-zone">
                            <i class="fas fa-trash"></i> Remove Zone
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    zonesContainer.insertAdjacentHTML('beforeend', newZone);
    
    // Initialize Select2 for the new select
    $(zonesContainer.querySelector(`.zone-item[data-index="${zoneIndex}"] .select2`)).select2({
        theme: 'bootstrap-5',
        placeholder: 'Select countries',
        allowClear: true
    });
    
    zoneIndex++;
});

// Remove zone
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-zone') || e.target.closest('.remove-zone')) {
        const zoneItem = e.target.closest('.zone-item');
        if (zoneItem && document.querySelectorAll('.zone-item').length > 1) {
            zoneItem.remove();
        } else {
            alert('You must have at least one shipping zone.');
        }
    }
});

// Initialize preview on load
updateShippingPreview();
</script>
@endpush