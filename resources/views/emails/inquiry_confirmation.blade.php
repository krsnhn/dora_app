<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inquiry Confirmation - DORA</title>
<style>
body { margin:0; padding:0; background:#f5f0e8; font-family:'Helvetica Neue',Arial,sans-serif; }
.container { max-width:600px; margin:0 auto; padding:2rem 1rem; }
.card { background:white; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.1); }
.header { background:linear-gradient(135deg,#1E4A6D,#2C5F2D); padding:2.5rem 2rem; text-align:center; }
.header h1 { color:#E8DCC0; font-size:1.8rem; margin:0 0 .5rem; font-family:Georgia,serif; }
.header p { color:rgba(232,220,192,.8); margin:0; }
.check-circle { width:64px; height:64px; background:rgba(255,255,255,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; }
.body { padding:2rem; }
.detail-row { display:flex; gap:1rem; padding:.7rem 0; border-bottom:1px solid #f0f0f0; }
.detail-label { color:#999; font-size:.85rem; width:120px; flex-shrink:0; }
.detail-value { color:#2C1810; font-weight:500; font-size:.9rem; }
.info-box { background:#e0f2fe; border-radius:10px; padding:1.25rem; border-left:4px solid #1E4A6D; margin:1.5rem 0; }
.footer { padding:1.5rem 2rem; text-align:center; background:#f9f7f4; border-top:1px solid #eee; }
.footer p { color:#999; font-size:.8rem; margin:0; line-height:1.6; }
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <div class="check-circle">
                <svg width="32" height="32" fill="none" stroke="#E8DCC0" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1>🌍 DORA</h1>
            <p>Your inquiry has been submitted!</p>
        </div>
        <div class="body">
            <p style="color:#2C1810;font-size:1.05rem;margin-bottom:.5rem;">Hi <strong>{{ $inquiry->name }}</strong>,</p>
            <p style="color:#888;font-size:.9rem;margin-bottom:1.5rem;">We've received your inquiry and notified the agency. They'll be in touch with you shortly.</p>

            <h3 style="color:#2C1810;font-size:.95rem;margin-bottom:.75rem;">Inquiry Summary</h3>
            <div>
                <div class="detail-row">
                    <span class="detail-label">Package</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Destination</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->destination->name ?? '—' }}, {{ $inquiry->tourPackage->destination->country ?? '' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Price</span>
                    <span class="detail-value" style="color:#2C5F2D;font-weight:700;">₱{{ number_format($inquiry->tourPackage->price ?? 0, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->duration }} days</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Group Size</span>
                    <span class="detail-value">{{ $inquiry->pax }} {{ $inquiry->pax == 1 ? 'person' : 'people' }}</span>
                </div>
                <div class="detail-row" style="border:none;">
                    <span class="detail-label">Agency</span>
                    <span class="detail-value">{{ $inquiry->tourPackage->agency->business_name ?? $inquiry->tourPackage->agency->name ?? '—' }}</span>
                </div>
            </div>

            <div class="info-box">
                <p style="color:#1E4A6D;font-size:.9rem;font-weight:600;margin:0 0 .4rem;">What happens next?</p>
                <p style="color:#555;font-size:.88rem;line-height:1.6;margin:0;">The agency will review your inquiry and contact you at <strong>{{ $inquiry->email }}</strong>. Response times typically range from a few hours to 1 business day.</p>
            </div>

            <p style="color:#aaa;font-size:.85rem;text-align:center;margin-top:1.5rem;">You can track your inquiry status in your DORA dashboard.</p>
        </div>
        <div class="footer">
            <p>This confirmation was sent to <strong>{{ $inquiry->email }}</strong></p>
            <p style="margin-top:.25rem;">© {{ date('Y') }} DORA Digital Tourism Connector. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
