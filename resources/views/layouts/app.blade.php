
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'الحسيني ستور')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10d876;
            --warning-color: #f6ad55;
            --danger-color: #f56565;
            --info-color: #4299e1;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --light-bg: #1a202c;
            --white: #2d3748;
            --text-dark: #f7fafc;
            --text-muted: #a0aec0;
            --border-color: #4a5568;
        }

        [data-theme="dark"] body {
            background-color: #1a202c;
            color: #f7fafc;
        }

        [data-theme="dark"] .sidebar {
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        }

        [data-theme="dark"] .stats-card,
        [data-theme="dark"] .card,
        [data-theme="dark"] .main-content {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #f7fafc;
        }

        [data-theme="dark"] .table th {
            background: linear-gradient(90deg, #4a5568, #2d3748);
            color: #f7fafc;
        }

        [data-theme="dark"] .table td {
            background-color: #2d3748;
            color: #f7fafc;
            border-color: #4a5568;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #4a5568;
            border-color: #718096;
            color: #f7fafc;
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #4a5568;
            border-color: var(--primary-color);
            color: #f7fafc;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        [data-theme="dark"] .text-muted {
            color: #a0aec0 !important;
        }

        [data-theme="dark"] .text-dark {
            color: #f7fafc !important;
        }

        * {
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            direction: rtl;
        }

        * {
            font-family: 'Cairo', sans-serif !important;
        }

        .table, .table th, .table td,
        .form-control, .form-select, .form-label,
        .btn, .badge, .alert,
        .card, .card-title, .card-text,
        h1, h2, h3, h4, h5, h6,
        p, span, div, a {
            font-family: 'Cairo', sans-serif !important;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            margin: 3px 0;
            padding: 12px 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }
        
        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            left: 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(-8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card {
            border-radius: 20px;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--white);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }
        
        .main-content {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            margin: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }

        .card {
            border-radius: 15px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            background: var(--white);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table th {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            font-weight: 600;
        }

        .table td {
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .badge {
            border-radius: 20px;
            padding: 5px 12px;
            font-weight: 500;
        }

        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            background: var(--secondary-color);
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Counter Animation */
        .counter {
            font-weight: 700;
            font-size: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin: 10px;
                padding: 15px;
                border-radius: 15px;
            }
            
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                z-index: 1050;
                width: 280px;
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-store me-2"></i>
                            الحسيني ستور
                        </h4>
                        <small class="text-white-50">مرحباً، {{ auth()->user()->name }}</small>
                    </div>
                    
                    @yield('sidebar')
                    
                    <hr class="text-white-50">
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">@yield('page-title', 'لوحة التحكم')</h2>
                        <div class="text-muted">
                            <i class="fas fa-calendar me-2"></i>
                            {{ now()->format('Y-m-d') }}
                        </div>
                    </div>
                    
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الثيم">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const body = document.body;
            const icon = document.getElementById('theme-icon');
            
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                body.classList.remove('dark-theme');
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                body.classList.add('dark-theme');
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
            
            // إعادة تطبيق الخطوط العربية
            applyArabicFonts();
        }

        // تطبيق الخطوط العربية
        function applyArabicFonts() {
            const elements = document.querySelectorAll('*');
            elements.forEach(element => {
                if (element.style) {
                    element.style.fontFamily = "'Cairo', sans-serif";
                }
            });
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const icon = document.getElementById('theme-icon');
            const html = document.documentElement;
            const body = document.body;
            
            if (savedTheme === 'dark') {
                html.setAttribute('data-theme', 'dark');
                body.classList.add('dark-theme');
                icon.className = 'fas fa-sun';
            }

            // تطبيق الخطوط العربية
            applyArabicFonts();

            // Counter Animation
            animateCounters();

            // Sidebar Toggle for Mobile
            setupMobileSidebar();
            
            // مراقبة التغييرات في DOM للحفاظ على الخطوط العربية
            observeDOM();
        });

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            
            counters.forEach(counter => {
                const target = parseInt(counter.innerText.replace(/[^0-9]/g, ''));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        counter.innerText = target.toLocaleString('ar-EG');
                        clearInterval(timer);
                    } else {
                        counter.innerText = Math.floor(current).toLocaleString('ar-EG');
                    }
                }, 16);
            });
        }

        // Mobile Sidebar
        function setupMobileSidebar() {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                
                // Add mobile menu button
                const menuBtn = document.createElement('button');
                menuBtn.className = 'btn btn-primary d-md-none position-fixed';
                menuBtn.style.cssText = 'top: 20px; left: 20px; z-index: 1060;';
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                
                menuBtn.onclick = () => {
                    sidebar.classList.toggle('show');
                };
                
                document.body.appendChild(menuBtn);
                
                // Close sidebar when clicking outside
                mainContent.onclick = () => {
                    sidebar.classList.remove('show');
                };
            }
        }

        // Loading States
        function showLoading(button) {
            const original = button.innerHTML;
            button.innerHTML = '<span class="loading-spinner me-2"></span>جاري التحميل...';
            button.disabled = true;
            
            return () => {
                button.innerHTML = original;
                button.disabled = false;
            };
        }

        // Toast Notifications
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 1080; min-width: 300px;';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Form Validation Enhancement
        function enhanceFormValidation() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        if (this.required && !this.value) {
                            this.classList.add('is-invalid');
                        } else {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        }
                    });
                });
            });
        }

        // مراقبة التغييرات في DOM
        function observeDOM() {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                applyArabicFonts();
                            }
                        });
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        // Initialize enhancements
        document.addEventListener('DOMContentLoaded', function() {
            enhanceFormValidation();
        });
    </script>
    
    @stack('scripts')
</body>
</html>
