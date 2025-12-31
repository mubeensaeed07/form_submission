@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($formType === 'charity')
            Charity Assistance Application
        @elseif($formType === 'loan')
            Loan Application
        @elseif($formType === 'grant')
            Grant Application
        @else
            Charity Assistance Application
        @endif
    </title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('xhtml/assets/images/favicon.avif') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('xhtml/assets/css/style.css') }}">
    <style>
        /* Validation error styling */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        /* Date input styling */
        .date-text-input {
            text-align: center;
            letter-spacing: 1px;
        }
        
        .input-group .btn {
            z-index: 3;
        }
        
        /* Hidden date picker for calendar functionality */
        #dobDatePicker {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }
        
        /* Header styling */
        .form-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 2rem 2rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logo-box {
            background: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 0.6rem 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .sofi-logo-img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }
        
        .logo-box .partner-text {
            font-size: 0.85rem;
            font-weight: 500;
            color: #1e3a8a;
            white-space: nowrap;
            line-height: 1.2;
            margin-left: 0.5rem;
        }
        
        .secure-btn {
            background: rgba(147, 197, 253, 0.25);
            border: 2px solid rgba(147, 197, 253, 0.4);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .header-button {
            background: rgba(147, 197, 253, 0.25);
            border: 2px solid rgba(147, 197, 253, 0.4);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: white;
            font-weight: 500;
            width: 100%;
            margin-bottom: 0.75rem;
            text-align: center;
            cursor: default;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s;
        }
        
        .header-button:hover {
            transform: translateY(-2px);
        }
        
        .header-button:last-child {
            margin-bottom: 0;
        }
        
        /* 3D Form Card Styling */
        .form-card-3d {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 
                        0 10px 30px rgba(0, 0, 0, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.8);
            padding: 0;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }
        
        /* Banner styling inside form */
        .form-banner-inner {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            margin: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-banner-inner h1 {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .form-banner-inner p {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 400;
        }
        
        .form-card-body {
            padding: 2.5rem;
        }
        
        .step-badge {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
        }
        
        .step-badge.bg-primary {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            transform: scale(1.1);
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }
        
        .btn-outline-primary {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-width: 2px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* 3D Amount Display */
        .amount-display-3d {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4),
                        0 5px 15px rgba(59, 130, 246, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .amount-display-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
            pointer-events: none;
        }
        
        .amount-display-3d span {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
            display: block;
        }
        
        /* 3D Assistance Type Buttons */
        .assistance-type-btn-3d {
            width: 100%;
            background: #ffffff;
            border: 3px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1),
                        0 2px 6px rgba(0, 0, 0, 0.08),
                        inset 0 1px 0 rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60px;
        }
        
        .assistance-type-btn-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6) 0%, transparent 100%);
            pointer-events: none;
            transition: all 0.3s ease;
        }
        
        .assistance-type-btn-3d:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15),
                        0 3px 10px rgba(0, 0, 0, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: #d1d5db;
        }
        
        .assistance-type-btn-3d:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12),
                        inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Selected state with green outline */
        .assistance-type-btn-3d.selected {
            border-color: #10b981;
            border-width: 3px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3),
                        0 3px 10px rgba(16, 185, 129, 0.2),
                        inset 0 0 0 2px rgba(16, 185, 129, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.9);
            color: #059669;
        }
        
        .assistance-type-btn-3d.selected:hover {
            border-color: #059669;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4),
                        0 4px 12px rgba(16, 185, 129, 0.3),
                        inset 0 0 0 2px rgba(16, 185, 129, 0.15),
                        inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        
        .assistance-type-btn-3d.selected::before {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.1) 0%, transparent 100%);
        }
        
        .assistance-type-btn-3d span {
            position: relative;
            z-index: 1;
        }
        
        /* Error state for assistance types container */
        #assistanceTypesContainer.is-invalid {
            border: 2px solid #dc3545 !important;
            border-radius: 12px;
            padding: 1rem;
        }
    </style>
