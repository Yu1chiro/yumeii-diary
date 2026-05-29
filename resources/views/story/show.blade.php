<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    showMusicModal: true,
    isPlaying: false
}" :class="darkMode ? 'dark' : ''">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $story->title }} - Our Memories</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            background-color: var(--color-cream, #FFF8F0);
            color: var(--color-text, #5A4A4A);
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        /* Dark Mode Body */
        .dark body {
            background-color: #1a202c;
            color: #e2e8f0;
        }

        .bg-pattern {
            background-image: radial-gradient(#F8BBD0 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Dark Mode Pattern */
        .dark .bg-pattern {
            background-image: radial-gradient(rgba(248, 187, 208, 0.1) 1px, transparent 1px);
        }

        /* TIPOGRAFI ALA BLOG - Disesuaikan untuk kenyamanan baca tingkat tinggi */
        .story-content {
            font-size: 1.05rem;
            /* Ukuran dasar mobile */
            line-height: 1.8;
            letter-spacing: 0.015em;
            color: inherit;
        }

        @media (min-width: 768px) {
            .story-content {
                font-size: 1.15rem;
                /* Ukuran desktop lebih besar & lega */
                line-height: 1.95;
            }
        }

        .story-content p {
            margin-bottom: 1.8rem;
        }

        .story-content h2,
        .story-content h3 {
            font-family: 'Caveat', cursive;
            color: #F472B6;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-size: 2.2rem;
            line-height: 1.2;
        }

        .story-content strong {
            color: #d946ef;
            font-weight: 600;
        }

        .story-content img {
            border-radius: 1rem;
            margin: 2rem auto;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.1);
            max-height: 60vh;
            object-fit: contain;
        }

        .story-content blockquote {
            border-left: 4px solid #F8BBD0;
            padding-left: 1.25rem;
            margin: 2rem 0;
            font-style: italic;
            color: #9D8189;
            background: #FFF0F5;
            padding: 1.25rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            font-size: 1.1em;
        }

        /* Dark Mode untuk Blockquote */
        .dark .story-content blockquote {
            background: #2d3748;
            color: #cbd5e0;
            border-left-color: #9f7aea;
        }
    </style>
</head>

<body class="bg-pattern font-sans antialiased selection:bg-pink-300 selection:text-white"
    :class="showMusicModal ? 'overflow-hidden h-screen' : ''">

    @if ($song)
        <audio id="bgMusic" src="{{ $song->url }}" loop preload="auto"></audio>

        <button x-show="!showMusicModal"
            @click="let audio = document.getElementById('bgMusic'); if(audio.paused) { audio.play(); isPlaying = true; } else { audio.pause(); isPlaying = false; }"
            class="fixed bottom-6 right-6 z-50 bg-white/80 dark:bg-gray-800 backdrop-blur-md p-3 rounded-full shadow-soft border border-pink-100 dark:border-gray-700 hover:scale-110 transition-transform">
            <span x-show="isPlaying" class="text-pink-500">🔊</span>
            <span x-show="!isPlaying" class="text-gray-400">🔇</span>
        </button>
    @endif

    <div x-show="showMusicModal" style="display: none;"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl transform transition-all scale-100">
            <div
                class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <span class="text-3xl">🎧</span>
            </div>
            <p class="text-gray-700 dark:text-gray-200 font-medium mb-8 text-lg">Mau lanjut dengerin musik sambil baca
                cerita ini?</p>
            <div class="flex gap-4 justify-center">
                <button
                    @click="let audio = document.getElementById('bgMusic'); if(audio) { audio.play(); } isPlaying = true; showMusicModal = false;"
                    class="flex-1 py-3 bg-gradient-to-r from-pink-400 to-purple-400 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-md">
                    Lanjut Play
                </button>
                <button @click="isPlaying = false; showMusicModal = false;"
                    class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 font-bold rounded-full hover:scale-105 transition-transform">
                    Matiin Aja
                </button>
            </div>
        </div>
    </div>

    <nav class="w-full max-w-4xl mx-auto px-4 py-6 md:py-8">
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 text-pink-400 font-bold hover:text-pink-600 dark:hover:text-pink-300 transition-colors bg-white/60 dark:bg-gray-800/60 backdrop-blur px-5 py-2.5 rounded-full shadow-sm border border-transparent dark:border-gray-700">
            <span>←</span> Kembali ke Halaman Utama
        </a>
    </nav>

    <main class="max-w-4xl mx-auto px-4 pb-24">

        <article
            class="bg-white/80 dark:bg-gray-800/90 backdrop-blur-md p-6 sm:p-10 md:p-16 rounded-[2.5rem] shadow-soft border border-pink-50 dark:border-gray-700 transition-colors">

            <header class="text-center mb-12">
                <span class="text-sm font-bold tracking-widest text-purple-400 uppercase mb-3 block">
                    {{ \Carbon\Carbon::parse($story->published_at)->format('d F Y') }}
                </span>
                <h1 class="text-5xl md:text-7xl font-hand text-pink-500 mb-6 leading-tight">{{ $story->title }}</h1>
            </header>

            @if (count($slides) > 0)
                <div x-data="{
                    activeSlide: 0,
                    lightboxOpen: false,
                    slides: {{ $slides->toJson() }},
                    next() { this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1 },
                    prev() { this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1 }
                }"
                    class="relative w-full rounded-3xl shadow-sm mb-14 bg-pink-50 dark:bg-gray-900 group border border-transparent dark:border-gray-700">

                    <div class="relative h-[250px] sm:h-[400px] md:h-[500px] w-full overflow-hidden rounded-3xl cursor-pointer"
                        @click="lightboxOpen = true">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100" :src="slide"
                                class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                                alt="Story Image">
                        </template>

                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <span
                                class="bg-white/90 backdrop-blur text-pink-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg">Klik
                                untuk perbesar 🔍</span>
                        </div>
                    </div>

                    <div x-show="lightboxOpen" style="display: none;" x-transition.opacity.duration.300ms
                        class="fixed inset-0 z-[300] flex items-center justify-center bg-black/95 p-4 sm:p-8 backdrop-blur-md"
                        @keydown.escape.window="lightboxOpen = false">

                        <button @click="lightboxOpen = false"
                            class="absolute top-4 right-4 sm:top-6 sm:right-6 text-white/70 hover:text-white p-2 z-50 bg-black/30 rounded-full hover:bg-black/50 transition-colors">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <template x-if="slides.length > 1">
                            <button @click.stop="prev"
                                class="absolute left-2 sm:left-6 text-white/70 hover:text-white p-2 md:p-3 z-50 hover:scale-110 transition-transform bg-black/40 rounded-full hover:bg-black/60">
                                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                        </template>

                        <img :src="slides[activeSlide]"
                            class="w-auto h-auto max-w-[90vw] md:max-w-5xl max-h-[75vh] md:max-h-[85vh] object-contain rounded-xl shadow-2xl mx-auto"
                            alt="Full Story Image" @click.stop>

                        <template x-if="slides.length > 1">
                            <button @click.stop="next"
                                class="absolute right-2 sm:right-6 text-white/70 hover:text-white p-2 md:p-3 z-50 hover:scale-110 transition-transform bg-black/40 rounded-full hover:bg-black/60">
                                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </template>

                        <template x-if="slides.length > 1">
                            <div
                                class="absolute bottom-6 sm:bottom-10 left-1/2 -translate-x-1/2 flex gap-3 z-50 bg-black/30 px-4 py-2 rounded-full">
                                <template x-for="(slide, index) in slides" :key="index">
                                    <button @click.stop="activeSlide = index"
                                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                        :class="activeSlide === index ? 'bg-pink-500 w-6' : 'bg-white/60 hover:bg-white'"></button>
                                </template>
                            </div>
                        </template>
                    </div>

                    <template x-if="slides.length > 1">
                        <div>
                            <button @click="prev"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white w-10 h-10 rounded-full flex items-center justify-center text-pink-500 shadow-md backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="next"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white w-10 h-10 rounded-full flex items-center justify-center text-pink-500 shadow-md backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            @endif
            <div class="story-content">
                {!! $story->content !!}
            </div>

            <hr class="my-16 border-pink-100 dark:border-gray-700 border-2 rounded-full">

            <div class="text-center text-pink-300 dark:text-pink-400 font-hand text-4xl">
                I love you ❤️
            </div>

        </article>
    </main>

</body>

</html>
