<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $websiteTitle }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        .nav-menu {
            background-color: #343a40;
            padding: 1rem 0;
        }
        .nav-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .nav-menu li {
            margin: 0 1rem;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
        }
        .nav-menu a:hover {
            background-color: #495057;
            border-radius: 4px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .content-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem 0;
        }
        .notice-section, .news-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>

    <!-- @php
        echo $viewType . '<br>';
        echo $theme . '<br>';
        echo $components . '<br>';
    @endphp
    <script>
        window.appConfig = {
            subdomainType: "{{ $viewType }}",
            fullDomain: "{{ $fullDomain }}",
            siteData : "{{ $siteData }}",
            theme : "{{ $theme }}",
            components : "{{ $components }}",
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js']) -->
</head>
<body>
    <!-- Website Banner -->
    <header class="banner">
        <div class="container">
            <h1>{{ $bannerTitle }}</h1>
            <p>{{ $bannerSubtitle }}</p>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <div class="container">
            <ul>
                @if(isset($navItems) && is_array($navItems))
                    @foreach($navItems as $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['label'] ?? $item }}</a></li>
                    @endforeach
                @else
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#departments">Departments</a></li>
                    <li><a href="#admissions">Admissions</a></li>
                    <li><a href="#research">Research</a></li>
                    <li><a href="#contact">Contact</a></li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <div class="content-section">
            <!-- Notice Section -->
            <section class="notice-section">
                <h2 class="section-title">Latest Notices</h2>
                <div class="notice-list">
                    <div class="notice-item">
                        <h4>Admission Notice 2024</h4>
                        <p>Applications for undergraduate programs are now open. Last date: March 30, 2024</p>
                        <small>Posted: March 1, 2024</small>
                    </div>
                    <div class="notice-item">
                        <h4>Semester Final Examination</h4>
                        <p>Final examination schedule for Spring 2024 semester has been published.</p>
                        <small>Posted: February 25, 2024</small>
                    </div>
                    <div class="notice-item">
                        <h4>Research Grant Application</h4>
                        <p>Faculty members can apply for research grants. Deadline: April 15, 2024</p>
                        <small>Posted: February 20, 2024</small>
                    </div>
                </div>
            </section>

            <!-- News Section -->
            <section class="news-section">
                <h2 class="section-title">Latest News</h2>
                <div class="news-list">
                    <div class="news-item">
                        <h4>International Conference Success</h4>
                        <p>MBSTU hosted successful international conference on sustainable technology with 200+ participants.</p>
                        <small>Published: March 5, 2024</small>
                    </div>
                    <div class="news-item">
                        <h4>New Research Center Established</h4>
                        <p>University inaugurates state-of-the-art AI and Machine Learning Research Center.</p>
                        <small>Published: February 28, 2024</small>
                    </div>
                    <div class="news-item">
                        <h4>Student Achievement Recognition</h4>
                        <p>MBSTU students win national programming contest, bringing pride to the university.</p>
                        <small>Published: February 22, 2024</small>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>{{ $footerCopyright }}</p>
            <p>{{ $footerContact }}</p>
        </div>
    </footer>
</body>
</html>
