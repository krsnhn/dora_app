@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div style="background:var(--platinum-beige);padding:4rem 1rem;text-align:center;border-bottom:1px solid var(--platinum-beige-dark);">
    <div class="container">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:white;border:1px solid var(--platinum-beige-dark);border-radius:100px;padding:.4rem 1rem;margin-bottom:1.25rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--ocean-blue);"></span>
            <span style="color:var(--text-dark);font-size:.8rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;">Legal</span>
        </div>
        <h1 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:clamp(2rem,5vw,3rem);margin-bottom:.5rem;font-weight:600;">Privacy Policy</h1>
        <p style="color:var(--text-muted);font-size:1rem;">Last updated: January 1, 2025</p>
    </div>
</div>

<div class="container" style="padding:3rem 1rem 5rem;max-width:820px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:3rem;">

        <div style="background:#fafaf6;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:2.5rem;border-left:3px solid var(--ocean-blue);">
            <p style="margin:0;color:var(--text-dark);font-size:.95rem;line-height:1.7;">DORA is committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal information when you use our platform.</p>
        </div>

        @php
        $sections = [
            ['title'=>'1. Information We Collect','content'=>'We collect information you provide directly: name, email address, password (hashed), phone number, address, profile information, and for agencies, valid ID documents. We also collect information automatically when you use our platform: IP address, device type, browser type, pages visited, and usage patterns through cookies and similar technologies.'],
            ['title'=>'2. How We Use Your Information','content'=>'We use your personal information to: create and manage your account, verify agency identity, facilitate connections between travelers and agencies, send email notifications about inquiries, improve our platform and user experience, ensure platform security and prevent fraud, comply with legal obligations, and communicate important platform updates.'],
            ['title'=>'3. Information Sharing','content'=>'We do not sell, rent, or trade your personal information. We may share limited information: with travel agencies when you submit an inquiry (your name, email, and message), with service providers who help operate our platform (Cloudinary for image storage, Brevo for email delivery), and when required by law or to protect our rights and safety.'],
            ['title'=>'4. Data Storage & Security','content'=>'Your data is stored securely using industry-standard encryption. Passwords are hashed using bcrypt. Profile images and documents are stored on Cloudinary with secure access controls. We implement technical and organizational measures to protect against unauthorized access, alteration, disclosure, or destruction of your data.'],
            ['title'=>'5. Cookies','content'=>'DORA uses essential cookies for session management and authentication. We do not use third-party advertising cookies. You may disable cookies in your browser settings, but some platform features may not function properly. Our analytics are conducted using first-party data only.'],
            ['title'=>'6. Your Rights','content'=>'You have the right to access the personal data we hold about you, correct inaccurate information, request deletion of your account and associated data (subject to legal requirements), opt out of non-essential communications, and export your data in a portable format. To exercise these rights, contact us at privacy@dora.app.'],
            ['title'=>'7. Data Retention','content'=>'We retain your account data as long as your account is active. If you delete your account, we will remove your personal information within 30 days, except where retention is required by law. Travel memories and feedback may be anonymized rather than deleted to maintain platform integrity.'],
            ['title'=>'8. Third-Party Services','content'=>'Our platform integrates with OpenWeatherMap (weather data), OpenStreetMap (location visualization), Brevo (email delivery), and Cloudinary (media storage). Each of these services has its own privacy policy. We encourage you to review their policies. We only share the minimum necessary data with these services.'],
            ['title'=>'9. Children\'s Privacy','content'=>'DORA is not directed at children under the age of 13. We do not knowingly collect personal information from children. If you believe a child has provided us with personal information, please contact us immediately and we will take steps to remove that information.'],
            ['title'=>'10. Changes to This Policy','content'=>'We may update this Privacy Policy periodically. We will notify you of significant changes via email or a prominent notice on our platform. The "Last updated" date at the top of this page indicates when the policy was last revised.'],
            ['title'=>'11. Contact & Data Controller','content'=>'DORA acts as the data controller for information collected on this platform. For privacy concerns, data requests, or complaints, contact us at: privacy@dora.app. We aim to respond to all privacy-related inquiries within 72 hours.'],
        ];
        @endphp

        @foreach($sections as $section)
        <div style="margin-bottom:2rem;">
            <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.35rem;margin-bottom:.75rem;padding-bottom:.6rem;border-bottom:1px solid var(--platinum-beige);font-weight:600;">{{ $section['title'] }}</h2>
            <p style="color:var(--text-muted);line-height:1.8;margin:0;">{{ $section['content'] }}</p>
        </div>
        @endforeach

        <div style="background:var(--platinum-beige);border:1px solid var(--platinum-beige-dark);border-radius:12px;padding:2rem;text-align:center;margin-top:2.5rem;">
            <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.25rem;margin-bottom:.5rem;font-weight:600;">Questions about your privacy?</h3>
            <p style="color:var(--text-muted);margin:0 0 1.25rem;font-size:.9rem;">We're here to help with any concerns about your data.</p>
            <a href="mailto:privacy@dora.app" class="btn btn-primary">Contact Privacy Team</a>
        </div>
    </div>
</div>
@endsection