<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :class="darkMode ? 'dark' : ''">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yuii & Mei - Our Scrapbook Memories</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;600;700&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: var(--color-cream, #FFF8F0);
            color: var(--color-text, #5A4A4A);
            overflow-x: hidden;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        /* Saat dark mode aktif, timpa variabel CSS bawaan */
        .dark body {
            background-color: #1a202c;
            /* gray-900 */
            color: #e2e8f0;
            /* gray-200 */
        }

        .bg-pattern-soft {
            background-image: radial-gradient(rgba(248, 187, 208, 0.4) 1.5px, transparent 1.5px);
            background-size: 30px 30px;
        }

        .dark .bg-pattern-soft {
            background-image: radial-gradient(rgba(248, 187, 208, 0.1) 1.5px, transparent 1.5px);
        }

        .polaroid {
            background: #ffffff;
            padding: 12px 12px 45px 12px;
            box-shadow: 0 10px 30px -5px rgba(248, 187, 208, 0.3);
            border-radius: 4px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-block;
            margin-bottom: 2rem;
            width: 100%;
        }

        .polaroid:hover {
            transform: scale(1.05) rotate(0deg) translateY(-10px) !important;
            z-index: 20;
            box-shadow: 0 20px 40px -5px rgba(248, 187, 208, 0.6);
        }

        .photobooth {
            background: #2D2D2D;
            padding: 15px 15px 40px 15px;
            border-radius: 6px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            width: 100%;
            display: inline-block;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .photobooth:hover {
            transform: translateY(-5px);
        }

        .photobooth img {
            margin-bottom: 12px;
            border-radius: 4px;
            filter: contrast(1.05) saturate(1.1);
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out 3s infinite;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body x-data="{ isLocked: true, showMusicModal: false, isPlaying: false }" :class="isLocked ? 'overflow-hidden h-screen' : ''"
    class="font-sans antialiased selection:bg-pinky selection:text-white">

    <button @click="toggleTheme()"
        class="fixed top-6 right-6 z-50 p-3 rounded-full bg-white/80 dark:bg-gray-800 backdrop-blur-md shadow-soft dark:shadow-none border border-pink-100 dark:border-gray-700 hover:scale-110 transition-transform">
        <span x-show="!darkMode">🌙</span>
        <span x-show="darkMode" style="display: none;">☀️</span>
    </button>

    @if ($song)
        <audio id="bgMusic" src="{{ $song->url }}" loop preload="auto"></audio>

        <div x-show="!isLocked" style="display: none;" x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white/90 dark:bg-gray-800 backdrop-blur-md shadow-soft border border-pink-100 dark:border-gray-700 rounded-full p-2 pr-5 transition-all transform hover:scale-105">

            <div class="relative w-10 h-10 bg-gradient-to-tr from-pink-400 to-purple-400 rounded-full flex items-center justify-center shadow-inner"
                :class="isPlaying ? 'animate-[spin_3s_linear_infinite]' : ''">
                <div class="w-3 h-3 bg-white rounded-full border border-gray-200"></div>
            </div>

            <div class="flex flex-col justify-center max-w-[120px] md:max-w-[150px]">
                <span class="text-xs font-bold text-gray-700 dark:text-gray-200 truncate">{{ $song->title }}</span>
                <span class="text-[10px] text-pink-400">Yuii & Mei</span>
            </div>

            <button
                @click="
                let audio = document.getElementById('bgMusic');
                if(isPlaying) { audio.pause(); isPlaying = false; } 
                else { audio.play(); isPlaying = true; }"
                class="text-pink-500 hover:text-pink-600 focus:outline-none ml-2 transition-transform active:scale-90">
                <svg x-show="!isPlaying" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <svg x-show="isPlaying" style="display: none;" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                </svg>
            </button>
        </div>
    @endif

    <div x-show="showMusicModal" style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl transform transition-all">
            <div
                class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <span class="text-3xl">🎵</span>
            </div>
            <p class="text-gray-700 dark:text-gray-200 font-medium mb-8 text-lg">"Kamu mau baca ini sambil play musik
                atau engga sayang?"</p>
            <div class="flex gap-4 justify-center">
                <button
                    @click="
                    let audio = document.getElementById('bgMusic');
                    if(audio) { audio.play(); isPlaying = true; localStorage.setItem('allowMusic', 'yes'); }
                    isLocked = false; 
                    showMusicModal = false; 
                    setTimeout(() => document.getElementById('scrapbook').scrollIntoView({behavior: 'smooth'}), 200);"
                    class="flex-1 py-3 bg-gradient-to-r from-pink-400 to-purple-400 text-white font-bold rounded-full hover:shadow-lg transition-all transform hover:scale-105">
                    iyaa mauu
                </button>
                <button
                    @click="
                    isPlaying = false;
                    isLocked = false; 
                    showMusicModal = false; 
                    localStorage.setItem('allowMusic', 'no');
                    setTimeout(() => document.getElementById('scrapbook').scrollIntoView({behavior: 'smooth'}), 200);"
                    class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 font-bold rounded-full transition-all transform hover:scale-105">
                    engga dlu deh
                </button>
            </div>
        </div>
    </div>

    <section class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://ik.imagekit.io/programassets/sample/yui&mei.jpg"
                class="w-full h-full object-cover object-center scale-105" alt="Yuii & Mei Background">
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/20 via-cream/40 to-cream dark:via-gray-900/40 dark:to-gray-900 transition-colors">
            </div>
        </div>
        <div class="relative z-10 flex flex-col items-center justify-center w-full px-4 text-center mt-20">
            <div class="absolute -top-10 -left-10 md:left-20 w-24 h-24 bg-pinky/30 rounded-full blur-2xl animate-float">
            </div>
            <div
                class="absolute top-40 -right-10 md:right-20 w-32 h-32 bg-lavender/40 rounded-full blur-2xl animate-float-delayed">
            </div>
            <p class="text-pink-600 font-semibold tracking-widest uppercase mb-4 text-sm md:text-base drop-shadow-sm">
                Our Digital Scrapbook
            </p>
            <h1 class="text-7xl md:text-9xl font-hand text-pink-500 mb-6 drop-shadow-md"
                style="text-shadow: 2px 4px 10px rgba(255,255,255,0.8);">
                Yuii & Mei
            </h1>

            <div x-data="daysCounter('{{ $startDate }}')" class="flex gap-4 md:gap-6 justify-center animate-float">
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md px-6 py-5 rounded-[2rem] shadow-soft border border-white dark:border-gray-700 flex flex-col items-center min-w-[100px]">
                    <span class="text-4xl md:text-5xl font-bold text-pink-400 mb-1" x-text="days">0</span>
                    <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-pink-300">Days</span>
                </div>
                <div class="flex items-center text-pink-300 font-bold text-3xl">:</div>
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md px-6 py-5 rounded-[2rem] shadow-soft border border-white dark:border-gray-700 flex flex-col items-center min-w-[100px]">
                    <span class="text-4xl md:text-5xl font-bold text-purple-400 mb-1" x-text="hours">0</span>
                    <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-purple-300">Hours</span>
                </div>
            </div>

            <button @click="showMusicModal = true"
                class="mt-20 px-8 py-4 bg-white/80 dark:bg-gray-800 backdrop-blur-md rounded-full text-pink-500 font-bold tracking-widest uppercase hover:bg-white dark:hover:bg-gray-700 hover:scale-105 transition-all shadow-soft border border-white dark:border-gray-600">
                Buka ya Sayang
            </button>
        </div>
    </section>

    <div id="scrapbook" class="bg-pattern-soft">

        <section class="py-20 px-4 relative">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-pink-400 font-bold tracking-widest text-sm uppercase">About Us</span>
                    <h2 class="text-5xl md:text-6xl font-hand text-purple-400 mt-2">Tentang Kita</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($profiles as $profile)
                        <div
                            class="bg-white/80 dark:bg-gray-800/90 backdrop-blur rounded-[2rem] p-8 shadow-soft border border-pink-50 dark:border-gray-700 flex flex-col items-center text-center group transition-all hover:-translate-y-2 hover:shadow-xl">
                            @if ($profile->icon_url)
                                <img src="{{ $profile->icon_url }}" alt="{{ $profile->name }}"
                                    class="w-32 h-32 rounded-full object-cover mb-4 border-4 border-pink-200 dark:border-pink-500 group-hover:scale-105 transition-transform">
                            @endif
                            <h3 class="text-3xl font-hand text-pink-500 font-bold mb-4">{{ $profile->name }}</h3>

                            @if ($profile->traits)
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">
                                        Karakteristik</p>
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        @foreach ($profile->traits as $trait)
                                            <span
                                                class="px-3 py-1 bg-pink-100 dark:bg-pink-900/50 text-pink-600 dark:text-pink-300 rounded-full text-sm font-medium border border-pink-200 dark:border-pink-800">{{ $trait }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($profile->favorite_music)
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Lagu
                                        Favorit</p>
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        @foreach ($profile->favorite_music as $music)
                                            <span
                                                class="px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300 rounded-full text-sm font-medium border border-purple-200 dark:border-purple-800">🎵
                                                {{ $music }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center col-span-2 text-pink-300 font-medium">Profil pasangan belum ditambahkan
                            di admin panel.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-20 px-4 relative">
            <div class="max-w-6xl mx-auto" x-data="{ limit: 3 }">
                <div class="text-center mb-16">
                    <span class="text-pink-400 font-bold tracking-widest text-sm uppercase">Our Diary</span>
                    <h2 class="text-5xl md:text-6xl font-hand text-purple-400 mt-2">Bab Cerita Kita</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
                    @forelse ($stories as $index => $story)
                        <a href="{{ route('story.show', $story->slug) }}" x-show="{{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="group block bg-white dark:bg-gray-800 rounded-[2rem] overflow-hidden shadow-soft hover:-translate-y-3 transition-all duration-300 border border-pink-50 dark:border-gray-700 relative">
                            <div class="absolute top-[-10px] right-8 w-16 h-8 bg-white/40 dark:bg-gray-800/40 backdrop-blur-md rotate-12 z-10"
                                style="box-shadow: 0 2px 5px rgba(0,0,0,0.05);"></div>
                            <div class="overflow-hidden aspect-video relative">
                                <img src="{{ $story->featured_image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    alt="Story">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                                    <span class="text-white font-medium flex items-center gap-2">Baca Cerita <svg
                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7-7m7 7H3"></path>
                                        </svg></span>
                                </div>
                            </div>
                            <div class="p-8">
                                <p class="text-xs font-bold text-pink-300 mb-2 uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($story->published_at)->diffForHumans() }}
                                </p>
                                <h3
                                    class="text-2xl font-bold mb-3 text-text dark:text-gray-100 group-hover:text-pink-500 transition-colors">
                                    {{ $story->title }}
                                </h3>
                                <p class="text-sm text-text/70 dark:text-gray-400 leading-relaxed">
                                    {!! Str::limit($story->excerpt ?? $story->content, 90) !!}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-center col-span-3 text-pink-300 font-medium">Belum ada cerita yang ditulis. Ayo
                            tulis kenangan pertama kalian!</p>
                    @endforelse
                </div>

                @if (count($stories) > 3)
                    <div class="text-center mt-12" x-show="limit < {{ count($stories) }}">
                        <button @click="limit += 3"
                            class="px-8 py-3 bg-pink-100 dark:bg-gray-800 hover:bg-pink-200 dark:hover:bg-gray-700 text-pink-600 dark:text-pink-400 font-bold rounded-full transition-colors shadow-sm border border-transparent dark:border-gray-600">
                            Load More Stories
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section class="py-20 px-4 relative">
            <div class="max-w-6xl mx-auto" x-data="{ limit: 3 }">
                <div class="text-center mb-16">
                    <span class="text-purple-400 font-bold tracking-widest text-sm uppercase">Sweet Moments</span>
                    <h2 class="text-5xl md:text-6xl font-hand text-pink-500 mt-2">Kenangan Manis</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($memories as $index => $memory)
                        <div x-show="{{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="bg-white/80 dark:bg-gray-800 backdrop-blur p-4 rounded-[2rem] shadow-sm hover:shadow-soft transition-all duration-300 border border-white dark:border-gray-700 group">
                            <div class="overflow-hidden rounded-3xl mb-4 relative aspect-[4/3]">
                                <img src="{{ $memory->image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    alt="Memory">
                                <div
                                    class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold text-pink-500 dark:text-pink-400 shadow-sm">
                                    {{ \Carbon\Carbon::parse($memory->memory_date)->format('M Y') }}
                                </div>
                            </div>
                            <div class="px-3 pb-2 text-center">
                                <h3
                                    class="text-xl font-bold mb-2 text-text dark:text-gray-200 group-hover:text-pink-500 transition-colors">
                                    {{ $memory->title }}
                                </h3>
                                <p class="text-sm text-text/70 dark:text-gray-400">{{ $memory->description }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center col-span-3 text-pink-300 font-medium">Memori manis belum ditambahkan.</p>
                    @endforelse
                </div>

                @if (count($memories) > 3)
                    <div class="text-center mt-12" x-show="limit < {{ count($memories) }}">
                        <button @click="limit += 3"
                            class="px-8 py-3 bg-purple-100 dark:bg-gray-800 hover:bg-purple-200 dark:hover:bg-gray-700 text-purple-600 dark:text-purple-400 font-bold rounded-full transition-colors shadow-sm border border-transparent dark:border-gray-600">
                            Load More Memories
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section
            class="py-24 px-4 bg-white/40 dark:bg-gray-800/40 backdrop-blur-sm border-y border-white/50 dark:border-gray-700/50 relative overflow-hidden">
            <div class="max-w-5xl mx-auto" x-data="{ limit: 3 }">
                <div class="text-center mb-16">
                    <h2 class="text-5xl md:text-6xl font-hand text-pink-500">Jejak Waktu</h2>
                    <p class="text-text/70 dark:text-gray-400 mt-2 font-medium">Langkah demi langkah yang kita lewati
                        bersama.</p>
                </div>
                <div class="relative">
                    <div
                        class="absolute top-0 bottom-0 left-6 md:left-1/2 w-1.5 bg-gradient-to-b from-pink-200 via-purple-200 to-pink-200 dark:from-pink-900 dark:via-purple-900 dark:to-pink-900 rounded-full md:-translate-x-1/2">
                    </div>

                    @forelse ($timeline as $index => $event)
                        <div x-show="{{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform translate-y-8"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="mb-12 relative flex flex-col md:flex-row items-center justify-between {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}">
                            <div
                                class="absolute left-6 md:left-1/2 w-12 h-12 bg-cream dark:bg-gray-900 border-4 border-pink-300 dark:border-pink-600 rounded-full flex items-center justify-center z-10 hover:scale-110 hover:border-pink-500 hover:bg-pink-50 transition-all duration-300 shadow-sm -translate-x-1/2">
                                <span class="text-pink-400 text-xl leading-none pt-1">🕰️</span>
                            </div>
                            <div
                                class="w-full md:w-[45%] {{ $index % 2 == 0 ? 'pl-16 md:pl-0 md:text-left' : 'pl-16 md:pl-0 md:text-right' }}">
                                <div
                                    class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-sm border border-pink-50 dark:border-gray-700 hover:shadow-soft transition-all duration-300 hover:-translate-y-1">
                                    <span
                                        class="text-sm font-bold tracking-wider text-purple-400 mb-2 block uppercase">{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}</span>
                                    <h3 class="text-2xl font-bold mb-3 text-pink-600">{{ $event->title }}</h3>
                                    <p class="text-text/80 dark:text-gray-300 mb-4 leading-relaxed">
                                        {{ $event->description }}</p>
                                    @if ($event->image_url)
                                        <img src="{{ $event->image_url }}"
                                            class="w-full h-48 md:h-56 object-cover rounded-2xl" alt="Event Moment">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-pink-300 font-medium z-10 relative">Belum ada jejak waktu yang
                            terekam.</p>
                    @endforelse
                </div>

                @if (count($timeline) > 3)
                    <div class="text-center mt-8 relative z-20" x-show="limit < {{ count($timeline) }}">
                        <button @click="limit += 3"
                            class="px-8 py-3 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-full transition-colors shadow-md">
                            Load More Timeline
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section class="py-24 px-4 max-w-6xl mx-auto">
            <div class="text-center mb-16" x-data="{ limit: 4 }">
                <span class="text-purple-400 font-bold tracking-widest text-sm uppercase">Video Moments</span>
                <h2 class="text-5xl md:text-6xl font-hand text-pink-500 mt-2">Galeri Video Kita</h2>

                <div class="mt-12 columns-1 md:columns-2 gap-8 space-y-8">
                    @foreach ($videos as $index => $video)
                        <div x-show="{{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="masonry-item bg-white dark:bg-gray-800 p-4 rounded-[2rem] shadow-soft border border-pink-50 dark:border-gray-700">
                            <div class="rounded-2xl overflow-hidden shadow-inner" style="aspect-ratio: 16/9;">
                                @if ($video->youtube_id)
                                    <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <div
                                        class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <p class="text-gray-400">Invalid Video URL</p>
                                    </div>
                                @endif
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-center text-text dark:text-gray-200">
                                {{ $video->title }}</h3>
                        </div>
                    @endforeach
                </div>

                @if (count($videos) > 4)
                    <div class="text-center mt-12" x-show="limit < {{ count($videos) }}">
                        <button @click="limit += 4"
                            class="px-8 py-3 bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-700 hover:bg-purple-50 dark:hover:bg-gray-700 text-purple-600 dark:text-purple-400 font-bold rounded-full transition-colors shadow-sm">
                            Load More Videos
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section class="py-24 px-4 max-w-6xl mx-auto overflow-hidden">
            <div x-data="{ limit: 6, lightboxOpen: false, lightboxImage: '' }">

                <div x-show="lightboxOpen" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-md p-4 sm:p-8"
                    @click="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false">

                    <button class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors p-2"
                        @click="lightboxOpen = false">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <img :src="lightboxImage"
                        class="max-w-full max-h-full object-contain rounded-xl shadow-2xl scale-100"
                        alt="Enlarged Photo" @click.stop>
                </div>

                <div class="text-center mb-16">
                    <span class="text-purple-400 font-bold tracking-widest text-sm uppercase">Memories Gallery</span>
                    <h2 class="text-5xl md:text-6xl font-hand text-pink-500 mt-2">Album Kenangan</h2>
                </div>

                <div class="columns-2 md:columns-2 lg:columns-3 gap-6 space-y-6">
                    @foreach ($galleries as $galleryIndex => $gallery)
                        @if (!empty($gallery->images_url) && is_array($gallery->images_url))
                            @foreach ($gallery->images_url as $photoIndex => $photo)
                                @php $globalIndex = ($galleryIndex * 10) + $photoIndex; @endphp

                                <div x-show="{{ $globalIndex }} < limit"
                                    @click="lightboxOpen = true; lightboxImage = '{{ $photo['url'] }}'"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    class="masonry-item relative group cursor-pointer">

                                    @if ($gallery->layout_style === 'polaroid')
                                        <div class="polaroid transform mx-auto dark:bg-gray-200"
                                            style="rotate: {{ $photoIndex % 2 == 0 ? '3deg' : '-4deg' }};">
                                            <div
                                                class="absolute -top-3 left-1/2 -translate-x-1/2 w-20 h-6 bg-white/60 backdrop-blur-sm shadow-sm rotate-2 z-10">
                                            </div>
                                            <img src="{{ $photo['url'] }}"
                                                class="w-full object-cover rounded-sm grayscale-[20%] hover:grayscale-0 transition-all duration-300"
                                                alt="Polaroid">
                                            <p class="font-hand text-center text-xl md:text-3xl mt-5 text-gray-700">
                                                {{ $gallery->title }}</p>
                                        </div>
                                    @elseif($gallery->layout_style === 'photobooth')
                                        <div class="photobooth transform mx-auto"
                                            style="rotate: {{ $photoIndex % 2 == 0 ? '-2deg' : '2deg' }};">
                                            <img src="{{ $photo['url'] }}" class="w-full object-cover"
                                                alt="Booth">
                                            <p
                                                class="font-sans text-center text-[10px] md:text-xs mt-5 tracking-widest text-white/80 uppercase">
                                                {{ $gallery->title }}</p>
                                        </div>
                                    @else
                                        <div class="overflow-hidden rounded-[1rem] md:rounded-[2rem] shadow-sm">
                                            <img src="{{ $photo['url'] }}"
                                                class="w-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                                                alt="Gallery Photo">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-pink-500/80 via-pink-400/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                                <span
                                                    class="text-white font-hand text-xl md:text-4xl text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">{{ $gallery->title }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>

                <div class="text-center mt-16" x-show="limit < {{ count($galleries) * 10 }}">
                    <button @click="limit += 6"
                        class="px-8 py-3 bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white font-bold rounded-full transition-colors shadow-md">
                        Load More Photos
                    </button>
                </div>
            </div>
        </section>

        <section class="py-24 bg-gradient-to-b from-transparent to-pink-50 dark:to-gray-900 px-4 relative">
            <div class="max-w-3xl mx-auto text-center" x-data="{ limit: 2 }">
                <h2 class="text-5xl md:text-6xl font-hand text-pink-500 mb-12">Surat Untukmu 💌</h2>

                <div class="space-y-8">
                    @forelse ($letters as $index => $letter)
                        <div x-show="{{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform translate-y-8"
                            x-transition:enter-end="opacity-100 transform translate-y-0" x-data="{ unlocked: {{ $letter->is_private ? 'false' : 'true' }}, pinInput: '', errorMsg: '' }"
                            class="bg-white/80 dark:bg-gray-800 backdrop-blur-xl p-8 md:p-12 rounded-[2.5rem] shadow-soft border border-white dark:border-gray-700 text-left relative overflow-hidden group">

                            <div
                                class="absolute -right-10 -top-10 text-pink-100 dark:text-gray-700 opacity-50 group-hover:scale-110 transition-transform duration-500">
                                <svg width="150" height="150" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold mb-6 text-pink-600 relative z-10 flex items-center gap-3">
                                {{ $letter->title }}
                                @if ($letter->is_private)
                                    <span x-show="!unlocked"
                                        class="text-xs bg-red-100 text-red-500 px-3 py-1 rounded-full font-sans tracking-wide">🔒
                                        Terkunci</span>
                                    <span x-show="unlocked"
                                        class="text-xs bg-green-100 text-green-500 px-3 py-1 rounded-full font-sans tracking-wide">🔓
                                        Terbuka</span>
                                @endif
                            </h3>

                            @if ($letter->is_private)
                                <div x-show="!unlocked"
                                    class="p-6 bg-pink-50 dark:bg-gray-700/50 rounded-2xl text-center relative z-10">
                                    <p class="mb-4 text-sm font-medium dark:text-gray-300">Surat ini rahasia, masukkan
                                        PIN untuk membaca ya ❤️</p>
                                    <input type="password" x-model="pinInput" placeholder="Masukkan PIN"
                                        class="border-2 border-pink-200 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-xl px-4 py-2 text-center text-lg mb-4 dark:text-white outline-none focus:border-pink-500 transition-colors">
                                    <br>
                                    <button
                                        @click="if(pinInput == '{{ $letter->pin }}') { unlocked = true; } else { errorMsg = 'Yahh salah, coba lagi! 😝'; pinInput = ''; }"
                                        class="bg-pink-500 text-white px-8 py-2.5 rounded-full font-bold hover:bg-pink-600 transition-colors shadow-md">
                                        Buka Surat
                                    </button>
                                    <p x-show="errorMsg" x-text="errorMsg"
                                        class="text-red-500 text-sm mt-3 font-bold"></p>
                                </div>
                            @endif

                            <div x-show="unlocked" x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0 blur-sm"
                                class="prose prose-pink prose-lg dark:prose-invert text-text/80 dark:text-gray-300 leading-loose relative z-10 font-medium">
                                {!! $letter->content !!}
                                <p class="mt-8 font-hand text-2xl text-pink-400 relative z-10 text-right">With Love,
                                    Yuii</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-pink-300 font-medium">Belum ada surat yang ditulis.</p>
                    @endforelse
                </div>

                @if (count($letters) > 2)
                    <div class="text-center mt-12" x-show="limit < {{ count($letters) }}">
                        <button @click="limit += 2"
                            class="px-8 py-3 bg-pink-100 dark:bg-gray-800 hover:bg-pink-200 dark:hover:bg-gray-700 text-pink-600 dark:text-pink-400 font-bold rounded-full transition-colors shadow-sm">
                            Baca Surat Lainnya
                        </button>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <footer
        class="py-10 text-center bg-pink-50 dark:bg-gray-900 border-t border-pink-100 dark:border-gray-800 transition-colors">
        <p class="text-sm font-bold text-pink-400 uppercase tracking-widest mb-2">Yuii & Mei</p>
        <p class="text-xs font-medium text-pink-300 dark:text-gray-500">Coded with 💻 & Effort for You.</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('daysCounter', (startDateStr) => ({
                days: 0,
                hours: 0,
                init() {
                    const startDate = new Date(startDateStr).getTime();
                    const updateCounter = () => {
                        const now = new Date().getTime();
                        const distance = now - startDate;
                        this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 *
                            60));
                    };
                    updateCounter();
                    setInterval(updateCounter, 1000);
                }
            }))
        })
    </script>
</body>

</html>
