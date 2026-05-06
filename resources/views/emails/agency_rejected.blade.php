<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Jost', Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 40px 20px; }
        .card { background: white; max-width: 560px; margin: 0 auto; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #C53030; padding: 40px 32px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 1.6rem; }
        .body { padding: 32px; color: #2C1810; }
        .body p { line-height: 1.7; margin-bottom: 1rem; }
        .notes { background: #FEF5E7; padding: 16px; border-left: 4px solid #C53030; margin: 1.5rem 0; border-radius: 4px; }
        .btn { display: inline-block; background: #FF7F4F; color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 8px; }
        .footer { text-align: center; padding: 20px; color: #aaa; font-size: .85rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>⏳ Application Review Complete</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $user->business_name ?? $user->name }}</strong>,</p>
            <p>Thank you for applying to become a travel partner on <strong>DORA – Digital Tourism Connector</strong>. We have reviewed your application carefully.</p>
            <p>Unfortunately, at this time, your agency account application has been <strong>rejected</strong>. This decision was made by our admin team during the verification process.</p>
            
            @if($notes)
                <div class="notes">
                    <strong>Feedback from our team:</strong>
                    <p>{{ $notes }}</p>
                </div>
            @endif

            <p>If you believe this was made in error or would like to reapply with updated information, please don't hesitate to contact our support team.</p>
            <p style="text-align:center;margin-top:2rem;">
                <a href="{{ url('/') }}" class="btn">Visit Our Website</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} DORA – Digital Tourism Connector. All rights reserved.
        </div>
    </div>
</body>
</html>
