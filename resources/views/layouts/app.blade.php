<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') - DORA @else DORA - Digital Tourism Connector @endif</title>
    <meta name="description" content="{{ $description ?? 'DORA connects travelers with trusted travel agencies.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-earth:#26302f;
            --forest-green:#2f7d62;
            --sunset-orange:#f9734a;
            --platinum-beige:#f4efe6;
            --platinum-beige-dark:#ddd4c4;
            --ocean-blue:#256b8f;
            --ocean-blue-light:#3287af;
            --earth:var(--deep-earth);
            --forest:var(--forest-green);
            --sunset:var(--sunset-orange);
            --beige:var(--platinum-beige);
            --ocean:var(--ocean-blue);
            --primary:#256b8f;
            --primary-light:#3287af;
            --surface:#ffffff;
            --surface-soft:#f7f7f4;
            --off-white:#f7f7f4;
            --text-dark:#17201f;
            --text-muted:#687371;
            --line:#e7e3dc;
            --shadow-sm:0 1px 2px rgba(15,23,42,.06);
            --shadow-md:0 10px 30px rgba(15,23,42,.08);
            --shadow-lg:0 22px 70px rgba(15,23,42,.16);
            --radius:10px;
            --radius-sm:8px;
            --radius-lg:14px;
        }

        body[data-theme="dark"] {
            --surface:#151b1f;
            --surface-soft:#101417;
            --off-white:#0f1316;
            --text-dark:#eef3f1;
            --text-muted:#aab5b2;
            --line:#263137;
            --platinum-beige:#20282d;
            --platinum-beige-dark:#344047;
            --deep-earth:#0d1113;
            --shadow-sm:0 1px 2px rgba(0,0,0,.35);
            --shadow-md:0 10px 30px rgba(0,0,0,.35);
            --shadow-lg:0 22px 70px rgba(0,0,0,.48);
        }

        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{min-height:100vh;background:var(--off-white);color:var(--text-dark);font-family:Inter,system-ui,sans-serif;line-height:1.55}
        h1,h2,h3,h4{font-family:Playfair Display,Georgia,serif;line-height:1.15}
        a{color:inherit}
        main{min-width:0}

        .container{max-width:1280px;margin:0 auto;padding:0 1.5rem}
        .container-sm{max-width:900px;margin:0 auto;padding:0 1.5rem}
        .section{padding:5rem 0}.section-sm{padding:3rem 0}

        .navbar{background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border-bottom:1px solid var(--line);position:sticky;top:0;z-index:60}
        body[data-theme="dark"] .navbar{background:rgba(21,27,31,.92)}
        .navbar-inner{max-width:1280px;margin:0 auto;height:68px;display:flex;align-items:center;gap:1rem;padding:0 1rem}
        .navbar-brand,.brand-lockup{display:flex;align-items:center;gap:.65rem;text-decoration:none}
        .brand-logo{width:38px;height:38px;border-radius:10px;display:block;object-fit:cover}
        .brand-name{font-family:Playfair Display,Georgia,serif;font-size:1.45rem;font-weight:700;letter-spacing:.03em;color:var(--text-dark)}
        .brand-tagline{display:block;font-size:.62rem;text-transform:uppercase;letter-spacing:.18em;color:var(--text-muted);margin-top:-3px}
        .navbar-nav{display:flex;align-items:center;gap:.25rem;list-style:none;margin-left:auto}
        .nav-link{display:inline-flex;align-items:center;gap:.4rem;color:var(--text-muted);text-decoration:none;padding:.58rem .85rem;border-radius:8px;font-size:.9rem;font-weight:600}
        .nav-link:hover,.nav-link.active{background:var(--platinum-beige);color:var(--text-dark)}
        .nav-link-cta{background:var(--primary);color:white!important}.nav-link-cta:hover{background:var(--primary-light);color:white}
        .nav-toggle{display:none;background:none;border:0;color:var(--text-dark);margin-left:auto;padding:.5rem}

        .nav-dropdown{position:relative}.nav-dropdown-toggle{cursor:pointer}
        .nav-dropdown-menu{position:absolute;right:0;top:calc(100% + 10px);min-width:220px;background:var(--surface);border:1px solid var(--line);box-shadow:var(--shadow-lg);border-radius:12px;padding:.5rem;opacity:0;visibility:hidden;transform:translateY(-6px);transition:.16s}
        .nav-dropdown:hover .nav-dropdown-menu{opacity:1;visibility:visible;transform:none}
        .dropdown-item{display:flex;align-items:center;gap:.55rem;width:100%;padding:.7rem .8rem;border-radius:8px;color:var(--text-dark);text-decoration:none;font-size:.9rem;border:0;background:transparent;font:inherit;cursor:pointer;text-align:left}
        .dropdown-item:hover{background:var(--platinum-beige)}.dropdown-item-danger{color:#dc2626}.dropdown-divider{height:1px;background:var(--line);margin:.4rem}.dropdown-user-info{padding:.7rem .8rem;border-bottom:1px solid var(--line);margin-bottom:.4rem}.dropdown-user-name{font-weight:700}.dropdown-user-role{font-size:.78rem;color:var(--text-muted);text-transform:capitalize}

        .app-shell{min-height:100vh;display:grid;grid-template-columns:280px minmax(0,1fr);background:var(--off-white)}
        .app-sidebar{position:sticky;top:0;height:100vh;background:var(--surface);border-right:1px solid var(--line);padding:1rem;display:flex;flex-direction:column;gap:1rem;transition:width .2s}
        .sidebar-header{height:52px;display:flex;align-items:center;justify-content:space-between;gap:.75rem}
        .sidebar-toggle,.theme-toggle{width:38px;height:38px;border:1px solid var(--line);border-radius:9px;background:var(--surface);color:var(--text-dark);display:grid;place-items:center;cursor:pointer}
        .sidebar-toggle:hover,.theme-toggle:hover{background:var(--platinum-beige)}
        .sidebar-user{padding:.9rem;border:1px solid var(--line);border-radius:12px;background:var(--surface-soft)}
        .sidebar-user strong{display:block;font-size:.93rem}.sidebar-user span{font-size:.78rem;color:var(--text-muted);text-transform:capitalize}
        .sidebar-section{display:flex;flex-direction:column;gap:.25rem}.sidebar-label{font-size:.68rem;font-weight:800;color:var(--text-muted);letter-spacing:.12em;text-transform:uppercase;margin:.65rem .7rem .25rem}
        .sidebar-link{display:flex;align-items:center;gap:.7rem;padding:.72rem .78rem;border-radius:9px;color:var(--text-muted);text-decoration:none;font-weight:650;font-size:.91rem}
        .sidebar-link:hover,.sidebar-link.active{background:rgba(37,107,143,.12);color:var(--text-dark)}
        .sidebar-link svg{width:18px;height:18px;flex:0 0 18px}
        .sidebar-footer{margin-top:auto;display:grid;gap:.5rem}
        .app-main{min-width:0;display:flex;flex-direction:column;min-height:100vh}
        .app-topbar{height:64px;position:sticky;top:0;z-index:50;background:rgba(247,247,244,.9);backdrop-filter:blur(16px);border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem}
        body[data-theme="dark"] .app-topbar{background:rgba(15,19,22,.9)}
        .topbar-title{font-weight:800}.topbar-actions{display:flex;align-items:center;gap:.65rem}
        .app-content{flex:1;min-width:0}
        body.sidebar-collapsed .app-shell{grid-template-columns:86px minmax(0,1fr)}
        body.sidebar-collapsed .sidebar-text,body.sidebar-collapsed .brand-tagline,body.sidebar-collapsed .sidebar-user,body.sidebar-collapsed .sidebar-label{display:none}
        body.sidebar-collapsed .app-sidebar{align-items:center}.mobile-sidebar-toggle{display:none}

        .flash-container{max-width:1280px;margin:1rem auto 0;padding:0 1.5rem}.app-main .flash-container{width:100%;max-width:none}
        .flash{position:relative;padding:.85rem 3rem .85rem 1rem;border-radius:9px;font-weight:650;font-size:.9rem;box-shadow:var(--shadow-sm)}
        .flash-success{background:#dcfce7;color:#166534}.flash-error{background:#fee2e2;color:#991b1b}.flash-warning{background:#fef3c7;color:#92400e}.flash-info{background:#dbeafe;color:#1e40af}
        .flash-close{position:absolute;right:.8rem;top:.55rem;background:transparent;border:0;color:inherit;font-size:1.3rem;cursor:pointer}
        .confirm-overlay{position:fixed;inset:0;z-index:1200;background:rgba(23,32,31,.54);display:none;align-items:center;justify-content:center;padding:1rem;backdrop-filter:blur(4px)}
        .confirm-overlay.open{display:flex}
        .confirm-dialog{width:min(430px,100%);background:var(--surface);color:var(--text-dark);border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow-lg);padding:1.25rem}
        .confirm-title{font-family:Inter,system-ui,sans-serif;font-size:1.05rem;font-weight:800;margin-bottom:.45rem;color:var(--text-dark)}
        .confirm-message{color:var(--text-muted);font-size:.92rem;line-height:1.55;margin-bottom:1.15rem}
        .confirm-actions{display:flex;justify-content:flex-end;gap:.65rem;flex-wrap:wrap}

        .page-header{background:linear-gradient(135deg,#153f52 0%,#2f7d62 100%);padding:3.5rem 1.5rem;color:white}
        .page-header-content{max-width:1280px;margin:0 auto}.page-header h1,.page-title{color:inherit;font-size:2.45rem;font-weight:700}.page-header p,.page-subtitle{color:rgba(255,255,255,.82);margin-top:.55rem}
        .page-sub{color:var(--text-muted);font-size:.95rem;margin:.4rem 0 1.6rem}
        .card,.section-box,.kpi-card,.stat-card{background:var(--surface);border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow-sm)}
        .section-box{overflow:hidden;margin-bottom:1.5rem}.section-box-header{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid var(--line)}.section-box-title{font-family:Inter,system-ui,sans-serif;font-size:1rem;font-weight:800;color:var(--text-dark)}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem}.stat-card{padding:1.25rem}.stat-number{font-size:2rem;font-weight:800;color:var(--text-dark);line-height:1}.stat-label{font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);font-weight:800;margin-top:.35rem}
        .grid-4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}.kpi-card{padding:1.25rem}.kpi-icon{width:40px;height:40px;border-radius:9px;display:grid;place-items:center;background:rgba(37,107,143,.12);margin-bottom:.75rem}.kpi-num{font-size:2rem;font-weight:800;color:var(--text-dark);line-height:1}.kpi-label{color:var(--text-muted);font-size:.82rem;font-weight:650;margin-top:.35rem}

        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.7rem 1rem;border-radius:8px;border:1px solid transparent;font:inherit;font-size:.9rem;font-weight:750;text-decoration:none;cursor:pointer;transition:.16s;min-height:40px}
        .btn-primary{background:var(--primary);color:white}.btn-primary:hover{background:var(--primary-light)}
        .btn-secondary{background:var(--text-dark);color:var(--surface)}.btn-secondary:hover{opacity:.88}
        .btn-outline{background:transparent;border-color:var(--line);color:var(--text-dark)}.btn-outline:hover{background:var(--platinum-beige)}
        .btn-ghost{background:transparent;color:var(--text-muted)}.btn-ghost:hover{background:var(--platinum-beige);color:var(--text-dark)}
        .btn-danger{background:#dc2626;color:white}.btn-sm{padding:.45rem .7rem;font-size:.8rem;min-height:32px}
        .badge{display:inline-flex;align-items:center;border-radius:999px;padding:.22rem .55rem;font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em}
        .badge-green,.badge-success{background:#dcfce7;color:#166534}.badge-orange,.badge-warning{background:#fff7ed;color:#c2410c}.badge-red,.badge-danger{background:#fee2e2;color:#991b1b}.badge-blue{background:#dbeafe;color:#1e40af}.badge-gray{background:#f1f5f9;color:#475569}.badge-earth{background:var(--platinum-beige);color:var(--text-dark)}
        .form-group{margin-bottom:1.15rem}.form-label{display:block;font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:.45rem}
        .form-input,.form-control{width:100%;padding:.75rem .9rem;border:1px solid var(--line);border-radius:8px;background:var(--surface);color:var(--text-dark);font:inherit}.form-input:focus,.form-control:focus{outline:3px solid rgba(37,107,143,.13);border-color:var(--primary)}textarea.form-input{min-height:120px;resize:vertical}.form-error{color:#dc2626;font-size:.82rem;margin-top:.35rem}
        .table-container{overflow-x:auto}.table{width:100%;border-collapse:collapse;font-size:.88rem}.table th{background:var(--surface-soft);padding:.85rem 1rem;text-align:left;font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);border-bottom:1px solid var(--line)}.table td{padding:.9rem 1rem;border-bottom:1px solid var(--line);vertical-align:middle}.table tbody tr:hover{background:var(--surface-soft)}

        footer{background:var(--surface);border-top:1px solid var(--line);padding:3rem 1rem 1.5rem;margin-top:auto}.footer-inner{max-width:1280px;margin:0 auto}.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:2rem;margin-bottom:2rem}.footer-title{font-weight:800;font-size:.75rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-muted);margin-bottom:.9rem}.footer-links{list-style:none;display:grid;gap:.55rem}.footer-links a{text-decoration:none;color:var(--text-muted);font-size:.9rem}.footer-bottom{border-top:1px solid var(--line);padding-top:1rem;display:flex;justify-content:space-between;gap:1rem;color:var(--text-muted);font-size:.82rem}

        @media(max-width:1000px){.app-shell{grid-template-columns:1fr}.app-sidebar{position:fixed;z-index:80;left:0;top:0;transform:translateX(-105%);width:280px}.sidebar-open .app-sidebar{transform:none}.mobile-sidebar-toggle{display:grid}.grid-4{grid-template-columns:repeat(2,1fr)}body.sidebar-collapsed .app-shell{grid-template-columns:1fr}}
        @media(max-width:768px){.navbar-nav{display:none;position:absolute;left:0;right:0;top:68px;background:var(--surface);border-bottom:1px solid var(--line);padding:1rem;flex-direction:column;align-items:stretch}.navbar-nav.open{display:flex}.nav-toggle{display:block}.nav-dropdown-menu{position:static;opacity:1;visibility:visible;transform:none;box-shadow:none}.footer-grid{grid-template-columns:1fr 1fr}.page-header h1,.page-title{font-size:2rem}.app-topbar{padding:0 1rem}.grid-4{grid-template-columns:1fr}.footer-bottom{flex-direction:column}}
        @media(max-width:520px){.footer-grid{grid-template-columns:1fr}.container{padding:0 1rem}}
    </style>
    @stack('styles')
