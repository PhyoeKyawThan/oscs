<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0 text-muted">
                    v1.0.0 | {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>
</footer>