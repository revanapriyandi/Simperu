<?php

namespace App\Filament\Resident\Pages\FamilyDataManagement;

use App\Models\Family;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Actions\Action;
use Filament\Actions\Action as FilamentAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Resources\Pages\Concerns\HasWizard;

class CompleteFamilyData extends Page implements HasForms
{
    use InteractsWithForms;
    use HasWizard;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationLabel = 'Lengkapi Data Keluarga';
    protected static ?string $title = 'Lengkapi Data Keluarga';
    protected static ?string $navigationGroup = 'Data & Profil';
    protected static ?int $navigationSort = 15;
    protected static string $view = 'filament.resident.pages.family-data-management.complete-family-data';

    public ?array $data = [];
    public Family $family;

    public function mount(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if ($user->role !== 'resident') {
            abort(403, 'Access denied. Only residents can manage family data.');
        }

        $this->family = $user->family ?? new Family();
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->data = [
            'basic_info' => [
                'kk_number' => $this->family->kk_number,
                'head_of_family' => $this->family->head_of_family,
                'wife_name' => $this->family->wife_name,
                'house_block' => $this->family->house_block,
                'house_status' => $this->family->house_status,
            ],
            'contact_info' => [
                'phone_1' => $this->family->phone_1,
                'phone_2' => $this->family->phone_2,
                'emergency_contact' => $this->family->emergency_contact ?? '',
                'emergency_contact_relation' => $this->family->emergency_contact_relation ?? '',
            ],
            'vehicle_info' => [
                'license_plate_1' => $this->family->license_plate_1,
                'license_plate_2' => $this->family->license_plate_2,
                'vehicle_1_type' => $this->family->vehicle_1_type ?? '',
                'vehicle_2_type' => $this->family->vehicle_2_type ?? '',
            ],
            'family_members' => $this->family->members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'nik' => $member->nik,
                    'relationship' => $member->relationship,
                    'gender' => $member->gender,
                    'birth_date' => $member->birth_date?->format('Y-m-d'),
                    'occupation' => $member->occupation,
                    'education' => $member->education ?? '',
                    'phone' => $member->phone ?? '',
                    'notes' => $member->notes ?? '',
                ];
            })->toArray(),
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Dasar')
                        ->icon('heroicon-m-identification')
                        ->schema([
                            Section::make('Data Keluarga Utama')
                                ->description('Informasi dasar keluarga yang terdaftar di perumahan')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('basic_info.kk_number')
                                            ->label('Nomor Kartu Keluarga (KK)')
                                            ->required()
                                            ->maxLength(16)
                                            ->placeholder('1406021611070085')
                                            ->helperText('16 digit nomor KK sesuai dokumen resmi'),

                                        Select::make('basic_info.house_status')
                                            ->label('Status Rumah')
                                            ->required()
                                            ->options([
                                                'Milik Sendiri' => 'Milik Sendiri',
                                                'Sewa' => 'Sewa',
                                                'Kontrak' => 'Kontrak',
                                                'Menumpang' => 'Menumpang',
                                            ])
                                            ->default('Milik Sendiri'),
                                    ]),

                                    Grid::make(2)->schema([
                                        TextInput::make('basic_info.head_of_family')
                                            ->label('Nama Kepala Keluarga')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Nama lengkap kepala keluarga'),

                                        TextInput::make('basic_info.wife_name')
                                            ->label('Nama Istri')
                                            ->maxLength(255)
                                            ->placeholder('Nama lengkap istri (jika ada)'),
                                    ]),

                                    TextInput::make('basic_info.house_block')
                                        ->label('Blok Rumah')
                                        ->required()
                                        ->maxLength(10)
                                        ->placeholder('A2, B7, C12, dll.')
                                        ->helperText('Blok dan nomor rumah di Villa Windaro Permai'),
                                ]),
                        ]),

                    Wizard\Step::make('Informasi Kontak')
                        ->icon('heroicon-m-phone')
                        ->schema([
                            Section::make('Kontak Utama')
                                ->description('Nomor telepon yang dapat dihubungi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('contact_info.phone_1')
                                            ->label('No. HP 1 (Utama)')
                                            ->tel()
                                            ->required()
                                            ->placeholder('081234567890')
                                            ->helperText('Nomor yang paling mudah dihubungi'),

                                        TextInput::make('contact_info.phone_2')
                                            ->label('No. HP 2 (Alternatif)')
                                            ->tel()
                                            ->placeholder('082345678901')
                                            ->helperText('Nomor cadangan (opsional)'),
                                    ]),
                                ]),

                            Section::make('Kontak Darurat')
                                ->description('Kontak yang dapat dihubungi dalam keadaan darurat')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('contact_info.emergency_contact')
                                            ->label('Kontak Darurat')
                                            ->tel()
                                            ->placeholder('081987654321')
                                            ->helperText('Nomor keluarga/kerabat terdekat'),

                                        Select::make('contact_info.emergency_contact_relation')
                                            ->label('Hubungan')
                                            ->options([
                                                'Orang Tua' => 'Orang Tua',
                                                'Saudara' => 'Saudara',
                                                'Kerabat' => 'Kerabat',
                                                'Teman' => 'Teman',
                                                'Lainnya' => 'Lainnya',
                                            ])
                                            ->placeholder('Pilih hubungan'),
                                    ]),
                                ]),
                        ]),

                    Wizard\Step::make('Data Kendaraan')
                        ->icon('heroicon-m-truck')
                        ->schema([
                            Section::make('Kendaraan Terdaftar')
                                ->description('Data kendaraan yang parkir di area perumahan')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('vehicle_info.license_plate_1')
                                            ->label('Plat Nomor 1')
                                            ->placeholder('BM 1234 AB')
                                            ->helperText('Kendaraan utama keluarga'),

                                        Select::make('vehicle_info.vehicle_1_type')
                                            ->label('Jenis Kendaraan 1')
                                            ->options([
                                                'Mobil' => 'Mobil',
                                                'Motor' => 'Motor',
                                                'Pick Up' => 'Pick Up',
                                                'SUV' => 'SUV',
                                                'MPV' => 'MPV',
                                                'Lainnya' => 'Lainnya',
                                            ])
                                            ->placeholder('Pilih jenis'),
                                    ]),

                                    Grid::make(2)->schema([
                                        TextInput::make('vehicle_info.license_plate_2')
                                            ->label('Plat Nomor 2')
                                            ->placeholder('BM 5678 CD')
                                            ->helperText('Kendaraan kedua (jika ada)'),

                                        Select::make('vehicle_info.vehicle_2_type')
                                            ->label('Jenis Kendaraan 2')
                                            ->options([
                                                'Mobil' => 'Mobil',
                                                'Motor' => 'Motor',
                                                'Pick Up' => 'Pick Up',
                                                'SUV' => 'SUV',
                                                'MPV' => 'MPV',
                                                'Lainnya' => 'Lainnya',
                                            ])
                                            ->placeholder('Pilih jenis'),
                                    ]),
                                ]),
                        ]),

                    Wizard\Step::make('Anggota Keluarga')
                        ->icon('heroicon-m-users')
                        ->schema([
                            Section::make('Data Anggota Keluarga')
                                ->description('Tambahkan semua anggota keluarga yang tinggal di rumah ini')
                                ->schema([
                                    Repeater::make('family_members')
                                        ->label('')
                                        ->schema([
                                            Grid::make(3)->schema([
                                                TextInput::make('name')
                                                    ->label('Nama Lengkap')
                                                    ->required()
                                                    ->placeholder('Nama sesuai KTP/KK'),

                                                Select::make('relationship')
                                                    ->label('Hubungan')
                                                    ->required()
                                                    ->options([
                                                        'kepala_keluarga' => 'Kepala Keluarga',
                                                        'istri' => 'Istri',
                                                        'anak' => 'Anak',
                                                        'orang_tua' => 'Orang Tua',
                                                        'mertua' => 'Mertua',
                                                        'saudara' => 'Saudara',
                                                        'lainnya' => 'Lainnya',
                                                    ]),

                                                Select::make('gender')
                                                    ->label('Jenis Kelamin')
                                                    ->required()
                                                    ->options([
                                                        'laki-laki' => 'Laki-laki',
                                                        'perempuan' => 'Perempuan',
                                                    ]),
                                            ]),

                                            Grid::make(3)->schema([
                                                TextInput::make('nik')
                                                    ->label('NIK')
                                                    ->maxLength(16)
                                                    ->placeholder('1234567890123456')
                                                    ->helperText('16 digit NIK'),

                                                DatePicker::make('birth_date')
                                                    ->label('Tanggal Lahir')
                                                    ->displayFormat('d/m/Y')
                                                    ->maxDate(now()),

                                                TextInput::make('phone')
                                                    ->label('No. HP')
                                                    ->tel()
                                                    ->placeholder('081234567890'),
                                            ]),

                                            Grid::make(2)->schema([
                                                TextInput::make('occupation')
                                                    ->label('Pekerjaan')
                                                    ->placeholder('Karyawan, Wiraswasta, Mahasiswa, dll.'),

                                                Select::make('education')
                                                    ->label('Pendidikan')
                                                    ->options([
                                                        'SD' => 'SD',
                                                        'SMP' => 'SMP',
                                                        'SMA/SMK' => 'SMA/SMK',
                                                        'D3' => 'D3',
                                                        'S1' => 'S1',
                                                        'S2' => 'S2',
                                                        'S3' => 'S3',
                                                        'Lainnya' => 'Lainnya',
                                                    ])
                                                    ->placeholder('Pilih pendidikan terakhir'),
                                            ]),

                                            Textarea::make('notes')
                                                ->label('Catatan')
                                                ->placeholder('Informasi tambahan (opsional)')
                                                ->rows(2)
                                                ->columnSpanFull(),
                                        ])
                                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? 'Anggota Keluarga Baru')
                                        ->addActionLabel('Tambah Anggota Keluarga')
                                        ->defaultItems(1)
                                        ->reorderableWithButtons()
                                        ->cloneable()
                                        ->collapsible()
                                        ->deleteAction(
                                            fn(\Filament\Forms\Components\Actions\Action $action) => $action
                                                ->requiresConfirmation()
                                                ->modalHeading('Hapus Anggota Keluarga')
                                                ->modalDescription('Apakah Anda yakin ingin menghapus data anggota keluarga ini?')
                                        ),
                                ]),
                        ]),
                ])
                    ->columnSpanFull()
                    ->persistStepInQueryString()
                    ->nextAction(
                        fn(\Filament\Forms\Components\Actions\Action $action) => $action->label('Lanjut →')
                    )
                    ->previousAction(
                        fn(\Filament\Forms\Components\Actions\Action $action) => $action->label('← Kembali')
                    )
                    ->submitAction(
                        Action::make('save')
                            ->label('Simpan Data Keluarga')
                            ->icon('heroicon-o-check-circle')
                            ->color('primary')
                            ->action('save')
                    )


            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            DB::beginTransaction();

            // Update or create family data
            $familyData = array_merge($data['basic_info'], $data['contact_info'], $data['vehicle_info']);
            $familyData['user_id'] = Auth::id();

            if ($this->family->exists) {
                $this->family->update($familyData);
            } else {
                $this->family = Family::create($familyData);
                Auth::user()->update(['family_id' => $this->family->id]);
            }

            // Handle family members
            if (isset($data['family_members'])) {
                // Get existing member IDs
                $existingMemberIds = collect($data['family_members'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Delete members not in the new data
                $this->family->members()
                    ->whereNotIn('id', $existingMemberIds)
                    ->delete();

                // Update or create members
                foreach ($data['family_members'] as $memberData) {
                    $memberData['family_id'] = $this->family->id;
                    $memberData['birth_date'] = $memberData['birth_date'] ?
                        \Carbon\Carbon::parse($memberData['birth_date']) : null;

                    if (isset($memberData['id']) && $memberData['id']) {
                        // Update existing member
                        FamilyMember::where('id', $memberData['id'])->update($memberData);
                    } else {
                        // Create new member
                        unset($memberData['id']);
                        FamilyMember::create($memberData);
                    }
                }

                // Update family members count
                $this->family->update([
                    'family_members_count' => $this->family->members()->count()
                ]);
            }

            DB::commit();

            Notification::make()
                ->title('Data keluarga berhasil disimpan!')
                ->body('Semua informasi keluarga telah tersimpan dengan baik.')
                ->success()
                ->duration(5000)
                ->send();

            $this->redirect(route('filament.resident.pages.family-profile'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal menyimpan data')
                ->body('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->danger()
                ->duration(5000)
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            FilamentAction::make('save')
                ->label('Simpan Data Keluarga')
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->action('save'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Show only if user doesn't have complete family data
        $user = Auth::user();
        if (!$user || $user->role !== 'resident') {
            return false;
        }

        return !$user->family ||
            !$user->family->kk_number ||
            !$user->family->head_of_family ||
            !$user->family->house_block ||
            $user->family->members->count() === 0;
    }
}