</head>
<body style="background:#f4f7fb;">
<!-- Header Section -->
<div class="form-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <div class="logo-box">
                    <img src="{{ asset('images/sofi-logo.png') }}" alt="SoFi" class="sofi-logo-img" onerror="this.style.display='none';">
                    <span class="partner-text">Lifelift Charity Partner</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="secure-btn">Secure</div>
            </div>
            <div class="col-md-4 text-center">
                <p class="mb-0" style="font-size: 0.95rem; line-height: 1.4;">Providing financial assistance<br>to families in need</p>
            </div>
            <div class="col-md-3">
                <div class="header-button">Direct Cash Assistance</div>
                <div class="header-button">Bill Payment Support</div>
                <div class="header-button">Education Funding</div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="form-card-3d">
                <!-- Banner Section Inside Form -->
                <div class="form-banner-inner">
                    @if($formType === 'charity')
                        <h1>CHARITY ASSISTANCE APPLICATION</h1>
                        <p>Complete our simple form to apply for financial assistance</p>
                    @elseif($formType === 'loan')
                        <h1>LOAN APPLICATION</h1>
                        <p>Complete our simple form to apply for a loan</p>
                    @elseif($formType === 'grant')
                        <h1>GRANT APPLICATION</h1>
                        <p>Complete our simple form to apply for a grant</p>
                    @endif
                </div>
                
                <div class="form-card-body">
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        @foreach ([1,2,3,4] as $step)
                            <div class="text-center">
                                <div class="step-badge bg-light border text-primary" data-step-indicator="{{ $step }}">{{ $step }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <strong>Please correct the following errors:</strong>
                            <ul class="mb-0 mt-2">
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
                        <input type="hidden" name="form_type" value="{{ $formType }}">

                        {{-- Step 1 --}}
                        <div class="step-pane" data-step="1">
                            <h5 class="mb-3">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <input type="text" id="dobInput" class="form-control date-text-input @error('dob') is-invalid @enderror" placeholder="mm/dd/yyyy" maxlength="10" value="{{ old('dob') ? \Carbon\Carbon::parse(old('dob'))->format('m/d/Y') : '' }}" required>
                                        <button type="button" class="btn btn-outline-secondary" id="dobCalendarBtn" style="border-left: 0;">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                        <input type="date" name="dob" id="dobDatePicker" value="{{ old('dob') }}">
                                    </div>
                                    @error('dob')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="step-pane d-none" data-step="2">
                            <h5 class="mb-3">Family &amp; Financial Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Household Size</label>
                                    <select name="household_size" class="form-control @error('household_size') is-invalid @enderror" required>
                                        <option value="">Select number of people</option>
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" {{ old('household_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('household_size')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Number of Dependents</label>
                                    <select name="dependents" class="form-control @error('dependents') is-invalid @enderror" required>
                                        <option value="">Number of dependents</option>
                                        @for($i=0; $i<=10; $i++)
                                            <option value="{{ $i }}" {{ old('dependents') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('dependents')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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
                                    <select name="employment_status" class="form-control @error('employment_status') is-invalid @enderror" required>
                                        <option value="">Select employment status</option>
                                        <option value="Employed" {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                        <option value="Self-Employed" {{ old('employment_status') == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                        <option value="Unemployed" {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                        <option value="Student" {{ old('employment_status') == 'Student' ? 'selected' : '' }}>Student</option>
                                        <option value="Retired" {{ old('employment_status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    </select>
                                    @error('employment_status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Employer Name (if applicable)</label>
                                    <input type="text" name="employer_name" class="form-control @error('employer_name') is-invalid @enderror" value="{{ old('employer_name') }}" required>
                                    @error('employer_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Monthly Household Income</span>
                                    <span id="incomeValue">$0</span>
                                </label>
                                <input type="range" name="monthly_income" min="0" max="5000" step="100" value="{{ old('monthly_income', 0) }}" class="form-range" id="incomeRange">
                                <div class="d-flex justify-content-between text-muted">
                                    <span>$0</span>
                                    <span>$5,000+</span>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="step-pane d-none" data-step="3">
                            @if($formType === 'charity')
                                <h5 class="mb-3">Assistance Details</h5>
                                <div class="mb-4">
                                    <label class="form-label">Assistance Amount Requested</label>
                                    <div class="amount-display-3d mb-3">
                                        <span id="amountValue">$2,000</span>
                                    </div>
                                    <input type="range" name="assistance_amount" min="500" max="10000" step="100" value="{{ old('assistance_amount', 2000) }}" class="form-range" id="amountRange">
                                    <div class="d-flex justify-content-between text-muted mt-2">
                                        <span>$500</span>
                                        <span>$10,000</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Type of Assistance Needed <span class="text-danger">*</span></label>
                                    <div class="row g-3" id="assistanceTypesContainer" data-required="true">
                                        @foreach (['Housing Expenses', 'Utility Bills', 'Education Fees', 'Medical Expenses', 'Food & Essentials', 'Other'] as $type)
                                            <div class="col-md-4">
                                                <button type="button" class="assistance-type-btn-3d" data-type="{{ $type }}" data-checked="{{ in_array($type, old('assistance_types', [])) ? 'true' : 'false' }}">
                                                    <input class="assistance-type-checkbox d-none" type="checkbox" name="assistance_types[]" value="{{ $type }}" id="assist-{{ Str::slug($type) }}" {{ in_array($type, old('assistance_types', [])) ? 'checked' : '' }}>
                                                    <span>{{ $type }}</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback d-none" id="assistanceTypesError">Please select at least one type of assistance needed.</div>
                                    @error('assistance_types')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Please describe your situation and how this assistance will help</label>
                                    <textarea name="assistance_description" rows="4" class="form-control @error('assistance_description') is-invalid @enderror" placeholder="Provide details about your current financial situation and how this assistance would help you and your family..." required>{{ old('assistance_description') }}</textarea>
                                    @error('assistance_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif($formType === 'loan')
                                <h5 class="mb-3">Loan Details</h5>
                                <div class="mb-4">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Loan Amount Requested</span>
                                        <span id="loanAmountValue">$5,000</span>
                                    </label>
                                    <input type="range" name="loan_amount" min="1000" max="50000" step="500" value="{{ old('loan_amount', 5000) }}" class="form-range" id="loanAmountRange">
                                    <div class="d-flex justify-content-between text-muted">
                                        <span>$1,000</span>
                                        <span>$50,000</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Loan Purpose <span class="text-danger">*</span></label>
                                    <select name="loan_purpose" class="form-control @error('loan_purpose') is-invalid @enderror" required>
                                        <option value="">Select loan purpose</option>
                                        <option value="Home Purchase" {{ old('loan_purpose') == 'Home Purchase' ? 'selected' : '' }}>Home Purchase</option>
                                        <option value="Home Improvement" {{ old('loan_purpose') == 'Home Improvement' ? 'selected' : '' }}>Home Improvement</option>
                                        <option value="Debt Consolidation" {{ old('loan_purpose') == 'Debt Consolidation' ? 'selected' : '' }}>Debt Consolidation</option>
                                        <option value="Business" {{ old('loan_purpose') == 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="Education" {{ old('loan_purpose') == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Medical Expenses" {{ old('loan_purpose') == 'Medical Expenses' ? 'selected' : '' }}>Medical Expenses</option>
                                        <option value="Vehicle Purchase" {{ old('loan_purpose') == 'Vehicle Purchase' ? 'selected' : '' }}>Vehicle Purchase</option>
                                        <option value="Other" {{ old('loan_purpose') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('loan_purpose')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Loan Term (Months) <span class="text-danger">*</span></label>
                                    <select name="loan_term" class="form-control @error('loan_term') is-invalid @enderror" required>
                                        <option value="">Select loan term</option>
                                        <option value="12" {{ old('loan_term') == '12' ? 'selected' : '' }}>12 months</option>
                                        <option value="24" {{ old('loan_term') == '24' ? 'selected' : '' }}>24 months</option>
                                        <option value="36" {{ old('loan_term') == '36' ? 'selected' : '' }}>36 months</option>
                                        <option value="48" {{ old('loan_term') == '48' ? 'selected' : '' }}>48 months</option>
                                        <option value="60" {{ old('loan_term') == '60' ? 'selected' : '' }}>60 months</option>
                                    </select>
                                    @error('loan_term')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Please describe why you need this loan</label>
                                    <textarea name="assistance_description" rows="4" class="form-control @error('assistance_description') is-invalid @enderror" placeholder="Provide details about why you need this loan and how you plan to use it..." required>{{ old('assistance_description') }}</textarea>
                                    @error('assistance_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif($formType === 'grant')
                                <h5 class="mb-3">Grant Details</h5>
                                <div class="mb-4">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Grant Amount Requested</span>
                                        <span id="grantAmountValue">$3,000</span>
                                    </label>
                                    <input type="range" name="grant_amount" min="500" max="20000" step="100" value="{{ old('grant_amount', 3000) }}" class="form-range" id="grantAmountRange">
                                    <div class="d-flex justify-content-between text-muted">
                                        <span>$500</span>
                                        <span>$20,000</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Grant Category <span class="text-danger">*</span></label>
                                    <select name="grant_category" class="form-control @error('grant_category') is-invalid @enderror" required>
                                        <option value="">Select grant category</option>
                                        <option value="Education" {{ old('grant_category') == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Healthcare" {{ old('grant_category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                        <option value="Housing" {{ old('grant_category') == 'Housing' ? 'selected' : '' }}>Housing</option>
                                        <option value="Small Business" {{ old('grant_category') == 'Small Business' ? 'selected' : '' }}>Small Business</option>
                                        <option value="Research" {{ old('grant_category') == 'Research' ? 'selected' : '' }}>Research</option>
                                        <option value="Community Development" {{ old('grant_category') == 'Community Development' ? 'selected' : '' }}>Community Development</option>
                                        <option value="Arts & Culture" {{ old('grant_category') == 'Arts & Culture' ? 'selected' : '' }}>Arts & Culture</option>
                                        <option value="Other" {{ old('grant_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('grant_category')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Grant Purpose <span class="text-danger">*</span></label>
                                    <input type="text" name="grant_purpose" class="form-control @error('grant_purpose') is-invalid @enderror" placeholder="Brief description of the grant purpose" value="{{ old('grant_purpose') }}" required>
                                    @error('grant_purpose')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Please describe your project or need</label>
                                    <textarea name="assistance_description" rows="4" class="form-control @error('assistance_description') is-invalid @enderror" placeholder="Provide detailed information about your project, goals, and how this grant will help..." required>{{ old('assistance_description') }}</textarea>
                                    @error('assistance_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- Step 4 --}}
                        <div class="step-pane d-none" data-step="4">
                            <h5 class="mb-3">Verification &amp; Declaration</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Social Security Number (SSN)</label>
                                    <input type="text" name="ssn" id="ssnInput" class="form-control @error('ssn') is-invalid @enderror" placeholder="XXX-XX-XXXX" maxlength="11" value="{{ old('ssn') }}" required>
                                    <small class="text-muted">Format: XXX-XX-XXXX</small>
                                    @error('ssn')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Street Address</label>
                                    <input type="text" name="street" class="form-control @error('street') is-invalid @enderror" placeholder="123 Main St" value="{{ old('street') }}" required>
                                    @error('street')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder="City" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" placeholder="State" value="{{ old('state') }}" required>
                                    @error('state')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" name="zip" class="form-control @error('zip') is-invalid @enderror" placeholder="ZIP" value="{{ old('zip') }}" required>
                                    @error('zip')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Your privacy is our priority.</strong> All information is encrypted and securely transmitted. We respect your privacy and handle your data with care.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input @error('consent') is-invalid @enderror" type="checkbox" name="consent" value="1" id="consentCheck" {{ old('consent') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="consentCheck">
                                            I certify that all information provided is accurate and complete to the best of my knowledge.
                                        </label>
                                    </div>
                                    @error('consent')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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
    // Clear any stored step state (localStorage/sessionStorage) to ensure fresh start
    try {
        localStorage.removeItem('formStep');
        sessionStorage.removeItem('formStep');
    } catch (e) {
        // Ignore if storage is not available
    }
    
    const steps = Array.from(document.querySelectorAll('.step-pane'));
    const indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitForm');
    const form = document.getElementById('multiStepForm');
    
    // Always start at step 0 (first step) unless there are validation errors
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

        // Special validation for step 1 - Date of Birth format
        if (stepIndex === 0) {
            const dobField = currentStep.querySelector('#dobInput');
            if (dobField) {
                const dobValue = dobField.value.trim();
                if (!dobValue || dobValue.length !== 10) {
                    isValid = false;
                    dobField.classList.add('is-invalid');
                    if (firstInvalidField.length === 0) {
                        firstInvalidField.push(dobField);
                    }
                } else {
                    const parts = dobValue.split('/');
                    if (parts.length !== 3) {
                        isValid = false;
                        dobField.classList.add('is-invalid');
                        if (firstInvalidField.length === 0) {
                            firstInvalidField.push(dobField);
                        }
                    } else {
                        const month = parseInt(parts[0], 10);
                        const day = parseInt(parts[1], 10);
                        const year = parseInt(parts[2], 10);
                        
                        // Validate ranges
                        if (month < 1 || month > 12 || day < 1 || day > 31 || year < 1900 || year > 2100) {
                            isValid = false;
                            dobField.classList.add('is-invalid');
                            if (firstInvalidField.length === 0) {
                                firstInvalidField.push(dobField);
                            }
                        } else {
                            // Check if date is valid (e.g., not Feb 30)
                            const date = new Date(year, month - 1, day);
                            if (date.getMonth() !== month - 1 || date.getDate() !== day || date.getFullYear() !== year) {
                                isValid = false;
                                dobField.classList.add('is-invalid');
                                if (firstInvalidField.length === 0) {
                                    firstInvalidField.push(dobField);
                                }
                            } else {
                                dobField.classList.remove('is-invalid');
                            }
                        }
                    }
                }
            }
        }

        // Special validation for step 3 - at least one assistance type must be selected (only for charity form)
        if (stepIndex === 2) {
            const formTypeInput = document.querySelector('input[name="form_type"]');
            const formType = formTypeInput ? formTypeInput.value : 'charity';
            
            if (formType === 'charity') {
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
        }

        // Special validation for step 4 - consent checkbox and SSN format
        if (stepIndex === 3) {
            const consentCheckbox = currentStep.querySelector('#consentCheck');
            if (consentCheckbox && !consentCheckbox.checked) {
                isValid = false;
                consentCheckbox.classList.add('is-invalid');
                if (firstInvalidField.length === 0) {
                    firstInvalidField.push(consentCheckbox);
                }
            }
            
            // Validate SSN format (must be exactly 9 digits)
            const ssnField = currentStep.querySelector('#ssnInput');
            if (ssnField) {
                const ssnValue = ssnField.value.replace(/\D/g, ''); // Get digits only
                if (ssnValue.length !== 9) {
                    isValid = false;
                    ssnField.classList.add('is-invalid');
                    if (firstInvalidField.length === 0) {
                        firstInvalidField.push(ssnField);
                    }
                } else {
                    ssnField.classList.remove('is-invalid');
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
                
                // Show alert with specific message for SSN or DOB if needed
                let alertMessage = 'Please fill in all required fields before proceeding.';
                if (firstInvalidField[0] && firstInvalidField[0].id === 'ssnInput') {
                    alertMessage = 'Please enter a valid SSN in the format XXX-XX-XXXX (9 digits).';
                } else if (firstInvalidField[0] && firstInvalidField[0].id === 'dobInput') {
                    alertMessage = 'Please enter a valid date in the format mm/dd/yyyy (e.g., 10/06/2002).';
                }
                setTimeout(() => {
                    alert(alertMessage);
                }, 100);
            }
            
            return false;
        }

        return true;
    };

    const setStep = (index) => {
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
            if (current < steps.length - 1) {
                current += 1;
                setStep(current);
            }
    });

    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

            if (current > 0) {
                current -= 1;
                setStep(current);
            }
    });

    // Check for validation errors and navigate to the step with errors
    const findStepWithErrors = () => {
        // Step 1 fields
        const step1Fields = ['first_name', 'last_name', 'email', 'phone', 'dob'];
        // Step 2 fields
        const step2Fields = ['household_size', 'dependents', 'employment_status', 'employer_name', 'family_members'];
        // Step 3 fields
        const step3Fields = ['assistance_amount', 'assistance_types', 'assistance_description', 'loan_amount', 'loan_purpose', 'loan_term', 'grant_amount', 'grant_category', 'grant_purpose'];
        // Step 4 fields
        const step4Fields = ['ssn', 'street', 'city', 'state', 'zip', 'consent'];
        
        // Check for error fields in DOM
        const errorFields = document.querySelectorAll('.is-invalid');
        if (errorFields.length > 0) {
            for (let field of errorFields) {
                const fieldName = field.name || field.id || '';
                const baseFieldName = fieldName.split('[')[0].split('.')[0];
                
                if (step1Fields.includes(baseFieldName)) {
                    return 0;
                } else if (step2Fields.includes(baseFieldName) || fieldName.includes('family_members')) {
                    return 1;
                } else if (step3Fields.includes(baseFieldName)) {
                    return 2;
                } else if (step4Fields.includes(baseFieldName)) {
                    return 3;
                }
            }
        }
        
        // Check error messages for field names
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        for (let msg of errorMessages) {
            const field = msg.previousElementSibling || msg.closest('.form-control') || msg.parentElement.querySelector('input, select, textarea');
            if (field) {
                const fieldName = field.name || field.id || '';
                const baseFieldName = fieldName.split('[')[0].split('.')[0];
                
                if (step1Fields.includes(baseFieldName)) {
                    return 0;
                } else if (step2Fields.includes(baseFieldName) || fieldName.includes('family_members')) {
                    return 1;
                } else if (step3Fields.includes(baseFieldName)) {
                    return 2;
                } else if (step4Fields.includes(baseFieldName)) {
                    return 3;
                }
            }
        }
        
        // Check each step for error fields
        for (let i = 0; i < steps.length; i++) {
            const stepFields = steps[i].querySelectorAll('.is-invalid, .invalid-feedback');
            if (stepFields.length > 0) {
                return i;
            }
        }
        
        return -1;
    };

    // Navigate to step with errors on page load ONLY if there are actual server-side validation errors
    // Always start at step 0 (first step) for new form submissions
    @if($errors->any())
        const errorStep = findStepWithErrors();
        if (errorStep >= 0 && errorStep < steps.length) {
            current = errorStep;
        } else {
            // If error detection fails, still start at step 0
            current = 0;
        }
    @else
        // No validation errors, always start at step 0
        current = 0;
    @endif

    // Ensure we're on a valid step
    if (current < 0 || current >= steps.length) {
        current = 0;
    }

    // Force show step 1 and hide all others before setting step
    steps.forEach((step, i) => {
        if (i === 0) {
            step.classList.remove('d-none');
        } else {
            step.classList.add('d-none');
        }
    });

    setStep(current);

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
        
        // Replace current form page in history with Thank You page URL
        // This completely removes form from history before submission
        const thankyouUrl = '{{ route("public.thankyou", ["id" => "pending"]) }}';
        if (window.history && window.history.replaceState) {
            window.history.replaceState({ page: 'thankyou-pending' }, 'Thank You', thankyouUrl);
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
        
        // Sync date format before submission
        const dobInput = document.getElementById('dobInput');
        const dobDatePicker = document.getElementById('dobDatePicker');
        if (dobInput && dobDatePicker && dobInput.value) {
            const value = dobInput.value.trim();
            if (value.length === 10) {
                const parts = value.split('/');
                if (parts.length === 3) {
                    const month = String(parseInt(parts[0], 10)).padStart(2, '0');
                    const day = String(parseInt(parts[1], 10)).padStart(2, '0');
                    const year = parts[2];
                    dobDatePicker.value = year + '-' + month + '-' + day;
                }
            }
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
    const loanAmountRange = document.getElementById('loanAmountRange');
    const loanAmountValue = document.getElementById('loanAmountValue');
    const grantAmountRange = document.getElementById('grantAmountRange');
    const grantAmountValue = document.getElementById('grantAmountValue');

    const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(val);
    incomeRange?.addEventListener('input', e => incomeValue.textContent = formatCurrency(e.target.value));
    amountRange?.addEventListener('input', e => amountValue.textContent = formatCurrency(e.target.value));
    loanAmountRange?.addEventListener('input', e => loanAmountValue.textContent = formatCurrency(e.target.value));
    grantAmountRange?.addEventListener('input', e => grantAmountValue.textContent = formatCurrency(e.target.value));
    
    // Initialize range displays with old values or defaults
    if (incomeRange) {
        incomeRange.value = incomeRange.value || {{ old('monthly_income', 0) }};
        incomeValue.textContent = formatCurrency(incomeRange.value);
    }
    if (amountRange) {
        amountRange.value = amountRange.value || {{ old('assistance_amount', 2000) }};
        amountValue.textContent = formatCurrency(amountRange.value);
    }
    if (loanAmountRange) {
        loanAmountRange.value = loanAmountRange.value || {{ old('loan_amount', 5000) }};
        loanAmountValue.textContent = formatCurrency(loanAmountRange.value);
    }
    if (grantAmountRange) {
        grantAmountRange.value = grantAmountRange.value || {{ old('grant_amount', 3000) }};
        grantAmountValue.textContent = formatCurrency(grantAmountRange.value);
    }

    // Family members dynamic rows
    const familyWrapper = document.getElementById('familyMembers');
    const addBtn = document.getElementById('addFamilyMember');
    let memberIndex = 0;

    const addFamilyMemberRow = (memberData = null) => {
        const row = document.createElement('div');
        row.className = 'col-12';
        const nameValue = memberData && memberData.name ? memberData.name : '';
        const ageValue = memberData && memberData.age !== undefined ? memberData.age : '';
        const relationshipValue = memberData && memberData.relationship ? memberData.relationship : '';
        row.innerHTML = `
            <div class="row g-2 align-items-center border rounded p-3 bg-light">
                <div class="col-md-4">
                    <input type="text" name="family_members[${memberIndex}][name]" class="form-control @error('family_members.${memberIndex}.name') is-invalid @enderror" placeholder="Full Name" value="${nameValue}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="family_members[${memberIndex}][age]" class="form-control @error('family_members.${memberIndex}.age') is-invalid @enderror" placeholder="Age" min="0" max="120" value="${ageValue}" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="family_members[${memberIndex}][relationship]" class="form-control @error('family_members.${memberIndex}.relationship') is-invalid @enderror" placeholder="Relationship" value="${relationshipValue}" required>
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

    addBtn.addEventListener('click', () => addFamilyMemberRow());
    familyWrapper.addEventListener('click', (e) => {
        if (e.target.closest('.remove-member')) {
            e.target.closest('.col-12').remove();
        }
    });
    
    // Restore family members from old input if validation failed
    const oldFamilyMembers = @json(old('family_members', []));
    if (oldFamilyMembers && oldFamilyMembers.length > 0) {
        oldFamilyMembers.forEach(member => {
            addFamilyMemberRow(member);
        });
    } else {
        addFamilyMemberRow();
    }
    
    // 3D Assistance Type Buttons - Handle clicks and toggle selection
    document.querySelectorAll('.assistance-type-btn-3d').forEach(button => {
        const checkbox = button.querySelector('.assistance-type-checkbox');
        const isChecked = button.getAttribute('data-checked') === 'true';
        
        // Set initial state
        if (isChecked) {
            button.classList.add('selected');
            if (checkbox) checkbox.checked = true;
        }
        
        // Handle button click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Toggle selected class
                if (checkbox.checked) {
                    button.classList.add('selected');
                } else {
                    button.classList.remove('selected');
                }
                
                // Clear error state when any button is selected
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

    // SSN Formatting and Validation
    const ssnInput = document.getElementById('ssnInput');
    if (ssnInput) {
        // Format existing value on load if it exists
        if (ssnInput.value && !ssnInput.value.match(/^\d{3}-\d{2}-\d{4}$/)) {
            let value = ssnInput.value.replace(/\D/g, '');
            if (value.length === 9) {
                ssnInput.value = value.substring(0, 3) + '-' + value.substring(3, 5) + '-' + value.substring(5);
            }
        }
        
        // Format SSN as user types (XXX-XX-XXXX)
        ssnInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits
            
            // Limit to 9 digits
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            
            // Format with dashes
            if (value.length > 3) {
                value = value.substring(0, 3) + '-' + value.substring(3);
            }
            if (value.length > 6) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            
            e.target.value = value;
        });
        
        // Prevent pasting invalid characters
        ssnInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const digitsOnly = pastedText.replace(/\D/g, '').substring(0, 9);
            
            // Format the pasted value
            let formatted = digitsOnly;
            if (formatted.length > 3) {
                formatted = formatted.substring(0, 3) + '-' + formatted.substring(3);
            }
            if (formatted.length > 6) {
                formatted = formatted.substring(0, 6) + '-' + formatted.substring(6);
            }
            
            this.value = formatted;
            this.dispatchEvent(new Event('input'));
        });
        
        // Validate SSN format (XXX-XX-XXXX = 11 characters with dashes, or 9 digits)
        ssnInput.addEventListener('blur', function() {
            const value = this.value.replace(/\D/g, ''); // Get digits only
            if (value.length !== 9) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    

    // Date of Birth Input Formatting and Calendar Picker
    const dobInput = document.getElementById('dobInput');
    const dobDatePicker = document.getElementById('dobDatePicker');
    const dobCalendarBtn = document.getElementById('dobCalendarBtn');
    
    if (dobInput && dobDatePicker && dobCalendarBtn) {
        // Format date as user types (mm/dd/yyyy)
        dobInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits
            
            // Limit to 8 digits (mmddyyyy)
            if (value.length > 8) {
                value = value.substring(0, 8);
            }
            
            // Format with slashes
            let formatted = value;
            if (value.length > 2) {
                formatted = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length > 4) {
                formatted = value.substring(0, 2) + '/' + value.substring(2, 4) + '/' + value.substring(4);
            }
            
            e.target.value = formatted;

            // Update hidden date picker if valid date
            if (value.length === 8) {
                const month = value.substring(0, 2);
                const day = value.substring(2, 4);
                const year = value.substring(4, 8);

                // Validate month (01-12)
                if (month >= '01' && month <= '12') {
                    // Validate day (01-31)
                    if (day >= '01' && day <= '31') {
                        // Validate year (reasonable range)
                        if (year >= '1900' && year <= '2100') {
                            const dateStr = year + '-' + month + '-' + day;
                            dobDatePicker.value = dateStr;
            }
                    }
                }
            }
        });

        // Handle paste event
        dobInput.addEventListener('paste', function(e) {
                e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const digitsOnly = pastedText.replace(/\D/g, '').substring(0, 8);
            
            // Format the pasted value
            let formatted = digitsOnly;
            if (digitsOnly.length > 2) {
                formatted = digitsOnly.substring(0, 2) + '/' + digitsOnly.substring(2);
            }
            if (digitsOnly.length > 4) {
                formatted = digitsOnly.substring(0, 2) + '/' + digitsOnly.substring(2, 4) + '/' + digitsOnly.substring(4);
            }
            
            this.value = formatted;
            this.dispatchEvent(new Event('input'));
    });
    
        // Open calendar picker when button is clicked
        dobCalendarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Position date picker over the button temporarily
            const rect = dobCalendarBtn.getBoundingClientRect();
            dobDatePicker.style.position = 'fixed';
            dobDatePicker.style.left = rect.left + 'px';
            dobDatePicker.style.top = rect.top + 'px';
            dobDatePicker.style.width = rect.width + 'px';
            dobDatePicker.style.height = rect.height + 'px';
            dobDatePicker.style.opacity = '0';
            dobDatePicker.style.pointerEvents = 'auto';
            dobDatePicker.style.zIndex = '9999';
            
            // Try modern showPicker() method, fallback to focus/click for older browsers
            if (dobDatePicker.showPicker) {
                dobDatePicker.showPicker().catch(() => {
                    // Fallback if showPicker fails
                    dobDatePicker.focus();
                    dobDatePicker.click();
                });
            } else {
                // Fallback for older browsers
                dobDatePicker.focus();
                dobDatePicker.click();
                }
            
            // Reset styles after a delay
                setTimeout(() => {
                dobDatePicker.style.position = 'absolute';
                dobDatePicker.style.left = 'auto';
                dobDatePicker.style.top = 'auto';
                dobDatePicker.style.width = '0';
                dobDatePicker.style.height = '0';
                dobDatePicker.style.pointerEvents = 'none';
            }, 100);
            });
    
        // When date is selected from calendar, update text input
        dobDatePicker.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();
                dobInput.value = month + '/' + day + '/' + year;
            }
        });
        
        // Validate date format on blur
        dobInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && value.length === 10) {
                const parts = value.split('/');
                if (parts.length === 3) {
                    const month = parseInt(parts[0], 10);
                    const day = parseInt(parts[1], 10);
                    const year = parseInt(parts[2], 10);
                    
                    // Validate ranges
                    if (month < 1 || month > 12 || day < 1 || day > 31 || year < 1900 || year > 2100) {
                        this.classList.add('is-invalid');
                    } else {
                        // Check if date is valid (e.g., not Feb 30)
                        const date = new Date(year, month - 1, day);
                        if (date.getMonth() !== month - 1 || date.getDate() !== day || date.getFullYear() !== year) {
                            this.classList.add('is-invalid');
                        } else {
                            this.classList.remove('is-invalid');
                            // Update hidden date picker
                            const dateStr = year + '-' + String(month).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                            dobDatePicker.value = dateStr;
                        }
                    }
                } else {
                    this.classList.add('is-invalid');
                }
            } else if (value && value.length > 0) {
                this.classList.add('is-invalid');
            }
        });
        
        // Remove invalid class when user starts typing
        dobInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
    });
    
        // Convert existing value to mm/dd/yyyy format if it exists
        if (dobInput.value && dobDatePicker.value) {
            const date = new Date(dobDatePicker.value);
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            dobInput.value = month + '/' + day + '/' + year;
        }
    }
});
</script>
</body>
</html>
