@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<div style="background:var(--platinum-beige);padding:4rem 1rem;text-align:center;border-bottom:1px solid var(--platinum-beige-dark);">
    <div class="container">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:white;border:1px solid var(--platinum-beige-dark);border-radius:100px;padding:.4rem 1rem;margin-bottom:1.25rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--forest-green);"></span>
            <span style="color:var(--text-dark);font-size:.8rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;">Legal</span>
        </div>
        <h1 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:clamp(2rem,5vw,3rem);margin-bottom:.5rem;font-weight:600;">Terms & Conditions</h1>
        <p style="color:var(--text-muted);font-size:1rem;">Last updated: January 1, 2025</p>
    </div>
</div>

<div class="container" style="padding:3rem 1rem 5rem;max-width:820px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:3rem;">

        <div style="background:#fafaf6;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:2.5rem;border-left:3px solid var(--forest-green);">
            <p style="margin:0;color:var(--text-dark);font-size:.95rem;line-height:1.7;">Please read these Terms and Conditions carefully before using the DORA platform. By accessing or using our service, you agree to be bound by these terms.</p>
        </div>

        @php
        $sections = [
            ['title'=>'1. Acceptance of Terms','content'=>'By registering for or using the DORA Digital Tourism Connector platform ("Service"), you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our Service. These terms apply to all visitors, users, travelers, and registered agencies.'],
            ['title'=>'2. Description of Service','content'=>'DORA is a digital tourism connector platform that facilitates connections between travelers and local travel agencies. We provide tools for browsing destinations, managing tour packages, sending inquiries, and sharing travel memories. DORA acts as an intermediary and is not responsible for the actual travel services provided by agencies.'],
            ['title'=>'3. User Accounts','content'=>'To access certain features, you must create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account. You agree to provide accurate, current, and complete information during registration. DORA reserves the right to suspend or terminate accounts that violate these terms.'],
            ['title'=>'4. Agency Registration','content'=>'Travel agencies must complete verification by submitting valid business identification. Approval is subject to DORA\'s review process. Agencies are responsible for the accuracy of their tour package listings, pricing, and inclusions. Misrepresentation may result in account termination. DORA reserves the right to reject or revoke agency status at any time.'],
            ['title'=>'5. Traveler Conduct','content'=>'Travelers agree to use the platform in good faith. Inquiries submitted must be genuine. Feedback and reviews must be honest and based on actual experiences. Spam, fake reviews, or abusive behavior toward agencies is prohibited and may result in account suspension.'],
            ['title'=>'6. Content & Intellectual Property','content'=>'Users retain ownership of content they upload (photos, reviews). By uploading content, you grant DORA a non-exclusive, royalty-free license to display and use that content on the platform. You agree not to upload content that is offensive, illegal, or infringes on third-party rights. DORA\'s branding, design, and platform code are the intellectual property of DORA.'],
            ['title'=>'7. Privacy','content'=>'Your use of the Service is also governed by our Privacy Policy, which is incorporated into these Terms. Please review our Privacy Policy to understand our practices. By using our Service, you consent to the collection and use of information as described therein.'],
            ['title'=>'8. Disclaimer of Warranties','content'=>'DORA provides the platform "as is" without warranties of any kind. We do not guarantee the accuracy of destination information, agency listings, or weather data. DORA is not liable for any travel-related incidents, disputes between travelers and agencies, or loss of data.'],
            ['title'=>'9. Limitation of Liability','content'=>'To the maximum extent permitted by law, DORA shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of or inability to use the Service, even if DORA has been advised of the possibility of such damages.'],
            ['title'=>'10. Changes to Terms','content'=>'DORA reserves the right to modify these Terms at any time. We will notify users of significant changes via email or platform notification. Continued use of the Service after changes constitutes acceptance of the new terms. We encourage you to review these Terms periodically.'],
            ['title'=>'11. Governing Law','content'=>'These Terms are governed by the laws of the Republic of the Philippines. Any disputes arising from these Terms or your use of the Service shall be subject to the exclusive jurisdiction of the courts of the Philippines.'],
            ['title'=>'12. Contact Us','content'=>'If you have questions about these Terms and Conditions, please contact us at legal@dora.app or through our platform\'s support channels.'],
        ];
        @endphp

        @foreach($sections as $section)
        <div style="margin-bottom:2rem;">
            <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.35rem;margin-bottom:.75rem;padding-bottom:.6rem;border-bottom:1px solid var(--platinum-beige);font-weight:600;">{{ $section['title'] }}</h2>
            <p style="color:var(--text-muted);line-height:1.8;margin:0;">{{ $section['content'] }}</p>
        </div>
        @endforeach

        <div style="background:var(--platinum-beige);border:1px solid var(--platinum-beige-dark);border-radius:12px;padding:2rem;text-align:center;margin-top:2.5rem;">
            <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.25rem;margin-bottom:.5rem;font-weight:600;">Have questions about our terms?</h3>
            <p style="color:var(--text-muted);margin:0 0 1.25rem;font-size:.9rem;">Reach out to our legal team for clarification.</p>
            <a href="mailto:legal@dora.app" class="btn btn-primary">Contact Us</a>
        </div>
    </div>
</div>
@endsection