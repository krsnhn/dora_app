<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Jost', Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 40px 20px; }
        .card { background: white; max-width: 560px; margin: 0 auto; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #2C5F2D; padding: 40px 32px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 1.6rem; }
        .body { padding: 32px; color: #2C1810; }
        .body p { line-height: 1.7; margin-bottom: 1rem; }
        .btn { display: inline-block; background: #FF7F4F; color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 8px; }
        .footer { text-align: center; padding: 20px; color: #aaa; font-size: .85rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>🎉 You're Approved!</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $user->business_name ?? $user->name }}</strong>,</p>
            <p>Great news! Your agency account on <strong>DORA – Digital Tourism Connector</strong> has been reviewed and <strong>approved</strong> by our admin team.</p>
            <p>You can now log in to your dashboard and start:</p>
            <ul style="line-height:2;">
                <li>Creating and publishing tour packages</li>
                <li>Receiving inquiries from travelers</li>
                <li>Managing your agency profile</li>
            </ul>
            <p style="text-align:center;margin-top:2rem;">
                <a href="{{ url('/agency/dashboard') }}" class="btn">Go to My Dashboard</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} DORA – Digital Tourism Connector. All rights reserved.
        </div>
    </div>
</body>
</html>