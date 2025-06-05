<?php

namespace App\Livewire;

use App\Models\Family;
use Livewire\Component;
use App\Models\Announcement;
use App\Models\ActivityPhoto;
use App\Models\LandingPageContent;
use App\Models\LandingPageSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Log;

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
    public $heroImages;

    public function mount()
    {
        try {
            $this->loadContent();
            $this->loadSettings();
            $this->loadDynamicData();
            $this->loadHeroImages();
        } catch (\Exception $e) {
            Log::error('LandingPage mount error: ' . $e->getMessage());
            // Set default values if loading fails
            $this->setDefaults();
        }
    }

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    protected function loadContent()
    {
        try {
            $this->heroContent = LandingPageContent::bySection('hero')->active()->first();
            $this->aboutContent = LandingPageContent::getSection('about');
            $this->benefitsContent = LandingPageContent::getSection('benefits');
            $this->processFlowContent = LandingPageContent::getSection('workflow');
            $this->servicesContent = LandingPageContent::getSection('services');
            $this->contactContent = LandingPageContent::getSection('contact');
        } catch (\Exception $e) {
            Log::error('loadContent error: ' . $e->getMessage());
        }
    }

    protected function setDefaults()
    {
        $this->settings = [
            'site_name' => 'SIMPERU',
            'site_description' => 'Sistem Informasi Manajemen Pengurus Perumahan',
            'site_tagline' => 'Membantu pengelolaan perumahan menjadi lebih mudah, modern, dan transparan.',
            'email' => 'info@simperu.id',
            'phone' => '+62 812-3456-7890',
            'address' => 'Jl. Digital Raya No. 123, Kota Cyber, Indonesia',
            'whatsapp' => 'https://wa.me/6281234567890',
            'social_facebook' => '#',
            'social_instagram' => '#',
            'social_twitter' => '#',
        ];

        $this->announcements = collect();
        $this->featuredPhotos = collect();
        $this->statistics = [
            'total_families' => 0,
            'total_members' => 0,
            'total_announcements' => 0,
            'active_houses' => 0,
        ];

        $this->heroImages = [
            [
                'url' => 'https://placehold.co/1920x1080/1E3A8A/FFFFFF?text=Manajemen+Perumahan+Modern',
                'alt' => 'Manajemen Perumahan Modern'
            ],
        ];
    }

    protected function loadSettings()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('loadSettings error: ' . $e->getMessage());
        }
    }

    protected function loadDynamicData()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('loadDynamicData error: ' . $e->getMessage());
            $this->announcements = collect();
            $this->featuredPhotos = collect();
            $this->statistics = [
                'total_families' => 0,
                'total_members' => 0,
                'total_announcements' => 0,
                'active_houses' => 0,
            ];
        }
    }

    protected function loadHeroImages()
    {
        try {
            if ($this->heroContent) {
                // Get images from Spatie Media Library without eager loading to prevent memory issues
                $heroImages = $this->heroContent->getMedia('hero_images');

                if ($heroImages->isNotEmpty()) {
                    $this->heroImages = $heroImages->take(3)->map(function ($media, $index) {
                        return [
                            'url' => $media->getUrl(),
                            'alt' => $media->getCustomProperty('alt') ?? "Hero Image " . ($index + 1),
                            'title' => $media->name
                        ];
                    })->toArray();
                    return;
                }
            }
        } catch (\Exception $e) {
            Log::error('loadHeroImages error: ' . $e->getMessage());
        }

        // Default fallback images
        $this->heroImages = [
            [
                'url' => 'https://placehold.co/1920x1080/1E3A8A/FFFFFF?text=Manajemen+Perumahan+Modern',
                'alt' => 'Manajemen Perumahan Modern'
            ],
        ];
    }

    public function getHeroImagesProperty()
    {
        return $this->heroImages;
    }

    public function getSectionImage($section, $default = null)
    {
        $content = null;

        switch ($section) {
            case 'hero':
                $content = $this->heroContent;
                break;
            case 'about':
                $content = $this->aboutContent;
                break;
            case 'benefits':
                $content = $this->benefitsContent;
                break;
            case 'workflow':
                $content = $this->processFlowContent;
                break;
            case 'services':
                $content = $this->servicesContent;
                break;
            case 'contact':
                $content = $this->contactContent;
                break;
        }

        if ($content) {
            $image = $content->getFirstMedia('default');
            if ($image) {
                return [
                    'url' => $image->getUrl(),
                    'alt' => $image->getCustomProperty('alt') ?? $content->title ?? ucfirst($section) . ' Image',
                    'title' => $image->name
                ];
            }
        }

        return $default;
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('components.layouts.landing');
    }
}