</head>
<body>
@php
    $user = auth()->user();
    $role = $user?->role;
    $navGroups = [];
    if ($user?->isAdmin()) {
        $navGroups = [
            'Platform' => [
                ['label' => 'Admin Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z'],
                ['label' => 'Users', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm8 2a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Destinations', 'route' => 'admin.destinations.index', 'active' => 'admin.destinations.*', 'icon' => 'M12 21s7-4.35 7-11a7 7 0 10-14 0c0 6.65 7 11 7 11zm0-8a3 3 0 100-6 3 3 0 000 6z'],
                ['label' => 'Feedback', 'route' => 'admin.feedback.index', 'active' => 'admin.feedback.*', 'icon' => 'M12 17.3L6.1 21l1.6-6.7L2.5 9.8l6.9-.6L12 3l2.6 6.2 6.9.6-5.2 4.5 1.6 6.7L12 17.3z'],
            ],
        ];
    } elseif ($user?->isAgency()) {
        $navGroups = [
            'Agency' => [
                ['label' => 'Agency Dashboard', 'route' => 'agency.dashboard', 'active' => 'agency.dashboard', 'icon' => 'M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z'],
                ['label' => 'Packages', 'route' => 'agency.packages.index', 'active' => 'agency.packages.*', 'icon' => 'M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z'],
                ['label' => 'Inquiries', 'route' => 'agency.inquiries.index', 'active' => 'agency.inquiries.*', 'icon' => 'M21 15a4 4 0 01-4 4H7l-4 4V7a4 4 0 014-4h10a4 4 0 014 4v8z'],
                ['label' => 'Add Destination', 'route' => 'agency.destinations.create', 'active' => 'agency.destinations.*', 'icon' => 'M12 5v14M5 12h14'],
            ],
            'Explore' => [
                ['label' => 'Public Destinations', 'route' => 'destinations.index', 'active' => 'destinations.*', 'icon' => 'M3 6l7-3 7 3 4-2v14l-7 3-7-3-4 2V6z'],
            ],
        ];
    } elseif ($user) {
        $navGroups = [
            'Traveler' => [
                ['label' => 'Traveler Dashboard', 'route' => 'traveler.dashboard', 'active' => 'traveler.dashboard', 'icon' => 'M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z'],
                ['label' => 'Destinations', 'route' => 'destinations.index', 'active' => 'destinations.*', 'icon' => 'M12 21s7-4.35 7-11a7 7 0 10-14 0c0 6.65 7 11 7 11z'],
                ['label' => 'Favorites', 'route' => 'favorites.index', 'active' => 'favorites.*', 'icon' => 'M12 21C7 17 4 14.5 4 10a4 4 0 017-2.6A4 4 0 0118 10c0 4.5-3 7-6 11z'],
                ['label' => 'Backpack', 'route' => 'backpack.index', 'active' => 'backpack.*', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                ['label' => 'Memories', 'route' => 'memories.index', 'active' => 'memories.*', 'icon' => 'M4 5h16v14H4zM8 13l2-2 3 3 2-2 3 4'],
                ['label' => 'Inquiries', 'route' => 'inquiries.index', 'active' => 'inquiries.*', 'icon' => 'M21 15a4 4 0 01-4 4H7l-4 4V7a4 4 0 014-4h10a4 4 0 014 4v8z'],
            ],
        ];
    }
@endphp

@guest
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img class="brand-logo" src="{{ asset('doralogo.png') }}" alt="DORA logo">
                <span><span class="brand-name">DORA</span><span class="brand-tagline">Digital Tourism Connector</span></span>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <ul class="navbar-nav" id="navMenu">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('destinations.index') }}" class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}">Destinations</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><button class="theme-toggle" id="guestThemeToggle" type="button" aria-label="Toggle theme">☾</button></li>
                <li><a href="{{ route('login') }}" class="nav-link">Sign In</a></li>
                <li><a href="{{ route('register') }}" class="nav-link nav-link-cta">Get Started</a></li>
            </ul>
        </div>
    </nav>
    @include('partials.flash')
    <main>@yield('content')</main>
    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="brand-lockup"><img class="brand-logo" src="{{ asset('doralogo.png') }}" alt="DORA logo"><span class="brand-name">DORA</span></div>
                    <p style="color:var(--text-muted);max-width:340px;margin-top:.75rem;">A digital connector for travelers and agencies: discover destinations, compare packages, send inquiries, and plan with confidence.</p>
                </div>
                <div><p class="footer-title">Explore</p><ul class="footer-links"><li><a href="{{ route('destinations.index') }}">Destinations</a></li><li><a href="{{ route('about') }}">About</a></li></ul></div>
                <div><p class="footer-title">Agencies</p><ul class="footer-links"><li><a href="{{ route('register') }}">Partner With Us</a></li><li><a href="{{ route('login') }}">Agency Login</a></li></ul></div>
                <div><p class="footer-title">Legal</p><ul class="footer-links"><li><a href="{{ route('terms') }}">Terms</a></li><li><a href="{{ route('privacy') }}">Privacy</a></li></ul></div>
            </div>
            <div class="footer-bottom"><span>&copy; {{ date('Y') }} DORA. All rights reserved.</span><span>Traveler and agency connection platform</span></div>
        </div>
    </footer>
