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
        /* Same protections as admin form to stop date picker opening on step change */
        body[data-navigating="true"] input[type="date"],
        body[data-step-changing="true"] input[type="date"] {
            pointer-events: none !important;
            opacity: 0.7;
        }

        input[type="date"].no-picker {
            pointer-events: none !important;
        }

        #nextStep, #prevStep {
            pointer-events: auto !important;
            z-index: 1000;
            position: relative;
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
                                    <input type="date" name="dob" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="step-pane d-none" data-step="2">
                            <h5 class="mb-3">Family &amp; Financial Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Household Size</label>
                                    <select name="household_size" class="form-control">
                                        <option value="">Select number of people</option>
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Number of Dependents</label>
                                    <select name="dependents" class="form-control">
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
                                    <select name="employment_status" class="form-control">
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
                                <label class="form-label">Type of Assistance Needed</label>
                                <div class="row g-2">
                                    @foreach (['Housing Expenses', 'Utility Bills', 'Education Fees', 'Medical Expenses', 'Food & Essentials', 'Other'] as $type)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assistance_types[]" value="{{ $type }}" id="assist-{{ Str::slug($type) }}">
                                                <label class="form-check-label" for="assist-{{ Str::slug($type) }}">{{ $type }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Please describe your situation and how this assistance will help</label>
                                <textarea name="assistance_description" rows="4" class="form-control" placeholder="Provide details about your current financial situation and how this assistance would help you and your family..."></textarea>
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
                                    <input type="text" name="street" class="form-control" placeholder="123 Main St">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" placeholder="City">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" placeholder="State">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" name="zip" class="form-control" placeholder="ZIP">
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Your privacy is our priority.</strong> All information is encrypted and securely transmitted. We respect your privacy and handle your data with care.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent" value="1" id="consentCheck">
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

    // Function to force close all date pickers (same logic as admin form)
    const closeAllDatePickers = () => {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.classList.add('no-picker');
            input.disabled = true;
            input.blur();
            if (document.activeElement === input) {
                input.blur();
            }
            setTimeout(() => {
                input.disabled = false;
                input.classList.remove('no-picker');
            }, 500);
        });
        if (document.activeElement && document.activeElement.type === 'date') {
            document.activeElement.blur();
        }
    };

    const setStep = (index) => {
        document.body.dataset.stepChanging = 'true';
        closeAllDatePickers();

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

        setTimeout(() => {
            delete document.body.dataset.stepChanging;
            delete document.body.dataset.navigating;
        }, 400);
    };

    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
        });

        document.body.dataset.navigating = 'true';
        closeAllDatePickers();

        setTimeout(() => {
            if (current < steps.length - 1) {
                current += 1;
                setStep(current);
            }
            setTimeout(() => {
                dateInputs.forEach(input => {
                    input.disabled = false;
                    input.classList.remove('no-picker');
                });
                delete document.body.dataset.navigating;
            }, 300);
        }, 50);
    });

    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
        });

        document.body.dataset.navigating = 'true';
        closeAllDatePickers();

        setTimeout(() => {
            if (current > 0) {
                current -= 1;
                setStep(current);
            }
            setTimeout(() => {
                dateInputs.forEach(input => {
                    input.disabled = false;
                    input.classList.remove('no-picker');
                });
                delete document.body.dataset.navigating;
            }, 300);
        }, 50);
    });

    setStep(current);

    // Also handle mousedown to prevent date picker from opening when clicking buttons
    nextBtn.addEventListener('mousedown', () => {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
        });
        document.body.dataset.navigating = 'true';
    });

    prevBtn.addEventListener('mousedown', () => {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('no-picker');
            input.blur();
        });
        document.body.dataset.navigating = 'true';
    });

    // Prevent form submission when clicking Next/Previous
    form.addEventListener('submit', (e) => {
        if (current !== steps.length - 1) {
            e.preventDefault();
            return false;
        }
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

    // Prevent date inputs from opening when clicking Next/Previous buttons
    document.addEventListener('click', (e) => {
        if (e.target === nextBtn || e.target === prevBtn || nextBtn.contains(e.target) || prevBtn.contains(e.target)) {
            closeAllDatePickers();
            document.body.dataset.navigating = 'true';
        }
    }, true);

    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('focus', (e) => {
            if (document.body.dataset.stepChanging === 'true' || 
                document.body.dataset.navigating === 'true' || 
                input.disabled || 
                input.classList.contains('no-picker')) {
                e.preventDefault();
                e.stopPropagation();
                e.target.blur();
                return false;
            }
        }, true);

        input.addEventListener('click', (e) => {
            if (document.body.dataset.navigating === 'true' || 
                input.disabled || 
                input.classList.contains('no-picker')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                input.blur();
                return false;
            }
        }, true);

        input.addEventListener('mousedown', (e) => {
            if (document.body.dataset.navigating === 'true' || 
                input.disabled || 
                input.classList.contains('no-picker')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }
        }, true);
    });
});
</script>
</body>
</html>
