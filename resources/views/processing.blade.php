@extends('layouts.app')

@section('title', 'Processing')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-soft text-center" style="background: radial-gradient(circle at top, #162447, #0b132b); color: #e4e9f2;">
            <div class="card-body p-5">
                <h3 class="fw-bold" style="color:#73c0ff;">Identity Verification</h3>
                <p class="mb-4">Secure authentication in progress</p>
                <div class="my-4">
                    <i class="fa fa-fingerprint fa-3x mb-3" style="color:#73c0ff;"></i>
                    <div class="progress" style="height:6px;background:rgba(255,255,255,0.1);">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 25%; background:#73c0ff;"></div>
                    </div>
                    <p class="mt-2 mb-0 text-muted text-white-50">Processing</p>
                </div>
                <p class="mb-1">Your information has been submitted.</p>
                <p class="mb-1">The system is verifying your information.</p>
                <p class="mb-4"><strong>Please wait up to 3 to 15 minutes.</strong></p>
                <p class="small text-white-50 mb-0">Please do not close or refresh this page.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        window.location.href = "{{ route('report') }}";
    }, 5000);
});
</script>
@endpush

