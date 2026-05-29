<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Memory;
use App\Models\TimelineEvent;
use App\Models\Letter;
use App\Models\Gallery;
use App\Models\PartnerProfile; // Tambahkan ini
use App\Models\Song;

class FrontendController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'stories' => Story::where('is_published', true)->orderBy('published_at', 'desc')->take(3)->get(),
            'memories' => Memory::orderBy('memory_date', 'desc')->take(6)->get(),
            'timeline' => TimelineEvent::orderBy('event_date', 'asc')->get(),
            'letters' => Letter::latest()->get(),
            'galleries' => Gallery::latest()->get(),
            'profiles' => PartnerProfile::all(), // Kirim data profil
            'song' => Song::latest()->first(),
            'videos' => \App\Models\VideoGallery::latest()->get(),
            'startDate' => '2023-02-14',
        ]);
    }

    public function show($slug)
    {
        $story = Story::with('images')->where('slug', $slug)->firstOrFail();
        $slides = collect([$story->featured_image_url])->merge($story->images->pluck('image_url'))->filter()->values();
        $song = Song::latest()->first(); // Kirim data musik ke halaman detail cerita

        return view('story.show', compact('story', 'slides', 'song'));
    }
}