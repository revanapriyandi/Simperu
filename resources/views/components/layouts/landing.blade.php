<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] ?? 'SIMPERU' }} -
        {{ $settings['site_description'] ?? 'Sistem Informasi Manajemen Pengurus Perumahan' }}</title>
    <meta name="description"
        content="{{ $settings['site_description'] ?? 'Sistem Informasi Manajemen Pengurus Perumahan - Platform digital untuk warga dan pengurus perumahan' }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: "Inter", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-poppins {
            font-family: "Poppins", sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        .hero-bg-slide {
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #10b981;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Hero Background Slide Styles */
        .hero-bg-slide {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            position: relative;
        }

        /* Swiper custom navigation for Hero */
        .heroSwiper .swiper-button-next,
        .heroSwiper .swiper-button-prev {
            color: #fff;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        @media (min-width: 768px) {

            .heroSwiper .swiper-button-next,
            .heroSwiper .swiper-button-prev {
                width: 48px;
                height: 48px;
            }
        }

        .heroSwiper .swiper-button-next:hover,
        .heroSwiper .swiper-button-prev:hover {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .heroSwiper .swiper-button-next::after,
        .heroSwiper .swiper-button-prev::after {
            font-size: 18px;
        }

        @media (min-width: 768px) {

            .heroSwiper .swiper-button-next::after,
            .heroSwiper .swiper-button-prev::after {
                font-size: 22px;
            }
        }

        .heroSwiper .swiper-pagination {
            bottom: 15px !important;
            z-index: 10;
        }

        @media (min-width: 768px) {
            .heroSwiper .swiper-pagination {
                bottom: 25px !important;
            }
        }

        .heroSwiper .swiper-pagination-bullet {
            background-color: rgba(255, 255, 255, 0.5);
            opacity: 1;
        }

        .heroSwiper .swiper-pagination-bullet-active {
            background-color: #10b981 !important;
        }

        .layanan-swiper .swiper-slide {
            height: auto;
            display: flex;
            flex-direction: column;
        }

        .layanan-swiper .swiper-slide>div {
            flex-grow: 1;
        }

        .layanan-swiper .swiper-pagination-bullet-active {
            background-color: #1e3a8a !important;
        }

        .layanan-swiper .swiper-pagination {
            position: static;
            margin-top: 20px;
        }

        /* Animation keyframes */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-30px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(30px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
            opacity: 0;
        }
    </style>
    @filamentStyles
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300"
    x-data="{ mobileMenuOpen: false }">
    {{ $slot }}

    @filamentScripts
    @vite('resources/js/app.js')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Inisialisasi Hero Swiper
        var heroSwiper = new Swiper(".heroSwiper", {
            loop: true,
            effect: "fade",
            fadeEffect: {
                crossFade: true,
            },
            autoplay: {
                delay: 7000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".heroSwiper .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".heroSwiper .swiper-button-next",
                prevEl: ".heroSwiper .swiper-button-prev",
            },
        });

        // Inisialisasi Layanan Swiper
        var layananSwiper = new Swiper(".layananSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".layananSwiper .layanan-pagination",
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });

        // Smooth scrolling for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll(
                    '.animate-fade-in-up, .animate-fade-in-down, .animate-slide-in-left, .animate-slide-in-right')
                .forEach(el => {
                    observer.observe(el);
                });
        });
    </script>
</body>

</html>
