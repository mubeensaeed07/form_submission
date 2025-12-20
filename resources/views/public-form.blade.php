@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charity Assistance Application</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('xhtml/assets/images/favicon.avif') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">
    <style>
        /* Prevent date picker from opening during navigation */
        body[data-navigating="true"] input[type="date"],
        body[data-step-changing="true"] input[type="date"],
        body[data-navigating="true"] input[type="date"]:focus,
        body[data-step-changing="true"] input[type="date"]:focus,
        body[data-navigating="true"] input[type="date"]:active,
        body[data-step-changing="true"] input[type="date"]:active {
            pointer-events: none !important;
            opacity: 0.7;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Prevent date picker from opening when button is clicked */
        input[type="date"].no-picker,
        input[type="date"].no-picker:focus {
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
        
        /* Ensure buttons are always clickable */
        #nextStep, #prevStep {
            pointer-events: auto !important;
            z-index: 1000;
            position: relative;
        }
        
        /* Additional Bootstrap override to prevent date picker */
        input[type="date"]:disabled,
        input[type="date"]:disabled:focus {
            pointer-events: none !important;
            cursor: not-allowed;
            user-select: none !important;
        }
        
        /* Prevent any interaction with date inputs during navigation */
        body[data-navigating="true"] input[type="date"] *,
        body[data-step-changing="true"] input[type="date"] * {
            pointer-events: none !important;
        }
        
        /* Validation error styling */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        /* Aggressive date input control */
        input[type="date"].date-input-field {
            position: relative;
        }
        
        input[type="date"].date-input-field.date-selected {
            pointer-events: auto !important;
        }
        
        /* Prevent date picker from opening when clicking other fields */
        body:not([data-date-picker-active="true"]) input[type="date"]:not(:focus) {
            pointer-events: auto !important;
        }
        
        /* When another field is focused, make date inputs non-interactive */
        body[data-other-field-focused="true"] input[type="date"] {
            pointer-events: none !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body style="background:#f4f7fb;">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-primary mb-1">Charity Assistance Application</h4>
                        <p class="text-muted mb-0">Complete our simple form to apply for financial assistance</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        @foreach ([1,2,3,4] as $step)
                            <div class="text-center">
                                <div class="step-badge bg-light border text-primary" data-step-indicator="{{ $step }}">{{ $step }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="multiStepForm" method="POST" action="{{ route('public.form.submit') }}">
                        @csrf
                        <input type="hidden" name="referrer_id" value="{{ $referrer?->id }}">
                        <input type="hidden" name="facebook_user_id" value="{{ $facebookUser?->id }}">

                        {{-- Step 1 --}}
                        <div class="step-pane" data-step="1">
                            <h5 class="mb-3">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control date-input-field" data-date-input="true">
                                </div>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="step-pane d-none" data-step="2">
                            <h5 class="mb-3">Family &amp; Financial Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Household Size</label>
                                    <select name="household_size" class="form-control" required>
                                        <option value="">Select number of people</option>
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Number of Dependents</label>
                                    <select name="dependents" class="form-control" required>
                                        <option value="">Number of dependents</option>
                                        @for($i=0; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="mb-0">Family Members Information</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="addFamilyMember">Add Family Member</button>
                                </div>
                                <div id="familyMembers" class="row g-3"></div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Employment Status</label>
                                    <select name="employment_status" class="form-control" required>
                                        <option value="">Select employment status</option>
                                        <option value="Employed">Employed</option>
                                        <option value="Self-Employed">Self-Employed</option>
                                        <option value="Unemployed">Unemployed</option>
                                        <option value="Student">Student</option>
                                        <option value="Retired">Retired</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Employer Name (if applicable)</label>
                                    <input type="text" name="employer_name" class="form-control">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Monthly Household Income</span>
                                    <span id="incomeValue">$0</span>
                                </label>
                                <input type="range" name="monthly_income" min="0" max="5000" step="100" value="0" class="form-range" id="incomeRange">
                                <div class="d-flex justify-content-between text-muted">
                                    <span>$0</span>
                                    <span>$5,000+</span>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="step-pane d-none" data-step="3">
                            <h5 class="mb-3">Assistance Details</h5>
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Assistance Amount Requested</span>
                                    <span id="amountValue">$2,000</span>
                                </label>
                                <input type="range" name="assistance_amount" min="500" max="10000" step="100" value="2000" class="form-range" id="amountRange">
                                <div class="d-flex justify-content-between text-muted">
                                    <span>$500</span>
                                    <span>$10,000</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type of Assistance Needed <span class="text-danger">*</span></label>
                                <div class="row g-2" id="assistanceTypesContainer" data-required="true">
                                    @foreach (['Housing Expenses', 'Utility Bills', 'Education Fees', 'Medical Expenses', 'Food & Essentials', 'Other'] as $type)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input assistance-type-checkbox" type="checkbox" name="assistance_types[]" value="{{ $type }}" id="assist-{{ Str::slug($type) }}">
                                                <label class="form-check-label" for="assist-{{ Str::slug($type) }}">{{ $type }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback d-none" id="assistanceTypesError">Please select at least one type of assistance needed.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Please describe your situation and how this assistance will help</label>
                                <textarea name="assistance_description" rows="4" class="form-control" placeholder="Provide details about your current financial situation and how this assistance would help you and your family..." required></textarea>
                            </div>
                        </div>

                        {{-- Step 4 --}}
                        <div class="step-pane d-none" data-step="4">
                            <h5 class="mb-3">Verification &amp; Agreement</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Social Security Number (SSN)</label>
                                    <input type="text" name="ssn" class="form-control" placeholder="XXX-XX-XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Street Address</label>
                                    <input type="text" name="street" class="form-control" placeholder="123 Main St" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" placeholder="City" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" placeholder="State" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" name="zip" class="form-control" placeholder="ZIP" required>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Your privacy is our priority.</strong> All information is encrypted and securely transmitted. We respect your privacy and handle your data with care.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent" value="1" id="consentCheck" required>
                                        <label class="form-check-label" for="consentCheck">
                                            I certify that all information provided is accurate and complete to the best of my knowledge.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-primary" id="prevStep" disabled>Previous</button>
                            <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                            <button type="submit" class="btn btn-success d-none" id="submitForm">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('xhtml/assets/vendor/global/global.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const steps = Array.from(document.querySelectorAll('.step-pane'));
    const indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitForm');
    const form = document.getElementById('multiStepForm');
    let current = 0;

    // Function to validate current step
    const validateStep = (stepIndex, showAlert = true) => {
        const currentStep = steps[stepIndex];
        const requiredFields = currentStep.querySelectorAll('[required]');
        let isValid = true;
        const firstInvalidField = [];

        requiredFields.forEach(field => {
            let fieldValue = '';
            let isEmpty = false;

            // Handle different input types
            if (field.type === 'checkbox') {
                isEmpty = !field.checked;
            } else if (field.type === 'radio') {
                const radioGroup = currentStep.querySelectorAll(`input[name="${field.name}"][type="radio"]`);
                const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                isEmpty = !isChecked;
            } else if (field.tagName === 'SELECT') {
                fieldValue = field.value;
                isEmpty = !fieldValue || fieldValue === '';
            } else {
                fieldValue = field.value;
                isEmpty = !fieldValue || fieldValue.trim() === '';
            }

            if (isEmpty) {
                isValid = false;
                field.classList.add('is-invalid');
                if (firstInvalidField.length === 0) {
                    firstInvalidField.push(field);
                }
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Special validation for step 3 - at least one assistance type must be selected
        if (stepIndex === 2) {
            const assistanceCheckboxes = currentStep.querySelectorAll('.assistance-type-checkbox');
            const atLeastOneChecked = Array.from(assistanceCheckboxes).some(cb => cb.checked);
            if (!atLeastOneChecked) {
                isValid = false;
                const container = currentStep.querySelector('#assistanceTypesContainer');
                const errorDiv = currentStep.querySelector('#assistanceTypesError');
                if (container) {
                    container.classList.add('is-invalid');
                    container.style.border = '1px solid #dc3545';
                    container.style.borderRadius = '0.375rem';
                    container.style.padding = '0.5rem';
                }
                if (errorDiv) {
                    errorDiv.classList.remove('d-none');
                }
            } else {
                const container = currentStep.querySelector('#assistanceTypesContainer');
                const errorDiv = currentStep.querySelector('#assistanceTypesError');
                if (container) {
                    container.classList.remove('is-invalid');
                    container.style.border = '';
                    container.style.padding = '';
                }
                if (errorDiv) {
                    errorDiv.classList.add('d-none');
                }
            }
        }

        // Special validation for step 4 - consent checkbox (already has required attribute, but double-check)
        if (stepIndex === 3) {
            const consentCheckbox = currentStep.querySelector('#consentCheck');
            if (consentCheckbox && !consentCheckbox.checked) {
                isValid = false;
                consentCheckbox.classList.add('is-invalid');
                if (firstInvalidField.length === 0) {
                    firstInvalidField.push(consentCheckbox);
                }
            }
        }

        if (!isValid && firstInvalidField.length > 0) {
            // Remove invalid class from all fields first
            currentStep.querySelectorAll('.is-invalid').forEach(f => {
                if (f !== firstInvalidField[0]) {
                    f.classList.remove('is-invalid');
                }
            });
            
            // Only show alert and focus if showAlert is true
            if (showAlert) {
                firstInvalidField[0].focus();
                firstInvalidField[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Show alert
                setTimeout(() => {
                    alert('Please fill in all required fields before proceeding.');
                }, 100);
            }
            
            return false;
        }

        return true;
    };

    // Function to force close all date pickers and reset their state
    const closeAllDatePickers = () => {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        // First, blur any active date input
        if (document.activeElement && document.activeElement.type === 'date') {
            document.activeElement.blur();
        }
        
        dateInputs.forEach(input => {
            // Force blur
            input.blur();
            
            // Remove focus programmatically
            if (document.activeElement === input) {
                input.blur();
            }
            
            // Add class to prevent picker
            input.classList.add('no-picker');
            
            // Disable temporarily
            input.disabled = true;
            
            // Set readonly to prevent interaction
            input.setAttribute('readonly', 'readonly');
            
            // Force remove focus using setTimeout
            setTimeout(() => {
                input.blur();
            }, 0);
        });
        
        // Force focus away from any date input
        if (document.activeElement && document.activeElement.type === 'date') {
            document.body.focus();
            document.activeElement.blur();
        }
    };

    // Function to reset date picker state after navigation
    const resetDatePickers = () => {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        setTimeout(() => {
            dateInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('no-picker');
                input.removeAttribute('readonly');
            });
        }, 600);
    };

    const setStep = (index) => {
        // Set flag to prevent date picker from opening during step change
        document.body.dataset.stepChanging = 'true';
        document.body.dataset.navigating = 'true';
        
        // Force close all date pickers
        closeAllDatePickers();
        
        // Blur any active element
        if (document.activeElement) {
            document.activeElement.blur();
        }
        
        // Change steps
        steps.forEach((step, i) => step.classList.toggle('d-none', i !== index));
        indicators.forEach((el, i) => {
            el.classList.remove('bg-primary', 'text-white', 'border');
            el.classList.add('bg-light', 'border');
            if (i === index) {
                el.classList.add('bg-primary', 'text-white');
                el.classList.remove('bg-light');
            }
        });
        prevBtn.disabled = index === 0;
        nextBtn.classList.toggle('d-none', index === steps.length - 1);
        submitBtn.classList.toggle('d-none', index !== steps.length - 1);
        
        // Reset date pickers after step change
        resetDatePickers();
        
        // Clear the flags after a delay
        setTimeout(() => {
            delete document.body.dataset.stepChanging;
            delete document.body.dataset.navigating;
        }, 700);
    };

    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        // Validate current step first - MUST pass validation
        const validationResult = validateStep(current);
        if (!validationResult) {
            e.stopImmediatePropagation();
            return false;
        }

        // Only proceed if validation passed
        // Set navigation flag immediately
        document.body.dataset.navigating = 'true';
        
        // Close all date pickers first
        closeAllDatePickers();
        
        // Blur any active element
        if (document.activeElement) {
            document.activeElement.blur();
        }

        // Wait to ensure all date pickers are closed
        setTimeout(() => {
            if (current < steps.length - 1) {
                current += 1;
                setStep(current);
            }
        }, 200);
    });

    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        // Set navigation flag immediately
        document.body.dataset.navigating = 'true';
        
        // Close all date pickers first
        closeAllDatePickers();
        
        // Blur any active element
        if (document.activeElement) {
            document.activeElement.blur();
        }

        // Wait to ensure all date pickers are closed
        setTimeout(() => {
            if (current > 0) {
                current -= 1;
                setStep(current);
            }
        }, 200);
    });

    setStep(current);

    // Also handle mousedown to prevent date picker from opening when clicking buttons
    nextBtn.addEventListener('mousedown', (e) => {
        e.preventDefault();
        document.body.dataset.navigating = 'true';
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
            input.setAttribute('readonly', 'readonly');
        });
        if (document.activeElement && document.activeElement.type === 'date') {
            document.activeElement.blur();
        }
    });

    prevBtn.addEventListener('mousedown', (e) => {
        e.preventDefault();
        document.body.dataset.navigating = 'true';
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
            input.setAttribute('readonly', 'readonly');
        });
        if (document.activeElement && document.activeElement.type === 'date') {
            document.activeElement.blur();
        }
    });

    // Function to validate all steps
    const validateAllSteps = () => {
        let allValid = true;
        const invalidSteps = [];
        
        // Validate each step (without showing individual alerts)
        for (let i = 0; i < steps.length; i++) {
            const stepValid = validateStep(i, false); // false = don't show alert
            if (!stepValid) {
                allValid = false;
                invalidSteps.push(i + 1);
            }
        }
        
        if (!allValid) {
            // Find the first invalid step and go to it
            const firstInvalidStep = invalidSteps[0] - 1;
            if (firstInvalidStep !== current) {
                current = firstInvalidStep;
                setStep(current);
                // Now show alert and focus on the first invalid field
                setTimeout(() => {
                    const currentStep = steps[current];
                    const firstInvalid = currentStep.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    alert('Please complete all required fields in all steps before submitting. You are now on step ' + (current + 1) + '.');
                }, 300);
            } else {
                // Already on invalid step, just show alert
                const currentStep = steps[current];
                const firstInvalid = currentStep.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                alert('Please complete all required fields in all steps before submitting.');
            }
            return false;
        }
        
        return true;
    };

    // Handle submit button click
    submitBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Validate all steps before submission
        if (!validateAllSteps()) {
            return false;
        }
        
        // If all validation passed, submit the form
        form.submit();
    });

    // Prevent form submission when clicking Next/Previous or if validation fails
    form.addEventListener('submit', (e) => {
        // If not on last step, prevent submission
        if (current !== steps.length - 1) {
            e.preventDefault();
            return false;
        }
        
        // Validate all steps before final submission
        if (!validateAllSteps()) {
            e.preventDefault();
            return false;
        }
        
        // Allow submission if all validations pass
        return true;
    });

    // Range displays
    const incomeRange = document.getElementById('incomeRange');
    const incomeValue = document.getElementById('incomeValue');
    const amountRange = document.getElementById('amountRange');
    const amountValue = document.getElementById('amountValue');

    const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(val);
    incomeRange?.addEventListener('input', e => incomeValue.textContent = formatCurrency(e.target.value));
    amountRange?.addEventListener('input', e => amountValue.textContent = formatCurrency(e.target.value));
    incomeValue.textContent = formatCurrency(incomeRange.value);
    amountValue.textContent = formatCurrency(amountRange.value);

    // Family members dynamic rows
    const familyWrapper = document.getElementById('familyMembers');
    const addBtn = document.getElementById('addFamilyMember');
    let memberIndex = 0;

    const addFamilyMemberRow = () => {
        const row = document.createElement('div');
        row.className = 'col-12';
        row.innerHTML = `
            <div class="row g-2 align-items-center border rounded p-3 bg-light">
                <div class="col-md-4">
                    <input type="text" name="family_members[${memberIndex}][name]" class="form-control" placeholder="Full Name">
                </div>
                <div class="col-md-3">
                    <input type="number" name="family_members[${memberIndex}][age]" class="form-control" placeholder="Age" min="0" max="120">
                </div>
                <div class="col-md-4">
                    <input type="text" name="family_members[${memberIndex}][relationship]" class="form-control" placeholder="Relationship">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-light text-danger remove-member" aria-label="Remove family member">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        memberIndex += 1;
        familyWrapper.appendChild(row);
    };

    addBtn.addEventListener('click', addFamilyMemberRow);
    familyWrapper.addEventListener('click', (e) => {
        if (e.target.closest('.remove-member')) {
            e.target.closest('.col-12').remove();
        }
    });
    addFamilyMemberRow();
    
    // Clear assistance types error when checkbox is clicked
    document.querySelectorAll('.assistance-type-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const container = document.getElementById('assistanceTypesContainer');
                const errorDiv = document.getElementById('assistanceTypesError');
                if (container) {
                    container.classList.remove('is-invalid');
                    container.style.border = '';
                    container.style.padding = '';
                }
                if (errorDiv) {
                    errorDiv.classList.add('d-none');
                }
            }
        });
    });

    // Prevent date inputs from opening when clicking Next/Previous buttons
    document.addEventListener('click', (e) => {
        if (e.target === nextBtn || e.target === prevBtn || nextBtn.contains(e.target) || prevBtn.contains(e.target)) {
            closeAllDatePickers();
            document.body.dataset.navigating = 'true';
        }
    }, true);

    // Track date picker state
    let datePickerActive = false;
    let dateInputWithValue = null;
    
    // Make date inputs readonly by default, only allow editing when explicitly clicked
    document.querySelectorAll('input[type="date"]').forEach(input => {
        // Set readonly initially - user must click to edit
        input.setAttribute('readonly', 'readonly');
        
        // When user explicitly clicks on date input, allow editing
        input.addEventListener('mousedown', function(e) {
            // Only allow if this is a direct click on the date input
            if (e.target === input) {
                input.removeAttribute('readonly');
                datePickerActive = true;
                document.body.setAttribute('data-date-picker-active', 'true');
            }
        }, true);
        
        // When date picker opens (focus)
        input.addEventListener('focus', function() {
            datePickerActive = true;
            document.body.setAttribute('data-date-picker-active', 'true');
        });
        
        // CRITICAL: When date is selected, immediately make it readonly again and blur
        input.addEventListener('change', function() {
            // Mark that this input has a value
            dateInputWithValue = input;
            
            // Make it readonly immediately
            input.setAttribute('readonly', 'readonly');
            
            // Force blur
            setTimeout(() => {
                input.blur();
                datePickerActive = false;
                document.body.removeAttribute('data-date-picker-active');
                
                // Force focus away
                if (document.activeElement === input) {
                    document.body.focus();
                }
            }, 10);
        });
        
        // When date input loses focus, make it readonly
        input.addEventListener('blur', function() {
            input.setAttribute('readonly', 'readonly');
            datePickerActive = false;
            document.body.removeAttribute('data-date-picker-active');
        });
        
        // Prevent focus if another field is being focused
        input.addEventListener('focus', function(e) {
            if (document.body.getAttribute('data-other-field-focused') === 'true') {
                e.preventDefault();
                input.blur();
                return false;
            }
        }, true);

        // Prevent focus from opening date picker during navigation
        input.addEventListener('focus', (e) => {
            if (document.body.dataset.stepChanging === 'true' || 
                document.body.dataset.navigating === 'true' || 
                input.disabled || 
                input.classList.contains('no-picker') ||
                input.hasAttribute('readonly')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                e.target.blur();
                return false;
            }
        }, true);

        // Prevent click from opening date picker during navigation
        input.addEventListener('click', (e) => {
            if (document.body.dataset.navigating === 'true' || 
                document.body.dataset.stepChanging === 'true' ||
                input.disabled || 
                input.classList.contains('no-picker') ||
                input.hasAttribute('readonly')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                input.blur();
                return false;
            }
        }, true);

        // Prevent mousedown from opening date picker during navigation
        input.addEventListener('mousedown', (e) => {
            if (document.body.dataset.navigating === 'true' || 
                document.body.dataset.stepChanging === 'true' ||
                input.disabled || 
                input.classList.contains('no-picker') ||
                input.hasAttribute('readonly')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                input.blur();
                return false;
            }
        }, true);
        
        // Prevent touchstart (for mobile)
        input.addEventListener('touchstart', (e) => {
            if (document.body.dataset.navigating === 'true' || 
                document.body.dataset.stepChanging === 'true' ||
                input.disabled || 
                input.classList.contains('no-picker') ||
                input.hasAttribute('readonly')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                input.blur();
                return false;
            }
        }, true);
        
        // When date picker closes (blur), ensure it doesn't affect other fields
        input.addEventListener('blur', () => {
            activeDateInput = null;
            // Small delay to ensure calendar is fully closed
            setTimeout(() => {
                // Force remove any lingering focus states
                const allInputs = document.querySelectorAll('input, select, textarea');
                allInputs.forEach(inp => {
                    if (inp !== input && inp.type !== 'date') {
                        // Ensure other inputs can receive focus normally
                        inp.style.pointerEvents = 'auto';
                    }
                });
            }, 150);
        });
    });
    
    // Prevent calendar from opening when clicking on other fields
    document.addEventListener('click', (e) => {
        const target = e.target;
        const isDateInput = target.type === 'date' || target.closest('input[type="date"]');
        const isOtherInput = (target.tagName === 'INPUT' && target.type !== 'date') || 
                            target.tagName === 'SELECT' || 
                            target.tagName === 'TEXTAREA';
        
        // If we're navigating, ensure date pickers are closed
        if (document.body.dataset.navigating === 'true' || document.body.dataset.stepChanging === 'true') {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (document.activeElement === input) {
                    input.blur();
                }
            });
        }
        
        // CRITICAL: If clicking on a non-date field, force close any open date picker
        if (isOtherInput || (!isDateInput && target.tagName !== 'BUTTON')) {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                // Force blur if it's the active element
                if (document.activeElement === input || activeDateInput === input) {
                    input.blur();
                    activeDateInput = null;
                }
                // Also prevent it from getting focus
                input.style.pointerEvents = 'none';
                setTimeout(() => {
                    input.style.pointerEvents = 'auto';
                }, 200);
            });
        }
    }, true);
    
    // Also prevent date picker from opening when mousedown on other fields
    document.addEventListener('mousedown', (e) => {
        const target = e.target;
        const isDateInput = target.type === 'date' || target.closest('input[type="date"]');
        const isOtherInput = (target.tagName === 'INPUT' && target.type !== 'date') || 
                            target.tagName === 'SELECT' || 
                            target.tagName === 'TEXTAREA';
        
        // If clicking on a non-date field, force close any open date picker
        if (isOtherInput || (!isDateInput && target.tagName !== 'BUTTON')) {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (document.activeElement === input || activeDateInput === input) {
                    input.blur();
                    activeDateInput = null;
                }
            });
        }
    }, true);
    
    // CRITICAL: Handle when user interacts with any other input field
    document.querySelectorAll('input:not([type="date"]), select, textarea').forEach(field => {
        // On mousedown (before focus), set flag and close date pickers
        field.addEventListener('mousedown', (e) => {
            document.body.setAttribute('data-other-field-focused', 'true');
            
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                // Force blur and make readonly
                if (document.activeElement === input) {
                    input.blur();
                }
                input.setAttribute('readonly', 'readonly');
                input.style.pointerEvents = 'none';
            });
            
            // Clear flag after a delay
            setTimeout(() => {
                document.body.removeAttribute('data-other-field-focused');
                dateInputs.forEach(input => {
                    input.style.pointerEvents = 'auto';
                });
            }, 500);
        }, true);
        
        // On focus, ensure date pickers are closed
        field.addEventListener('focus', (e) => {
            document.body.setAttribute('data-other-field-focused', 'true');
            
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (document.activeElement === input) {
                    input.blur();
                }
                input.setAttribute('readonly', 'readonly');
            });
        }, true);
        
        // On click, close date pickers
        field.addEventListener('click', (e) => {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (document.activeElement === input) {
                    input.blur();
                }
                input.setAttribute('readonly', 'readonly');
            });
        }, true);
    });
    
    // Global click handler - if clicking anywhere that's not a date input, close date pickers
    document.addEventListener('click', (e) => {
        const target = e.target;
        const isDateInput = target.type === 'date' || target.closest('input[type="date"]');
        
        if (!isDateInput) {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (document.activeElement === input) {
                    input.blur();
                }
                // Make all date inputs readonly
                input.setAttribute('readonly', 'readonly');
            });
            document.body.removeAttribute('data-date-picker-active');
        }
    }, true);
});
</script>
</body>
</html>
