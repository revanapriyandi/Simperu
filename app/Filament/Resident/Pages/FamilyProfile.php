<?php

namespace App\Filament\Resident\Pages;

use App\Models\Family;
use App\Models\FamilyMember;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class FamilyProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Data Keluarga';
    
    protected static ?string $title = 'Profil Keluarga';
    
    protected static ?string $navigationGroup = 'Data & Profil';
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.resident.pages.family-profile';

    public function mount(): void
    {
        // Redirect if user doesn't have family data
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthorized');
        }
        
        if ($user->role !== 'resident') {
            abort(403, 'Access denied. Only residents can view family profiles.');
        }
        
        if (!$user->family) {
            redirect()->route('filament.resident.pages.dashboard')
                ->with('warning', 'Data keluarga Anda belum tersedia. Silakan hubungi pengurus.');
        }
    }

    public function getFamily(): ?Family
    {
        return Auth::user()->family;
    }

    public function getFamilyMembers()
    {
        return $this->getFamily()?->members()->orderBy('created_at')->get() ?? collect();
    }

    public function getHeadingActions(): array
    {
        return [
            // Add actions here if needed
        ];
    }
}