@else
    <div class="app-shell">
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <a href="{{ $user->isAdmin() ? route('admin.dashboard') : ($user->isAgency() ? route('agency.dashboard') : route('traveler.dashboard')) }}" class="navbar-brand">
                    <img class="brand-logo" src="{{ asset('doralogo.png') }}" alt="DORA logo">
                    <span class="sidebar-text"><span class="brand-name">DORA</span><span class="brand-tagline">Connector</span></span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Collapse sidebar">
                    <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
            </div>

            <div class="sidebar-user">
                <strong>{{ $user->business_name ?: $user->name }}</strong>
                <span>{{ $user->role }}</span>
            </div>

            @foreach($navGroups as $group => $links)
                <nav class="sidebar-section" aria-label="{{ $group }}">
                    <div class="sidebar-label">{{ $group }}</div>
                    @foreach($links as $link)
                        <a href="{{ route($link['route']) }}" class="sidebar-link {{ request()->routeIs($link['active']) ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $link['icon'] }}"/></svg>
                            <span class="sidebar-text">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @endforeach

            <div class="sidebar-footer">
                @if(!$user->isAdmin())
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        <span class="sidebar-text">Profile</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link" style="width:100%;border:0;background:transparent;cursor:pointer;font:inherit;">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        <span class="sidebar-text">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="app-main">
            <header class="app-topbar">
                <div style="display:flex;align-items:center;gap:.65rem;">
                    <button class="sidebar-toggle mobile-sidebar-toggle" id="mobileSidebarToggle" type="button" aria-label="Open sidebar">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="topbar-title">@yield('title', ucfirst($role).' Dashboard')</span>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline btn-sm">Browse</a>
                    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle theme">☾</button>
                </div>
            </header>
            @include('partials.flash')
            <main class="app-content">@yield('content')</main>
        </div>
    </div>
