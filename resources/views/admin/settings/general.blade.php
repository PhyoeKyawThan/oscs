@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Settings</h3>
                </div>
                <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_name">Site Name</label>
                                    <input type="text" name="site_name" id="site_name" 
                                           class="form-control @error('site_name') is-invalid @enderror"
                                           value="{{ old('site_name', $settings['site_name']) }}" required>
                                    @error('site_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_email">Site Email</label>
                                    <input type="email" name="site_email" id="site_email" 
                                           class="form-control @error('site_email') is-invalid @enderror"
                                           value="{{ old('site_email', $settings['site_email']) }}" required>
                                    @error('site_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <div class="custom-file">
                                        <input type="file" name="logo" id="logo" 
                                               class="custom-file-input @error('logo') is-invalid @enderror"
                                               accept="image/*">
                                        <label class="custom-file-label" for="logo">Choose file</label>
                                        @error('logo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if($settings['logo'])
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings['logo']) }}" 
                                                 alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                            <a href="#" class="text-danger ml-2" onclick="event.preventDefault(); document.getElementById('remove-logo').submit();">
                                                Remove
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Add more fields as needed -->
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
                
                <!-- Remove logo form -->
                @if($settings['logo'])
                    <form id="remove-logo" action="{{ route('admin.settings.logo.remove') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show file name in file input
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("logo").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endpush