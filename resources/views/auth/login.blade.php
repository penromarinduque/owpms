@extends('layouts.auth')

@section('content')
<div class="card shadow-lg border-1 rounded-lg mt-1">
    <div class="card-header"><h3 class="text-center font-weight-light my-4"><i class="fas fa-lock"></i> Login</h3></div>
    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.store') }}" onsubmit="disableSubmitBtn('btn_login');">
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control" name="username" id="username" type="text" placeholder="Username" value="{{ old('username') }}" autocomplete="off" />
                <label for="username">Username</label>
                @error('username')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" name="password" id="password" type="password" placeholder="Password" />
                <label for="password">Password</label>
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" id="showpass" onclick="showPasswords('showpass', ['password']);">
                <label class="form-check-label" for="showpass">Show Password</label>
            </div>
            <a class="" href="{{ route('password.request') }}">Forgot Password?</a><br>
            <div class="d-flex align-items-center justify-content-between mt-1 mb-0">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="termsCheck" required
                        oninvalid="this.setCustomValidity('Please read and agree to the Terms and Conditions to proceed.')"
                        oninput="this.setCustomValidity('')">
                    <label class="form-check-label" for="termsCheck">
                        I have read and agree to the 
                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-decoration-none fw-semibold">
                            Terms and Conditions
                        </a>
                    </label>
                </div>
            </div>
            <button type="submit" name="btn_login" id="btn_login" class="btn btn-primary"><i class="fas fa-sign-in"></i> Login</button>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">Online Wildlife Permitting and Management System</div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content shadow">
                
                <div class="modal-header ">
                    <div class="modal-title" id="termsModalLabel">
                        <h5 class="m-0 fw-bold fs-6 text-uppercase">Terms and Conditions on Accessing the OWPMS</h5>
                        <small class="d-block ">DENR-PENRO Marinduque</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-dark" style="font-size: 0.9rem; line-height: 1.6;">
                    
                    <!-- Confirmation Notice Callout -->
                    <div class="alert alert-success bg-light text-dark mb-4">
                        <p class="mb-2">By clicking <strong>“I AGREE,” “ACCEPT,” “REGISTER,” “LOGIN,”</strong> or otherwise accessing or using the OWPMS, the User confirms that:</p>
                        <p class="fst-italic mb-0 text-secondary">
                            "I have read, understood, and agree to comply with these Terms and Conditions on Accessing and Using the OWPMS. I understand that my account is personal to me, that I am responsible for maintaining the confidentiality of my credentials, and that activities performed using my account may be recorded and audited in accordance with applicable laws and DENR policies."
                        </p>
                    </div>

                    <p class="fw-medium text-muted mb-4">
                        By registering for, accessing, or using the Online Wildlife Permitting and Management System (OWPMS) of DENR-PENRO Marinduque, the User acknowledges and agrees to comply with the following Terms and Conditions:
                    </p>

                    <!-- Terms List -->
                    <div class="vstack gap-3">
                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">1. Authorized Access</h6>
                            <p class="mb-1">Access to the OWPMS is granted only to duly registered applicants/permittees and authorized DENR personnel, according to their respective roles and level of access.</p>
                            <p class="mb-0">Access shall be limited to the functions and information necessary for the performance of the User's authorized activities. The OWPMS uses role-based access controls to ensure that users can access only the functions appropriate to their assigned role.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">2. User Account</h6>
                            <p class="mb-1">The User shall provide complete, accurate, and truthful information during registration and shall maintain the accuracy of the information associated with the account.</p>
                            <p class="mb-0">Each User shall maintain only his/her authorized account and shall not create, use, or maintain an account under another person's identity.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">3. Username and Password</h6>
                            <p class="mb-2">The User shall keep his/her username, password, authentication credentials, and other account credentials strictly confidential.</p>
                            <p class="mb-1">The User shall:</p>
                            <ul class="mb-2 ps-3">
                                <li>not disclose or share account credentials with any other person;</li>
                                <li>not allow another person to use his/her account;</li>
                                <li>use only his/her assigned account;</li>
                                <li>immediately report any suspected unauthorized access or compromise of credentials; and</li>
                                <li>take reasonable measures to protect the security of the account.</li>
                            </ul>
                            <p class="mb-0">Every action performed using an assigned account may be attributed to the registered User, subject to applicable investigation and audit procedures.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">4. Proper Use of the System</h6>
                            <p class="mb-1">The OWPMS shall be used only for its intended and authorized purposes, including the submission, processing, monitoring, inspection, assessment, payment, approval, issuance, release, and records management of covered wildlife permitting transactions.</p>
                            <p class="mb-0">The User shall not use the system for any unlawful, unauthorized, fraudulent, malicious, or unrelated purpose.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">5. Accuracy and Authenticity of Information</h6>
                            <p class="mb-1">The User shall ensure that all information, documents, photographs, signatures, and other materials submitted, uploaded, entered, or processed through the OWPMS are accurate, complete, authentic, and not misleading.</p>
                            <p class="mb-0">The User shall be responsible for the accuracy and authenticity of information submitted through his/her account.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">6. Prohibited Access and Activities</h6>
                            <p class="mb-1">The User shall not:</p>
                            <ol class="mb-0 ps-3">
                                <li>Access another user's account without authorization;</li>
                                <li>Attempt to obtain or use another person's credentials;</li>
                                <li>Circumvent or defeat system security or access controls;</li>
                                <li>Access functions or information outside the User's authorized role;</li>
                                <li>Alter, delete, manipulate, or interfere with electronic records or system data without authority;</li>
                                <li>Upload malicious software, harmful files, or unauthorized content;</li>
                                <li>Falsify or manipulate documents, photographs, signatures, or other information;</li>
                                <li>Use another person's electronic or digital signature without proper authority;</li>
                                <li>Attempt to disrupt, damage, overload, or compromise the OWPMS; or</li>
                                <li>Engage in any activity that violates applicable laws, DENR policies, information-security rules, or data-privacy requirements.</li>
                            </ol>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">7. Data Privacy and Confidentiality</h6>
                            <p class="mb-1">The User acknowledges that information submitted through the OWPMS may include personal and other protected information.</p>
                            <p class="mb-0">The User shall comply with Republic Act No. 10173 (Data Privacy Act of 2012) and applicable National Privacy Commission issuances and shall not access, copy, disclose, reproduce, or use personal or confidential information except as authorized by law or by the User's assigned functions. The OWPMS policy expressly requires compliance with data-privacy and cybersecurity requirements.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">8. Electronic Documents and Signatures</h6>
                            <p class="mb-1">Where applicable, the User agrees that electronic documents, electronic records, and authorized electronic or digital signatures processed through the OWPMS may form part of the official transaction record, subject to applicable law and DENR policies.</p>
                            <p class="mb-0">The User shall not share, reproduce, misuse, or allow unauthorized use of his/her electronic or digital signature.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">9. System Monitoring and Audit Trail</h6>
                            <p class="mb-1">The User acknowledges that material activities performed through the OWPMS may be recorded in system logs or audit trails, including application submission, review, payment, inspection, signing, approval, issuance, and release.</p>
                            <p class="mb-0">Such records may be used for accountability, administrative review, internal control, compliance monitoring, investigation, security, and other lawful purposes.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">10. System Availability</h6>
                            <p class="mb-1">The OWPMS is intended to provide continuous access subject to system maintenance, technical limitations, security measures, connectivity, and other circumstances beyond the control of DENR-PENRO Marinduque.</p>
                            <p class="mb-1">Temporary unavailability of the system shall not constitute a waiver of any legal, documentary, inspection, payment, or approval requirement.</p>
                            <p class="mb-0">Where necessary, DENR-PENRO Marinduque may implement an authorized alternative or hybrid procedure during system downtime. Transactions processed through an authorized alternative procedure shall be appropriately recorded or reconciled in the OWPMS upon restoration.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">11. Suspension or Revocation of Access</h6>
                            <p class="mb-1">DENR-PENRO Marinduque may suspend, restrict, or revoke access to an OWPMS account when warranted by:</p>
                            <ul class="mb-2 ps-3">
                                <li>violation of these Terms and Conditions;</li>
                                <li>unauthorized or suspicious access;</li>
                                <li>compromise of account credentials;</li>
                                <li>misuse of the system;</li>
                                <li>security or privacy concerns;</li>
                                <li>separation, reassignment, or change in official function of a DENR user; or</li>
                                <li>other circumstances authorized by law or applicable DENR policies.</li>
                            </ul>
                            <p class="mb-0">For DENR personnel, access shall be based on their authorized role and may be modified upon reassignment or change of functions. The System Administrator is responsible for account and role management and system security coordination.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">12. No Transfer of Legal Authority</h6>
                            <p class="mb-1">Access to the OWPMS does not confer any authority to approve, issue, sign, release, or otherwise act upon a wildlife permit beyond the authority legally or administratively assigned to the User.</p>
                            <p class="mb-0">The system merely facilitates and documents the authorized workflow. It does not create, modify, enlarge, diminish, or transfer substantive legal authority.</p>
                        </section>

                        <section>
                            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">13. Compliance with Laws and DENR Policies</h6>
                            <p class="mb-1">All Users shall comply with applicable wildlife laws and regulations, DENR issuances, the OWPMS Policy Guidelines, data-privacy requirements, information-security policies, records-management rules, and other applicable government policies.</p>
                            <p class="mb-0">In case of conflict, the applicable law, subsequent DENR issuance, or directive of a competent authority shall prevail.</p>
                        </section>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn-sm px-4" onclick="acceptTerms()">I Agree & Accept</button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    function disableSubmitBtn(btn) {
        document.getElementById(btn).disabled = true;
        document.getElementById(btn).innerHTML = '<i class="fas fa-sign-in"></i>  Logging in...';
    }
</script>
<script>
function acceptTerms() {
    const termsCheck = document.getElementById('termsCheck');
    termsCheck.checked = true;
    termsCheck.setCustomValidity(''); // Clear custom validation message
    
    // Close modal using Bootstrap 5 API
    const modalEl = document.getElementById('termsModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) {
        modal.hide();
    }
}
</script>
@endsection