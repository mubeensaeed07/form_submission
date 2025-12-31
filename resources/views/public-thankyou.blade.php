<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('xhtml/assets/images/favicon.avif') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/icons/fontawesome/css/all.min.css') }}">
    <style>
        body {
            background: #0b132b;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .thank-you-card {
            background: radial-gradient(circle at top, #162447, #0b132b);
            color: #e4e9f2;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .timer-display {
            font-size: 3rem;
            font-weight: bold;
            color: #73c0ff;
            margin: 1rem 0;
        }
        .progress-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 30px;
            overflow: hidden;
            margin: 2rem 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #73c0ff, #4a90e2);
            border-radius: 10px;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .status-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
        }
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #73c0ff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card thank-you-card text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fa fa-check-circle" style="font-size: 4rem; color: #4ade80;"></i>
                    </div>
                    <h3 class="fw-bold" style="color:#73c0ff;">Thank You!</h3>
                    <p class="mb-4">Your application has been submitted successfully.</p>
                    
                    <div class="status-message">
                        <p class="mb-2"><strong>Waiting for approval...</strong></p>
                        <p class="text-white-50 small mb-0">Please wait while we review your application. You will be redirected automatically once approved.</p>
                    </div>

                    <!-- Timer Display -->
                    <div class="timer-display" id="timerDisplay">15:00</div>
                    
                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar" id="progressBar" style="width: 0%;">
                            <span id="progressText">0%</span>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="spinner"></div>
                    <p class="text-white-50 small mt-3" id="statusText">Checking approval status...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const submissionId = {{ $submissionId ?? 'null' }};
    
    // Check if user navigated back to this page
    const navigationType = performance.getEntriesByType('navigation')[0]?.type;
    const isBackNavigation = navigationType === 'back_forward' || 
                             (performance.navigation && performance.navigation.type === 2);
    
    // Check if already approved and redirected (stored in sessionStorage)
    // This MUST be checked FIRST before any timers or polling start
    const approvalData = sessionStorage.getItem('thankyou_approval_' + submissionId);
    let hasRedirected = false;
    let approvalTime = null;
    let approvedUrl = null;
    
    if (approvalData) {
        try {
            const data = JSON.parse(approvalData);
            hasRedirected = data.redirected === true;
            approvalTime = data.approvalTime !== null && data.approvalTime !== undefined ? parseInt(data.approvalTime) : null;
            approvedUrl = data.approvedUrl || null;
        } catch (e) {
            console.error('Error parsing approval data:', e);
        }
    }
    
    // If already approved, show approved state immediately and exit
    // This prevents timers/polling from starting on back navigation
    if (hasRedirected && approvalTime !== null) {
        const totalMinutes = 15;
        const totalSeconds = totalMinutes * 60;
        const elapsedWhenApproved = totalSeconds - approvalTime;
        const progressPercent = (elapsedWhenApproved / totalSeconds) * 100;
        
        // Format time as MM:SS
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        // Update UI immediately
        document.getElementById('timerDisplay').textContent = formatTime(approvalTime);
        document.getElementById('progressBar').style.width = progressPercent + '%';
        document.getElementById('progressBar').querySelector('span').textContent = Math.round(progressPercent) + '%';
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('statusText').textContent = 'Your application has been approved.';
        document.querySelector('.status-message').innerHTML = '<p class="mb-2"><strong>Application Approved</strong></p><p class="text-white-50 small mb-0">Your application has been successfully approved.</p>';
        
        // Replace history entry
        if (window.history && window.history.replaceState) {
            window.history.replaceState({ page: 'thankyou', submissionId: submissionId }, 'Thank You', currentUrl);
        }
        
        // Prevent going back to form
        window.addEventListener('popstate', function(event) {
            if (!event.state || (event.state.page !== 'thankyou' && event.state.page !== 'form-submitted')) {
                if (window.location.pathname.includes('/thank-you')) {
                    if (window.history && window.history.pushState) {
                        window.history.pushState({ page: 'thankyou', submissionId: submissionId }, 'Thank You', currentUrl);
                    }
                }
            }
        });
        
        // Exit early - don't start any timers or polling
        return;
    }
    
    // Replace current history entry with Thank You page
    // This ensures form is never in history
    if (window.history && window.history.replaceState) {
        window.history.replaceState({ page: 'thankyou', submissionId: submissionId }, 'Thank You', currentUrl);
    }
    
    // Make Thank You page prevent going back to form, but allow forward navigation
    // Only prevent going back if trying to go beyond Thank You page (to form)
    let isHandlingPopstate = false;
    window.addEventListener('popstate', function(event) {
        if (isHandlingPopstate) return;
        isHandlingPopstate = true;
        
        // Check if we're trying to go back to form or before Thank You page
        // If the previous state is not Thank You page, prevent it
        if (!event.state || (event.state.page !== 'thankyou' && event.state.page !== 'form-submitted')) {
            // Push Thank You page back to history to prevent going back to form
            // But only if we're actually on Thank You page
            if (window.location.pathname.includes('/thank-you')) {
                if (window.history && window.history.pushState) {
                    window.history.pushState({ page: 'thankyou', submissionId: submissionId }, 'Thank You', currentUrl);
                }
            }
        }
        
        setTimeout(() => {
            isHandlingPopstate = false;
        }, 100);
    });
    
    if (!submissionId) {
        document.getElementById('statusText').textContent = 'Submission ID not found. Please contact support.';
        return;
    }

    // Timer settings
    const totalMinutes = 15;
    const totalSeconds = totalMinutes * 60;
    let remainingSeconds = totalSeconds;
    let progressPercent = 0;
    let pollingInterval = null;
    let timerInterval = null;
    let isApproved = false;
    let shouldStopPolling = false;

    // Timer display element
    const timerDisplay = document.getElementById('timerDisplay');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const statusText = document.getElementById('statusText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    // Format time as MM:SS
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    

    // Update timer
    function updateTimer() {
        if (isApproved) return;
        
        remainingSeconds--;
        const elapsedSeconds = totalSeconds - remainingSeconds;
        progressPercent = (elapsedSeconds / totalSeconds) * 100;
        
        timerDisplay.textContent = formatTime(remainingSeconds);
        progressBar.style.width = progressPercent + '%';
        progressText.textContent = Math.round(progressPercent) + '%';
        
        if (remainingSeconds <= 0) {
            clearInterval(timerInterval);
            statusText.textContent = 'Time limit reached. Please contact support if you have not been redirected.';
            loadingSpinner.style.display = 'none';
        }
    }

    // Start timer
    timerInterval = setInterval(updateTimer, 1000);

    // Check approval status via AJAX
    function checkApprovalStatus() {
        if (isApproved || shouldStopPolling) return;
        
        fetch(`/api/submission/${submissionId}/status`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.approved && data.approved_url) {
                // Approved! Stop everything and redirect (first time only)
                isApproved = true;
                shouldStopPolling = true;
                clearInterval(timerInterval);
                clearInterval(pollingInterval);
                
                // Store approval data: time when approved, URL, and redirect status
                const approvalData = {
                    redirected: true,
                    approvalTime: remainingSeconds, // Store remaining seconds when approved
                    approvedUrl: data.approved_url
                };
                sessionStorage.setItem('thankyou_approval_' + submissionId, JSON.stringify(approvalData));
                
                loadingSpinner.style.display = 'none';
                statusText.textContent = 'Approved! Redirecting...';
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                
                // Navigate to external URL - this will naturally add it to history
                // History will be: Previous Page → Thank You → External URL
                // When user clicks back from external URL, they'll come to Thank You page (not form)
                setTimeout(() => {
                    window.location.href = data.approved_url;
                }, 1000);
            } else if (data.status === 'incorrect') {
                // Marked as incorrect
                isApproved = true;
                shouldStopPolling = true;
                clearInterval(timerInterval);
                clearInterval(pollingInterval);
                
                loadingSpinner.style.display = 'none';
                statusText.textContent = 'Wrong details. Please visit the form again and submit again.';
                document.querySelector('.status-message').innerHTML = '<p class="text-danger"><strong>Application Status: Incorrect Details</strong></p><p class="text-white-50 small">Wrong details. Please visit the form again and submit again.</p>';
                progressBar.style.background = 'linear-gradient(90deg, #ef4444, #dc2626)';
            }
        })
        .catch(error => {
            console.error('Error checking approval status:', error);
            // Continue polling even on error (unless we should stop)
            if (shouldStopPolling) {
                clearInterval(pollingInterval);
            }
        });
    }

    // If user came from back navigation and already redirected, don't start polling
    if (isBackNavigation && hasRedirected) {
        shouldStopPolling = true;
        loadingSpinner.style.display = 'none';
        statusText.textContent = 'Your application has been approved.';
        document.querySelector('.status-message').innerHTML = '<p class="mb-2"><strong>Application Approved</strong></p><p class="text-white-50 small mb-0">Your application has been successfully approved.</p>';
        progressBar.style.width = '100%';
        progressText.textContent = '100%';
        timerDisplay.textContent = '00:00';
    } else {
        // Start polling every 1.5 seconds
        pollingInterval = setInterval(checkApprovalStatus, 1500);
        
        // Initial check
        checkApprovalStatus();
    }
});
</script>
</body>
</html>
