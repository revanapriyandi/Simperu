<?php

namespace App\Livewire;

use App\Models\Family;
use Livewire\Component;
use App\Models\Announcement;
use App\Models\ActivityPhoto;
use App\Models\LandingPageContent;
use App\Models\LandingPageSetting;
use Illuminate\Support\Facades\DB;

class LandingPage extends Component
{
    public $mobileMenuOpen = false;

    // Content sections
    public $heroContent;
    public $aboutContent;
    public $benefitsContent;
    public $processFlowContent;
    public $servicesContent;
    public $contactContent;

    // Dynamic data
    public $announcements;
    public $featuredPhotos;
    public $statistics;
    public $settings;

    public function mount()
    {
        $this->loadContent();
        $this->loadSettings();
        $this->loadDynamicData();
    }

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    protected function loadContent()
    {
        $this->heroContent = LandingPageContent::getSection('hero');
        $this->aboutContent = LandingPageContent::getSection('about');
        $this->benefitsContent = LandingPageContent::getSection('benefits');
        $this->processFlowContent = LandingPageContent::getSection('workflow');
        $this->servicesContent = LandingPageContent::getSection('services');
        $this->contactContent = LandingPageContent::getSection('contact');
    }

    protected function loadSettings()
    {
        $this->settings = [
            'site_name' => LandingPageSetting::get('site_name', 'SIMPERU'),
            'site_description' => LandingPageSetting::get('site_description', 'Sistem Informasi Manajemen Pengurus Perumahan'),
            'site_tagline' => LandingPageSetting::get('site_tagline', 'Membantu pengelolaan perumahan menjadi lebih mudah, modern, dan transparan.'),
            'email' => LandingPageSetting::get('contact_email', 'info@simperu.id'),
            'phone' => LandingPageSetting::get('contact_phone', '+62 812-3456-7890'),
            'address' => LandingPageSetting::get('contact_address', 'Jl. Digital Raya No. 123, Kota Cyber, Indonesia'),
            'whatsapp' => LandingPageSetting::get('contact_whatsapp', 'https://wa.me/6281234567890'),
            'social_facebook' => LandingPageSetting::get('social_facebook', '#'),
            'social_instagram' => LandingPageSetting::get('social_instagram', '#'),
            'social_twitter' => LandingPageSetting::get('social_twitter', '#'),
        ];
    }

    protected function loadDynamicData()
    {
        // Load announcements
        $this->announcements = Announcement::where('is_active', true)
            ->where('publish_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>=', now());
            })
            ->orderBy('publish_date', 'desc')
            ->limit(3)
            ->get();

        // Load featured photos
        $this->featuredPhotos = ActivityPhoto::where('is_featured', true)
            ->with('media')
            ->orderBy('activity_date', 'desc')
            ->limit(6)
            ->get();

        // Load statistics
        $this->statistics = [
            'total_families' => Family::where('status', 'active')->count(),
            'total_members' => DB::table('family_members')
                ->join('families', 'family_members.family_id', '=', 'families.id')
                ->where('families.status', 'active')
                ->count(),
            'total_announcements' => Announcement::where('is_active', true)->count(),
            'active_houses' => Family::where('status', 'active')
                ->distinct('house_block')
                ->count('house_block'),
        ];
    }

    public function getHeroImagesProperty()
    {
        if ($this->heroContent && $this->heroContent->meta_data && isset($this->heroContent->meta_data['hero_images'])) {
            return collect($this->heroContent->meta_data['hero_images'])->map(function ($image, $index) {
                return [
                    'url' => $image,
                    'alt' => "Hero Image " . ($index + 1)
                ];
            })->toArray();
        }

        // Default fallback images
        return [
            [
                'url' => 'https://placehold.co/1920x1080/1E3A8A/FFFFFF?text=Manajemen+Perumahan+Modern',
                'alt' => 'Manajemen Perumahan Modern'
            ],
            [
                'url' => 'https://placehold.co/1920x1080/059669/FFFFFF?text=Manajemen+Data+Warga',
                'alt' => 'Manajemen Data Warga'
            ],
            [
                'url' => 'https://placehold.co/1920x1080/F59E0B/1F2937?text=Administrasi+Keuangan+Digital',
                'alt' => 'Administrasi Keuangan Digital'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('components.layouts.landing');
    }
}
