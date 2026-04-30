<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — DORA' : 'DORA — Digital Tourism Connector' }}</title>
    <meta name="description" content="{{ $description ?? 'DORA connects travelers with local agencies across the Philippines and beyond.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Jost:wght@300;400;500;600&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --deep-earth: #2C1810;
            --forest-green: #2C5F2D;
            --sunset-orange: #FF7F4F;
            --platinum-beige: #E8DCC0;
            --ocean-blue: #1E4A6D;
            --deep-earth-light: #5a3a2c;
            --forest-green-light: #3d8040;
            --sunset-orange-light: #ff9d7a;
            --platinum-beige-dark: #d4c5a4;
            --ocean-blue-light: #2d6899;
            --primary: var(--ocean-blue);
            --primary-light: var(--ocean-blue-light);
            --white: #FFFFFF;
            --off-white: #FAF8F4;
            --text-dark: #1a0f0a;
            --text-muted: #6b5a52;
            --shadow-sm: 0 1px 3px rgba(44,24,16,0.08);
            --shadow-md: 0 4px 16px rgba(44,24,16,0.12);
            --shadow-lg: 0 12px 40px rgba(44,24,16,0.16);
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 20px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4 {
            font-family: 'Cormorant Garamond', serif;
            line-height: 1.2;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--deep-earth);
            padding: 0 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(44,24,16,0.3);
        }

        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            height: 68px;
            gap: 1rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: 1.25rem;
            color: white;
            letter-spacing: -0.5px;
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--platinum-beige);
            letter-spacing: 2px;
        }

        .brand-tagline {
            font-size: 0.6rem;
            color: rgba(232,220,192,0.5);
            letter-spacing: 3px;
            text-transform: uppercase;
            display: block;
            line-height: 1;
            margin-top: -2px;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 0.25rem;
            margin-left: auto;
        }

        .nav-link {
            color: rgba(232,220,192,0.8);
            text-decoration: none;
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--platinum-beige);
            background: rgba(30,74,109,0.15);
        }

        .nav-link-cta {
            background: var(--primary);
            color: white !important;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .nav-link-cta:hover {
            background: var(--primary-light);
        }

        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .nav-dropdown-toggle svg {
            width: 14px;
            height: 14px;
            transition: transform 0.2s;
        }

        .nav-dropdown:hover .nav-dropdown-toggle svg {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            padding: 0.5rem;
            border: 1px solid rgba(44,24,16,0.08);
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.875rem;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: background 0.15s;
        }

        .dropdown-item:hover {
            background: var(--off-white);
            color: var(--deep-earth);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--platinum-beige-dark);
            margin: 0.375rem 0.5rem;
        }

        .dropdown-item-danger {
            color: #dc2626;
        }

        .dropdown-item-danger:hover {
            background: #fee2e2;
        }

        .dropdown-user-info {
            padding: 0.75rem 0.875rem;
            border-bottom: 1px solid var(--platinum-beige-dark);
            margin-bottom: 0.375rem;
        }

        .dropdown-user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--deep-earth);
        }

        .dropdown-user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        /* Mobile nav toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            margin-left: auto;
            color: var(--platinum-beige);
        }

        /* ===== FLASH MESSAGES ===== */
        .flash-container {
            max-width: 1280px;
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .flash {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
            position: relative;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .flash-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .flash-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .flash-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .flash-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

        .flash-close {
            position: absolute;
            right: 0.875rem;
            background: none;
            border: none;
            cursor: pointer;
            opacity: 0.6;
            font-size: 1.25rem;
            line-height: 1;
            color: inherit;
        }

        .flash-close:hover { opacity: 1; }

        /* ===== MAIN CONTENT ===== */
        main { flex: 1; }

        /* ===== FOOTER ===== */
        footer {
            background: var(--deep-earth);
            color: var(--platinum-beige);
            padding: 4rem 1rem 2rem;
            margin-top: auto;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand .brand-name { color: var(--platinum-beige); font-size: 1.8rem; }
        .footer-desc {
            color: rgba(232,220,192,0.65);
            font-size: 0.875rem;
            line-height: 1.7;
            margin-top: 0.75rem;
            max-width: 280px;
        }

        .footer-title {
            font-family: 'Jost', sans-serif;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 1.25rem;
        }

        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.625rem; }
        .footer-links a {
            color: rgba(232,220,192,0.65);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--platinum-beige); }

        .footer-bottom {
            border-top: 1px solid rgba(232,220,192,0.15);
            padding-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copy {
            color: rgba(232,220,192,0.45);
            font-size: 0.8rem;
        }

        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }

        .footer-legal a {
            color: rgba(232,220,192,0.45);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .footer-legal a:hover { color: var(--platinum-beige); }

        /* ===== UTILITIES ===== */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .container-sm {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .section { padding: 5rem 0; }
        .section-sm { padding: 3rem 0; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-family: 'Jost', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover { background: var(--primary-light); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(30,74,109,0.4); }

        .btn-secondary {
            background: var(--deep-earth);
            color: var(--platinum-beige);
        }
        .btn-secondary:hover { background: var(--deep-earth-light); transform: translateY(-1px); }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--deep-earth);
            color: var(--deep-earth);
        }
        .btn-outline:hover { background: var(--deep-earth); color: var(--platinum-beige); }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
        }
        .btn-ghost:hover { background: var(--platinum-beige); color: var(--deep-earth); }

        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }

        .btn-sm { padding: 0.5rem 1rem; font-size: 0.8rem; }
        .btn-lg { padding: 1rem 2rem; font-size: 1rem; }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-green { background: #dcfce7; color: #166534; }
        .badge-orange { background: #fff7ed; color: #c2410c; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-gray { background: #f1f5f9; color: #475569; }
        .badge-earth { background: rgba(44,24,16,0.1); color: var(--deep-earth); }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid rgba(44,24,16,0.06);
        }

        /* Forms */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--platinum-beige-dark);
            border-radius: var(--radius-sm);
            font-family: 'Jost', sans-serif;
            font-size: 0.9rem;
            color: var(--text-dark);
            background: white;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--forest-green);
            box-shadow: 0 0 0 3px rgba(44,95,45,0.1);
        }
        .form-input::placeholder { color: #a89890; }

        textarea.form-input { resize: vertical; min-height: 120px; }
        select.form-input { appearance: none; cursor: pointer; }

        .form-error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 0.375rem;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(135deg, var(--deep-earth) 0%, var(--primary) 100%);
            padding: 4rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23E8DCC0' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .page-header-content { position: relative; max-width: 1280px; margin: 0 auto; }
        .page-header h1 { color: var(--platinum-beige); font-size: 3rem; font-weight: 300; }
        .page-header h1 strong { font-weight: 600; color: var(--sunset-orange); }
        .page-header p { color: rgba(232,220,192,0.7); margin-top: 0.75rem; font-size: 1.05rem; }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: rgba(232,220,192,0.5);
            margin-bottom: 1.25rem;
        }

        .breadcrumb a { color: rgba(232,220,192,0.65); text-decoration: none; }
        .breadcrumb a:hover { color: var(--sunset-orange); }
        .breadcrumb-sep { opacity: 0.4; }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 0.375rem; margin-top: 2.5rem; }
        .pagination a, .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .pagination a { background: white; color: var(--text-dark); border: 1px solid var(--platinum-beige-dark); }
        .pagination a:hover { background: var(--deep-earth); color: white; border-color: var(--deep-earth); }
        .pagination span.current { background: var(--deep-earth); color: white; }
        .pagination span.disabled { background: transparent; color: #ccc; }

        /* Table */
        .table-container { overflow-x: auto; border-radius: var(--radius); }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .table th {
            background: var(--off-white);
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 2px solid var(--platinum-beige-dark);
        }
        .table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid rgba(44,24,16,0.06);
            vertical-align: middle;
        }
        .table tbody tr:hover { background: rgba(232,220,192,0.2); }
        .table tbody tr:last-child td { border-bottom: none; }

        /* Stats cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid rgba(44,24,16,0.06);
            text-align: center;
        }
        .stat-number { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: var(--deep-earth); line-height: 1; }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; margin-top: 0.375rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-icon { font-size: 1.5rem; margin-bottom: 0.75rem; }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav {
                display: none;
                position: absolute;
                top: 68px;
                left: 0;
                right: 0;
                background: var(--deep-earth);
                padding: 1rem;
                flex-direction: column;
                align-items: stretch;
                gap: 0.25rem;
                border-top: 1px solid rgba(255,255,255,0.1);
                box-shadow: var(--shadow-lg);
            }
            .navbar-nav.open { display: flex; }
            .navbar { position: relative; }
            .nav-toggle { display: flex; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .page-header h1 { font-size: 2rem; }
            .nav-dropdown-menu { position: static; box-shadow: none; background: rgba(255,255,255,0.05); transform: none; opacity: 1; visibility: visible; }
            .dropdown-item { color: rgba(232,220,192,0.8); }
            .dropdown-item:hover { background: rgba(255,255,255,0.1); color: white; }
        }

        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="brand-logo">D</div>
                <div>
                    <span class="brand-name">DORA</span>
                    <span class="brand-tagline">Your Journey Awaits</span>
                </div>
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path id="menuIcon" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <ul class="navbar-nav" id="navMenu">
                
                @guest
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}">Destinations</a></li>
                    <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                    <li><a href="{{ route('login') }}" class="nav-link">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link nav-link-cta">Get Started</a></li>
                @else
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a></li>
                    @elseif(auth()->user()->isAgency())
                        <li><a href="{{ route('agency.dashboard') }}" class="nav-link">Dashboard</a></li>
                        <li><a href="{{ route('destinations.index') }}" class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}">Destinations</a></li>
                        <li><a href="{{ route('agency.packages.index') }}" class="nav-link">My Packages</a></li>
                        <li><a href="{{ route('agency.inquiries.index') }}" class="nav-link">Inquiries</a></li>
                        <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                    @else
                        <li><a href="{{ route('destinations.index') }}" class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}">Destinations</a></li>
                        <li><a href="{{ route('favorites.index') }}" class="nav-link">Favorites</a></li>
                        <li><a href="{{ route('memories.index') }}" class="nav-link">Memories</a></li>
                        <li><a href="{{ route('backpack.index') }}" class="nav-link">Backpack</a></li>
                        <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                    @endif

                    <li class="nav-dropdown">
                        <span class="nav-link nav-dropdown-toggle">
                            {{ auth()->user()->name }}
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                        </span>
                        <div class="nav-dropdown-menu">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-user-role">{{ auth()->user()->role }}</div>
                            </div>
                            @if(!auth()->user()->isAdmin())
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Profile Settings
                            </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-danger" style="width:100%;border:none;background:none;cursor:pointer;font-family:inherit;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="flash-container">
        @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
                <div class="flash flash-{{ $type }}" id="flash-{{ $type }}">
                    {{ session($type) }}
                    <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div class="brand-logo">D</div>
                        <span class="brand-name">DORA</span>
                    </div>
                    <p class="footer-desc">Your Digital Tourism Connector. Discover breathtaking destinations and connect with trusted local travel agencies across the Philippines and beyond.</p>
                </div>

                <div>
                    <p class="footer-title">Explore</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                        <li><a href="{{ route('about') }}">About DORA</a></li>
                        @auth
                        <li><a href="{{ route('favorites.index') }}">My Favorites</a></li>
                        <li><a href="{{ route('memories.index') }}">Travel Memories</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <p class="footer-title">For Agencies</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('register') }}">Partner With Us</a></li>
                        @auth @if(auth()->user()->isAgency())
                        <li><a href="{{ route('agency.dashboard') }}">Agency Dashboard</a></li>
                        <li><a href="{{ route('agency.packages.index') }}">Manage Packages</a></li>
                        @endif @endauth
                    </ul>
                </div>

                <div>
                    <p class="footer-title">Legal</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">© {{ date('Y') }} DORA — Digital Tourism Connector. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('privacy') }}">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        if (navToggle) {
            navToggle.addEventListener('click', () => navMenu.classList.toggle('open'));
        }

        // Auto-dismiss flash messages
        document.querySelectorAll('.flash').forEach(el => {
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                el.style.transition = 'all 0.3s';
                setTimeout(() => el.remove(), 300);
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
