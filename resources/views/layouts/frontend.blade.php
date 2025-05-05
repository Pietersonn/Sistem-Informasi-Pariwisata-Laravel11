<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JustHome - Explore Hulu Sungai Tengah')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('frontend.partials.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('frontend.partials.footer')
    
    <!-- Custom JS -->
    <script src="{{ asset('js/frontend.js') }}"></script>
    <script src="{{ asset('js/header.js') }}"></script>
    
    @stack('scripts')
</body>
</html>