<?php

namespace Database\Seeders;

use App\Models\LandingPageContent;
use App\Models\LandingPageSetting;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedContent();
    }

    private function seedSettings()
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Perumahan Villa Windaro Permai',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'Name of the website'
            ],
            [
                'key' => 'site_description',
                'value' => 'Sistem Informasi Manajemen Pengurus Perumahan',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Description',
                'description' => 'Description of the website'
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Membantu pengelolaan perumahan menjadi lebih mudah, modern, dan transparan.',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Tagline',
                'description' => 'Tagline for the website'
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@simperu.id',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Contact Email',
                'description' => 'Main contact email'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 812-3456-7890',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Contact Phone',
                'description' => 'Main contact phone number'
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => 'https://wa.me/6281234567890',
                'type' => 'url',
                'group' => 'contact',
                'label' => 'WhatsApp Link',
                'description' => 'WhatsApp contact link'
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Address',
                'description' => 'Physical address'
            ],

            // Social Media Settings
            [
                'key' => 'social_facebook',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook page URL'
            ],
            [
                'key' => 'social_instagram',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profile URL'
            ],
            [
                'key' => 'social_twitter',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profile URL'
            ],
        ];

        foreach ($settings as $setting) {
            LandingPageSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedContent()
    {
        // Hero Section
        LandingPageContent::updateOrCreate([
            'section' => 'hero'
        ], [
            'title' => 'Manajemen Perumahan <span class="text-accent-400">Terpadu</span> & <span class="text-accent-400">Efisien</span>',
            'subtitle' => 'SIMPERU DIGITAL',
            'description' => 'Solusi lengkap untuk pengurus dan warga dalam mengelola data, keuangan, surat-menyurat, dan komunikasi perumahan secara digital.',
            'button_text' => 'Lihat Layanan',
            'button_link' => '#layanan',
            'is_active' => true,
            'sort_order' => 1,
            'content' => [
                'features' => [
                    [
                        'title' => 'Dashboard Intuitif',
                        'description' => 'Informasi terpusat.',
                        'icon' => 'heroicon-o-chart-bar'
                    ],
                    [
                        'title' => 'Surat Online Cepat',
                        'description' => 'Pengajuan surat digital.',
                        'icon' => 'heroicon-o-document-text'
                    ],
                    [
                        'title' => 'Keuangan Transparan',
                        'description' => 'Laporan iuran & kas real-time.',
                        'icon' => 'heroicon-o-currency-dollar'
                    ]
                ]
            ],
            'meta_data' => [
                'hero_images' => [
                    'https://placehold.co/1920x1080?text=Manajemen+Perumahan+Modern',
                    'https://placehold.co/1920x1080?text=Manajemen+Data+Warga',
                    'https://placehold.co/1920x1080?text=Administrasi+Keuangan+Digital'
                ]
            ]
        ]);

        // About Section
        LandingPageContent::updateOrCreate([
            'section' => 'about'
        ], [
            'title' => 'Tentang SIMPERU',
            'description' => 'SIMPERU (Sistem Informasi Manajemen Pengurus Perumahan) adalah platform berbasis website yang dirancang untuk merevolusi cara pengelolaan administrasi dan komunikasi di lingkungan perumahan Anda. Kami hadir untuk membantu pengurus dan warga dalam mengelola data kependudukan, keuangan, surat-menyurat, serta informasi penting lainnya secara digital, efisien, dan transparan.',
            'is_active' => true,
            'sort_order' => 2,
            'content' => [
                'features' => [
                    [
                        'title' => 'Manajemen Data Warga Komprehensif',
                        'description' => 'Kelola data warga, KK, kontak, status rumah, dan kendaraan dengan mudah dan terpusat.',
                        'icon' => 'heroicon-o-users'
                    ],
                    [
                        'title' => 'Layanan Surat Menyurat Digital',
                        'description' => 'Proses pengajuan surat pengaduan, lingkungan, fasilitas, hingga izin acara secara online dengan status pelacakan.',
                        'icon' => 'heroicon-o-document-text'
                    ],
                    [
                        'title' => 'Keuangan & Iuran Transparan',
                        'description' => 'Laporan keuangan bulanan, pengelolaan iuran sampah, keamanan, sosial, dan kas secara akuntabel.',
                        'icon' => 'heroicon-o-currency-dollar'
                    ]
                ]
            ]
        ]);

        // Benefits Section
        LandingPageContent::updateOrCreate([
            'section' => 'benefits'
        ], [
            'title' => 'Mengapa Memilih SIMPERU?',
            'description' => 'SIMPERU memberikan berbagai kemudahan dan keuntungan bagi seluruh elemen perumahan.',
            'is_active' => true,
            'sort_order' => 3,
            'content' => [
                'benefits' => [
                    [
                        'title' => 'Transparansi Keuangan',
                        'description' => 'Akses laporan iuran dan pengeluaran kas secara real-time dan mudah dipahami.',
                        'icon' => 'heroicon-o-eye'
                    ],
                    [
                        'title' => 'Efisiensi Administrasi',
                        'description' => 'Pengelolaan data warga dan proses surat-menyurat menjadi lebih cepat dan paperless.',
                        'icon' => 'heroicon-o-document-check'
                    ],
                    [
                        'title' => 'Kemudahan Akses',
                        'description' => 'Akses informasi dan layanan perumahan kapan saja dan di mana saja melalui website.',
                        'icon' => 'heroicon-o-device-phone-mobile'
                    ],
                    [
                        'title' => 'Komunikasi Terpusat',
                        'description' => 'Pengumuman dan notifikasi penting tersampaikan secara efektif kepada seluruh warga.',
                        'icon' => 'heroicon-o-megaphone'
                    ],
                    [
                        'title' => 'Keamanan Data',
                        'description' => 'Data warga dan informasi sensitif lainnya tersimpan dengan aman dalam sistem.',
                        'icon' => 'heroicon-o-shield-check'
                    ],
                    [
                        'title' => 'Modernisasi Pengelolaan',
                        'description' => 'Membawa pengelolaan perumahan ke era digital yang lebih maju dan terorganisir.',
                        'icon' => 'heroicon-o-arrow-trending-up'
                    ]
                ]
            ]
        ]);

        // Process Flow Section
        LandingPageContent::updateOrCreate([
            'section' => 'workflow'
        ], [
            'title' => 'Alur Penggunaan Sistem SIMPERU',
            'description' => 'Pahami bagaimana SIMPERU memudahkan interaksi antara pengurus dan warga perumahan.',
            'is_active' => true,
            'sort_order' => 4,
            'content' => [
                'admin_steps' => [
                    [
                        'title' => 'Login & Dashboard',
                        'description' => 'Akses sistem dengan aman, lihat informasi penting, foto kegiatan, dan grafik keuangan.',
                        'icon' => 'heroicon-o-key'
                    ],
                    [
                        'title' => 'Manajemen Data Warga',
                        'description' => 'Tambah, edit, hapus, dan cari data warga dengan mudah (KK, kontak, rumah, dll).',
                        'icon' => 'heroicon-o-users'
                    ],
                    [
                        'title' => 'Kelola Surat & Keuangan',
                        'description' => 'Proses surat pengaduan, kelola iuran, dan buat laporan keuangan bulanan.',
                        'icon' => 'heroicon-o-document-arrow-down'
                    ],
                    [
                        'title' => 'Pengumuman & Notifikasi',
                        'description' => 'Sampaikan informasi penting ke warga melalui sistem dan notifikasi Telegram.',
                        'icon' => 'heroicon-o-bell'
                    ]
                ],
                'resident_steps' => [
                    [
                        'title' => 'Registrasi & Login',
                        'description' => 'Daftar akun baru dan masuk ke sistem dengan mudah.',
                        'icon' => 'heroicon-o-user-plus'
                    ],
                    [
                        'title' => 'Dashboard & Profil',
                        'description' => 'Lihat informasi, foto kegiatan, dan kelola data profil pribadi.',
                        'icon' => 'heroicon-o-user-circle'
                    ],
                    [
                        'title' => 'Pengajuan Surat & Pembayaran Iuran',
                        'description' => 'Ajukan surat pengaduan online, lihat status, dan upload bukti pembayaran iuran.',
                        'icon' => 'heroicon-o-pencil-square'
                    ],
                    [
                        'title' => 'Laporan Keuangan & Pengumuman',
                        'description' => 'Akses laporan keuangan bulanan/tahunan dan terima pengumuman penting.',
                        'icon' => 'heroicon-o-chart-bar'
                    ]
                ]
            ]
        ]);

        // Services Section
        LandingPageContent::updateOrCreate([
            'section' => 'services'
        ], [
            'title' => 'Layanan Unggulan SIMPERU',
            'description' => 'Solusi digital lengkap untuk pengelolaan perumahan yang modern dan efisien, baik untuk pengurus maupun warga.',
            'is_active' => true,
            'sort_order' => 5,
            'content' => [
                'services' => [
                    [
                        'title' => 'Admin/Pengurus: Dashboard & Data',
                        'description' => 'Sistem manajemen data warga yang komprehensif dengan dashboard informatif.',
                        'icon' => 'heroicon-o-users',
                        'features' => [
                            'Login aman dengan username & password',
                            'Dashboard informatif: info terkini, foto kegiatan, grafik keuangan',
                            'Manajemen Data Warga: Tambah, Edit, Hapus, Cari, filter status warga',
                            'Detail data: KK, nama, kontak, blok, status rumah, anggota keluarga, plat nomor'
                        ]
                    ],
                    [
                        'title' => 'Admin/Pengurus: Surat & Keuangan',
                        'description' => 'Pengelolaan surat pengaduan dan sistem keuangan yang transparan.',
                        'icon' => 'heroicon-o-currency-dollar',
                        'features' => [
                            'Surat Pengaduan: Form input ke PDF, template, penomoran',
                            'Manajemen Surat Masuk: Tanggal, status penyelesaian',
                            'Laporan Keuangan Bulanan: Pemasukan & Pengeluaran',
                            'Pengelolaan Iuran: Sampah, Keamanan, Dana Sosial, Kas'
                        ]
                    ],
                    [
                        'title' => 'Admin/Pengurus: Info & Akun',
                        'description' => 'Sistem pengumuman dan manajemen akun yang terintegrasi.',
                        'icon' => 'heroicon-o-bell',
                        'features' => [
                            'Pengumuman Informasi: Sistem notifikasi (opsi: Telegram)',
                            'Log out aman dari sistem'
                        ]
                    ],
                    [
                        'title' => 'Warga: Registrasi & Profil',
                        'description' => 'Kemudahan pendaftaran dan pengelolaan profil untuk warga.',
                        'icon' => 'heroicon-o-user-circle',
                        'features' => [
                            'Registrasi mudah: Nama, email, No. HP, No. Rumah, No. KK, password',
                            'Login aman dengan email & password',
                            'Dashboard personal: Informasi penting, foto kegiatan',
                            'Manajemen Profil: Edit data, simpan perubahan, ubah password'
                        ]
                    ],
                    [
                        'title' => 'Warga: Surat & Administrasi Iuran',
                        'description' => 'Layanan pengajuan surat dan administrasi pembayaran iuran online.',
                        'icon' => 'heroicon-o-document-arrow-down',
                        'features' => [
                            'Pengajuan Surat Pengaduan: Isi form online, konversi ke PDF',
                            'Detail surat: No. surat tujuan, perihal, tanggal, tujuan, deskripsi',
                            'Pelacakan Status Validasi & Berkas Surat',
                            'Administrasi Iuran: Upload bukti pembayaran, status validasi'
                        ]
                    ],
                    [
                        'title' => 'Warga: Laporan & Notifikasi',
                        'description' => 'Akses laporan keuangan dan sistem notifikasi terintegrasi.',
                        'icon' => 'heroicon-o-chart-bar',
                        'features' => [
                            'Akses Laporan Keuangan: Bulanan/Tahunan, grafik pemasukan & pengeluaran',
                            'Pengumuman Informasi: Notifikasi di sistem & opsi via Telegram',
                            'Log out aman'
                        ]
                    ]
                ]
            ]
        ]);

        // Contact Section
        LandingPageContent::updateOrCreate([
            'section' => 'contact'
        ], [
            'title' => 'Hubungi Kami',
            'description' => 'Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi tim SIMPERU atau kunjungi lokasi kami.',
            'is_active' => true,
            'sort_order' => 6,
            'content' => [
                'contact_info' => [
                    [
                        'type' => 'address',
                        'title' => 'Alamat',
                        'value' => 'Jl. Digital Raya No. 123, Kota Cyber, Indonesia',
                        'icon' => 'heroicon-o-map-pin'
                    ],
                    [
                        'type' => 'email',
                        'title' => 'Email',
                        'value' => 'info@simperu.id',
                        'icon' => 'heroicon-o-envelope'
                    ],
                    [
                        'type' => 'phone',
                        'title' => 'WhatsApp',
                        'value' => '+62 812-3456-7890',
                        'icon' => 'heroicon-o-phone'
                    ]
                ]
            ]
        ]);
    }
}