@endguest

<div class="confirm-overlay" id="doraConfirmOverlay" role="dialog" aria-modal="true" aria-labelledby="doraConfirmTitle">
    <div class="confirm-dialog">
        <div class="confirm-title" id="doraConfirmTitle">Confirm Change</div>
        <div class="confirm-message" id="doraConfirmMessage">Are you sure you want to continue?</div>
        <div class="confirm-actions">
            <button type="button" class="btn btn-outline" id="doraConfirmCancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="doraConfirmOk">Continue</button>
        </div>
    </div>
</div>

<script>
    const applyTheme = (theme) => {
        document.body.dataset.theme = theme;
        document.querySelectorAll('.theme-toggle').forEach(btn => btn.textContent = theme === 'dark' ? '☀' : '☾');
    };
    applyTheme(localStorage.getItem('dora-theme') || 'light');
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const next = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('dora-theme', next);
            applyTheme(next);
        });
    });

    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    navToggle?.addEventListener('click', () => navMenu?.classList.toggle('open'));

    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('dora-sidebar-collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
    });
    if (localStorage.getItem('dora-sidebar-collapsed') === '1') {
        document.body.classList.add('sidebar-collapsed');
    }
    document.getElementById('mobileSidebarToggle')?.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.app-sidebar') && !event.target.closest('#mobileSidebarToggle')) {
            document.body.classList.remove('sidebar-open');
        }
    });

    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            el.style.transition = 'all .25s';
            setTimeout(() => el.remove(), 250);
        }, 5000);
    });

    const confirmOverlay = document.getElementById('doraConfirmOverlay');
    const confirmTitle = document.getElementById('doraConfirmTitle');
    const confirmMessage = document.getElementById('doraConfirmMessage');
    const confirmCancel = document.getElementById('doraConfirmCancel');
    const confirmOk = document.getElementById('doraConfirmOk');
    let confirmResolver = null;

    function closeDoraConfirm(result) {
        confirmOverlay.classList.remove('open');
        if (confirmResolver) {
            confirmResolver(result);
            confirmResolver = null;
        }
    }

    window.doraConfirm = function(options = {}) {
        confirmTitle.textContent = options.title || 'Confirm Change';
        confirmMessage.textContent = options.message || 'Are you sure you want to continue?';
        confirmOk.textContent = options.confirmText || 'Continue';
        confirmOk.className = options.danger ? 'btn btn-danger' : 'btn btn-primary';
        confirmCancel.style.display = options.alert ? 'none' : '';
        confirmOverlay.classList.add('open');
        confirmOk.focus();

        return new Promise(resolve => {
            confirmResolver = resolve;
        });
    };

    window.doraAlert = function(message, title = 'Notice') {
        return window.doraConfirm({
            title,
            message,
            confirmText: 'OK',
            alert: true
        });
    };

    confirmCancel.addEventListener('click', () => closeDoraConfirm(false));
    confirmOk.addEventListener('click', () => closeDoraConfirm(true));
    confirmOverlay.addEventListener('click', event => {
        if (event.target === confirmOverlay) closeDoraConfirm(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && confirmOverlay.classList.contains('open')) closeDoraConfirm(false);
    });

    document.addEventListener('submit', event => {
        const form = event.target;
        if (!form.matches('form[data-confirm]') || form.dataset.confirmed === '1') {
            return;
        }

        event.preventDefault();
        window.doraConfirm({
            title: form.dataset.confirmTitle || 'Confirm Change',
            message: form.dataset.confirm || 'Are you sure you want to continue?',
            confirmText: form.dataset.confirmText || 'Continue',
            danger: form.dataset.confirmDanger === 'true'
        }).then(confirmed => {
            if (!confirmed) return;
            form.dataset.confirmed = '1';
            form.requestSubmit();
            setTimeout(() => delete form.dataset.confirmed, 0);
        });
    }, true);
</script>
@stack('scripts')
</body>
</html>
