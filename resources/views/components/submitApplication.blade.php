<div class="modal fade" id="submitApplicationModal">
    <div class="modal-dialog modal-lg">
        <form action="{{ session('forward_url') ? session('forward_url') : '' }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Submit Application</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <p id="message"></p> --}}
                <div class="" style="height: 400px; overflow-y: scroll;">
                    <style>
                        .privacy-notice {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            line-height: 1.7;
                            color: #212529;
                            text-align: justify;
                        }

                        .privacy-notice h3 {
                            font-size: 20px;
                            font-weight: bold;
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        .privacy-notice .intro {
                            margin-bottom: 20px;
                        }

                        .privacy-notice h5 {
                            font-size: 15px;
                            font-weight: bold;
                            margin-top: 22px;
                            margin-bottom: 8px;
                            text-align: left;
                        }

                        .privacy-notice ul {
                            padding-left: 25px;
                            margin-top: 5px;
                            margin-bottom: 12px;
                        }

                        .privacy-notice li {
                            margin-bottom: 3px;
                        }

                        .privacy-notice p {
                            margin-bottom: 10px;
                        }

                        .privacy-notice .notice-footer {
                            margin-top: 20px;
                            font-style: italic;
                        }
                    </style>

                    <div class="privacy-notice">

                        <h3>APPLICANT/PERMITTEE DATA PRIVACY NOTICE AND USER UNDERTAKING</h3>

                        <div class="intro">
                            <p>
                                Please read this Privacy Notice and User Undertaking carefully before
                                proceeding with your OWPMS registration or application.
                            </p>

                            <p>
                                The Department of Environment and Natural Resources – Provincial
                                Environment and Natural Resources Office of Marinduque (DENR-PENRO
                                Marinduque), through the Online Wildlife Permitting and Management
                                System (OWPMS), collects and processes information necessary for the
                                registration, evaluation, processing, monitoring, approval, issuance,
                                and management of wildlife permits and related regulatory transactions.
                            </p>

                            <p>
                                By proceeding with your OWPMS registration or application, you
                                acknowledge that you have been informed about the processing of your
                                information and agree to comply with the responsibilities applicable
                                to your use of the system.
                            </p>
                        </div>

                        <h5>1. WHO PROCESSES YOUR INFORMATION?</h5>

                        <p>The responsible office for the processing of information through OWPMS is:</p>

                        <p>
                            <strong>Department of Environment and Natural Resources</strong><br>
                            Provincial Environment and Natural Resources Office – Marinduque
                        </p>

                        <p>
                            The system may be administered by authorized DENR personnel and authorized
                            technical personnel acting within their assigned functions.
                        </p>

                        <p>
                            For privacy-related concerns, the applicant may contact the appropriate
                            DENR-PENRO Marinduque office or designated privacy/data protection personnel.
                        </p>

                        <h5>2. WHAT INFORMATION MAY BE COLLECTED?</h5>

                        <p>Depending on your transaction, OWPMS may collect and process:</p>

                        <ul>
                            <li>Full name and contact information;</li>
                            <li>Address and email address;</li>
                            <li>Identification information when required;</li>
                            <li>Organization, company, institution, or agency affiliation;</li>
                            <li>Information regarding your application or permit;</li>
                            <li>Wildlife/specimen information;</li>
                            <li>Source, destination, transport, and purpose of the transaction;</li>
                            <li>Supporting documents uploaded to the system;</li>
                            <li>Inspection and verification information;</li>
                            <li>Photographs and other inspection-related records;</li>
                            <li>Assessment and payment information;</li>
                            <li>Electronic or digital signature information, where applicable;</li>
                            <li>Application status and transaction history;</li>
                            <li>OWPMS account information; and</li>
                            <li>System-generated audit and security records.</li>
                        </ul>

                        <p>
                            Only information necessary and relevant to the particular transaction
                            shall be collected and processed.
                        </p>

                        <h5>3. WHY DO WE PROCESS YOUR INFORMATION?</h5>

                        <p>Your information may be processed for legitimate government purposes, including:</p>

                        <ul>
                            <li>Registration and authentication of OWPMS users;</li>
                            <li>Processing and evaluation of wildlife permit applications;</li>
                            <li>Documentary evaluation and verification;</li>
                            <li>Inspection and validation;</li>
                            <li>Assessment and payment monitoring;</li>
                            <li>Review and approval;</li>
                            <li>Electronic signing and issuance of permits;</li>
                            <li>Permit release and downloading;</li>
                            <li>Regulatory and compliance monitoring;</li>
                            <li>Wildlife transport monitoring;</li>
                            <li>Records management;</li>
                            <li>Audit and accountability;</li>
                            <li>Government reporting and statistical purposes;</li>
                            <li>System administration and security;</li>
                            <li>Prevention and investigation of fraud or unauthorized transactions;</li>
                            <li>Enforcement and investigation when authorized by law; and</li>
                            <li>Compliance with applicable laws, regulations, and DENR policies.</li>
                        </ul>

                        <h5>4. HOW WILL YOUR INFORMATION BE PROTECTED?</h5>

                        <p>
                            DENR-PENRO Marinduque shall implement reasonable and appropriate
                            organizational, physical, and technical safeguards for information
                            processed through OWPMS.
                        </p>

                        <p>These may include:</p>

                        <ul>
                            <li>User authentication;</li>
                            <li>Role-based access controls;</li>
                            <li>Individual user accounts;</li>
                            <li>Secure transmission and storage;</li>
                            <li>Audit logs;</li>
                            <li>Access monitoring;</li>
                            <li>Backup and recovery mechanisms;</li>
                            <li>Electronic/digital signature controls;</li>
                            <li>Security monitoring; and</li>
                            <li>Incident response procedures.</li>
                        </ul>

                        <p>
                            Access to information shall be limited to authorized persons who require
                            it for their official functions.
                        </p>

                        <h5>5. WILL YOUR INFORMATION BE SHARED?</h5>

                        <p>
                            Your information may be disclosed or shared when legally authorized or
                            required, including with:
                        </p>

                        <ul>
                            <li>Authorized DENR offices and personnel;</li>
                            <li>Other government agencies performing lawful regulatory, enforcement,
                                monitoring, or related functions;</li>
                            <li>Authorized inspection, audit, or investigative personnel;</li>
                            <li>Courts or law enforcement authorities when legally required or authorized; and</li>
                            <li>Other authorized recipients when necessary for a lawful government function.</li>
                        </ul>

                        <p>
                            Only information necessary and proportionate to the authorized purpose
                            shall be disclosed.
                        </p>

                        <p>
                            Where appropriate, inter-agency data sharing may be governed by a separate
                            Data Sharing Agreement (DSA) or other appropriate legal or administrative arrangement.
                        </p>

                        <h5>6. PUBLIC VERIFICATION OF PERMITS</h5>

                        <p>
                            OWPMS may provide an online facility for verifying the authenticity,
                            validity, or status of permits or related documents.
                        </p>

                        <p>
                            Only information necessary for verification shall be made publicly
                            accessible. Personal, confidential, restricted, or security-sensitive
                            information shall not be unnecessarily displayed.
                        </p>

                        <h5>7. HOW LONG WILL YOUR INFORMATION BE RETAINED?</h5>

                        <p>
                            Information may be retained for as long as necessary for the purposes
                            for which it was collected and processed and in accordance with applicable
                            laws, DENR records management requirements, government records retention
                            schedules, audit requirements, legal proceedings, enforcement requirements,
                            and other lawful government purposes.
                        </p>

                        <p>
                            Completion, expiration, or cancellation of a permit does not necessarily
                            result in the immediate deletion of information forming part of an
                            official government record.
                        </p>

                        <h5>8. YOUR RIGHTS AS A DATA SUBJECT</h5>

                        <p>
                            Subject to applicable laws and limitations, you may exercise applicable
                            rights under the Data Privacy Act of 2012, including the right:
                        </p>

                        <ul>
                            <li>To be informed about the processing of your information;</li>
                            <li>To access your personal information, subject to applicable limitations;</li>
                            <li>To request correction of inaccurate information;</li>
                            <li>To object to processing where legally applicable;</li>
                            <li>To request deletion or blocking where legally applicable; and</li>
                            <li>To raise a privacy concern or complaint through the appropriate channels.</li>
                        </ul>

                        <p>
                            The exercise of these rights shall be subject to lawful limitations,
                            particularly where DENR is required to retain or process information
                            for official government records, regulatory, audit, enforcement, or
                            other lawful purposes.
                        </p>

                        <h5>9. YOUR RESPONSIBILITIES AS AN OWPMS USER</h5>

                        <p>As an applicant or permittee, you agree to:</p>

                        <ul>
                            <li>Provide true, accurate, complete, and current information;</li>
                            <li>Submit authentic and valid documents;</li>
                            <li>Use only your authorized OWPMS account;</li>
                            <li>Keep your account credentials confidential;</li>
                            <li>Not allow unauthorized persons to use your account;</li>
                            <li>Immediately report suspected unauthorized access or account compromise;</li>
                            <li>Review your information before submitting an application;</li>
                            <li>Comply with OWPMS procedures and DENR requirements; and</li>
                            <li>Use OWPMS only for lawful and authorized purposes.</li>
                        </ul>

                        <h5>10. PROHIBITED USE OF THE SYSTEM</h5>

                        <p>Users shall not:</p>

                        <ul>
                            <li>Use another person's account;</li>
                            <li>Share account credentials with unauthorized persons;</li>
                            <li>Submit false, fraudulent, altered, or misleading information;</li>
                            <li>Attempt unauthorized access to OWPMS;</li>
                            <li>Manipulate or delete official system records without authority;</li>
                            <li>Circumvent system security controls;</li>
                            <li>Access or misuse another person's information;</li>
                            <li>Introduce malicious software or interfere with system operations; or</li>
                            <li>Commit any other act prohibited by law, DENR policy, or OWPMS rules.</li>
                        </ul>

                        <p>
                            Violation may result in account suspension or termination and may be
                            subject to administrative, civil, or criminal liability, as applicable.
                        </p>

                        <h5>11. ELECTRONIC TRANSACTIONS</h5>

                        <p>
                            OWPMS may facilitate electronic submission, review, notification,
                            approval, issuance, and release of documents.
                        </p>

                        <p>
                            Where electronic or digital signatures are used, applicable DENR
                            policies and legal requirements shall apply.
                        </p>

                        <p>
                            The system may maintain an electronic audit trail of relevant activities,
                            including submissions, modifications, approvals, electronic signatures,
                            downloads, and other transactions.
                        </p>

                        <h5>12. IMPORTANT NOTICE</h5>

                        <p>
                            Submission of an OWPMS application does not guarantee approval or
                            issuance of a permit.
                        </p>

                        <p>
                            The applicant must comply with all applicable documentary, inspection,
                            assessment, payment, verification, and other regulatory requirements.
                        </p>

                        <p class="notice-footer">
                            This Privacy Notice and User Undertaking is separate from any Data
                            Sharing Agreement (DSA) that may be entered into between DENR-PENRO
                            Marinduque and another government agency or authorized entity.
                        </p>

                    </div>

                    <div class="owpms-acknowledgment">

                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="privacy_read"
                                name="privacy_read"
                                value="1"
                                required
                                onchange="checkAcceptance()"
                            >
                            <label class="form-check-label fw-bold" for="privacy_read">
                                I HAVE READ AND UNDERSTOOD THE OWPMS APPLICANT/PERMITTEE DATA PRIVACY NOTICE AND USER UNDERTAKING.
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="privacy_acknowledgment"
                                name="privacy_acknowledgment"
                                value="1"
                                required
                                onchange="checkAcceptance()"
                            >
                            <label class="form-check-label fw-bold" for="privacy_acknowledgment">
                                I ACKNOWLEDGE THAT DENR-PENRO MARINDUQUE MAY COLLECT, PROCESS, RETAIN, AND, WHEN LAWFULLY AUTHORIZED, DISCLOSE OR SHARE MY INFORMATION FOR THE LEGITIMATE GOVERNMENT PURPOSES DESCRIBED ABOVE.
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="user_responsibilities"
                                name="user_responsibilities"
                                value="1"
                                required
                                onchange="checkAcceptance()"
                            >
                            <label class="form-check-label fw-bold" for="user_responsibilities">
                                I AGREE TO PROVIDE TRUE, ACCURATE, COMPLETE, AND AUTHENTIC INFORMATION AND TO COMPLY WITH THE OWPMS USER RESPONSIBILITIES, APPLICABLE DENR POLICIES, AND RELEVANT LAWS AND REGULATIONS.
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="permit_understanding"
                                name="permit_understanding"
                                value="1"
                                required
                                onchange="checkAcceptance()"
                            >
                            <label class="form-check-label fw-bold" for="permit_understanding">
                                I UNDERSTAND THAT THIS ACKNOWLEDGMENT DOES NOT CONSTITUTE APPROVAL OR GUARANTEE THE ISSUANCE OF A WILDLIFE PERMIT.
                            </label>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit" id="btn-submit" disabled>Submit</button>
            </div>
        </form >
    </div>
</div>

<script>
    $(function(){
        @if($errors->submitApplication->any())
            $('#submitApplicationModal').modal('show');
        @endif
        onAttachSignatureChanged();
    })
    function showSubmitApplicationModal(action, resubmit = false){
        $('#submitApplicationModal form').attr('action', action);
        $('#submitApplicationModal .modal-title').html(resubmit ? 'Resubmit Application' : 'Submit Application');
        $('#submitApplicationModal #message').html(resubmit ? 'Are you sure you want to resubmit this application? This action cannot be undone' : 'Are you sure you want to submit this application? This action cannot be undone');
        $('#submitApplicationModal .btn-submit').html(resubmit ? 'Resubmit' : 'Submit');
        $('#submitApplicationModal').modal('show');
    }
    function onAttachSignatureChanged() {
        $chkBox = $('#attach_signature');
        if($chkBox.is(':checked')) {
            $('#document').prop('required', true);
            $('#attach_signature_check').val(1);
            $('#document_container').hide();
        }
        else {
            $('#document').prop('required', false);
            $('#attach_signature_check').val(0);
            $('#document_container').show();
        }
    }

    function checkAcceptance() {
        if($('#privacy_read').is(':checked') && $('#privacy_acknowledgment').is(':checked') && $('#user_responsibilities').is(':checked') && $('#permit_understanding').is(':checked')) {
            $('#btn-submit').prop('disabled', false);
        }
        else {
            $('#btn-submit').prop('disabled', true);
        }
    }
</script>
