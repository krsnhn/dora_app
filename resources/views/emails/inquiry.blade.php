<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>New Inquiry - DORA</title>
<style>
body { margin:0; padding:0; background:#f5f0e8; font-family:'Helvetica Neue',Arial,sans-serif; }
.container { max-width:600px; margin:0 auto; padding:2rem 1rem; }
.card { background:white; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.1); }
.header { background:linear-gradient(135deg,#2C1810,#2C5F2D); padding:2.5rem 2rem; text-align:center; }
.header h1 { color:#E8DCC0; font-size:1.8rem; margin:0 0 .5rem; font-family:Georgia,serif; }
.header p { color:rgba(232,220,192,.8); margin:0; font-size:.9rem; }
.body { padding:2rem; }
.detail-row { display:flex; gap:1rem; padding:.75rem 0; border-bottom:1px solid #f0f0f0; }
.detail-label { color:#999; font-size:.85rem; width:100px; flex-shrink:0; }
.detail-value { color:#2C1810; font-weight:500; font-size:.9rem; }
.message-box { background:#f9f7f4; border-radius:10px; padding:1.25rem; border-left:4px solid #2C5F2D; margin:1.5rem 0; }
.message-box p { color:#555; font-size:.9rem; line-height:1.6; margin:0; font-style:italic; }
.cta { text-align:center; padding:1.5rem 0 .5rem; }
.btn { display:inline-block; background:#2C5F2D; color:white; padding:.75rem 2rem; border-radius:8px; text-decoration:none; font-weight:600; font-size:.9rem; }
.footer { padding:1.5rem 2rem; text-align:center; background:#f9f7f4; border-top:1px solid #eee; }
.footer p { color:#999; font-size:.8rem; margin:0; }
.badge { display:inline-block; background:#E8DCC0; color:#2C1810; font-size:.78rem; padding:.25rem .6rem; border-radius:20px; font-weight:500; }
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h1>🌍 DORA</h1>
            <p>Digital Tourism Connector</p>
        </div>
        <div class="body">
            <p style="color:#2C1810;font-weight:600;font-size:1.05rem;margin-bottom:.5rem;">You have a new inquiry!</p>
            <p style="color:#888;font-size:.9rem;margin-bottom:1.5rem;">A traveler is interested in your package: <strong style="color:#2C1810;">{{ $inquiry->tourPackage->name }}</strong></p>

            <div>
                <div class="detail-row">
                    <span class="detail-label">From</span>
                    <span class="detail-value">{{ $inquiry->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><a href="mailto:{{ $inquiry->email }}" style="color:#1E4A6D;">{{ $inquiry->email }}</a></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Group Size</span>
                    <span class="detail-value">{{ $inquiry->pax }} {{ $inquiry->pax == 1 ? 'person' : 'people' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Package</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->name }} <span class="badge">₱{{ number_format($inquiry->tourPackage->price,2) }}</span></span>
                </div>
                <div class="detail-row" style="border:none;">
                    <span class="detail-label">Destination</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->destination->name ?? '—' }}, {{ $inquiry->tourPackage->destination->country ?? '' }}</span>
                </div>
            </div>

            @if($inquiry->message)
            <div class="message-box">
                <p style="color:#666;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.5rem;font-style:normal;">Message from traveler:</p>
                <p>"{{ $inquiry->message }}"</p>
            </div>
            @endif

            <div class="cta">
                <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->tourPackage->name }} Inquiry" class="btn">Reply to Traveler</a>
                <p style="color:#aaa;font-size:.8rem;margin-top:1rem;">You can also manage inquiries from your agency dashboard.</p>
            </div>
        </div>
        <div class="footer">
            <p>This email was sent by <strong>DORA Digital Tourism Connector</strong></p>
            <p style="margin-top:.25rem;">© {{ date('Y') }} DORA. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
