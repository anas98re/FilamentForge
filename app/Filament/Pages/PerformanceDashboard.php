<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
// use Filament\Pages\Tab;
use Filament\Infolists\Components\Tabs\Tab;

class PerformanceDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static string $view = 'filament.pages.performance-dashboard';
    protected static ?string $navigationGroup = 'Performance Management';
    protected static ?int $navigationSort = 1;

    // تعيين اللسان النشط الافتراضي
    public ?string $activeTab = 'invitations_management';

    public function getTitle(): string
    {
        // تأكد أن لديك ملفات الترجمة lang/en.json و lang/ar.json
        // أو غيرها حسب اللغات التي تستهدفها.
        return __('Performance Dashboard');
    }

    // ميثود لتعريف الألسنة
    // يمكنك تسمية هذه الميثود getTabs() أيضاً إذا أردت،
    // طالما أنها لا تتعارض مع ميثود موجودة بنفس الاسم في الكلاس الأب Page.
    // getPageTabs() هو اسم آمن.
    public function getPageTabs(): array
    {
        return [
            'invitations_management' => Tab::make(__('Invitations Management'))
                ->icon('heroicon-m-envelope-open'),

            '360_assessments' => Tab::make(__('360 Assessments'))
                ->icon('heroicon-m-chart-pie'),

            'view_360_assessments' => Tab::make(__('View 360 Assessments'))
                ->icon('heroicon-m-eye'),

            'total_invitations_summary' => Tab::make(__('Total Invitations Summary'))
                ->icon('heroicon-m-calculator'),
        ];
    }

    // هذا الميثود مطلوب بواسطة Filament إذا لم يكن هناك محتوى آخر في الصفحة مباشرة
    // أو إذا كنت تريد عرض الـ view فقط.
    // لكن بما أننا نستخدم الـ view المحدد في protected static string $view،
    // قد لا نحتاج هذا الميثود صراحة.
    // public static function shouldRegisterNavigation(): bool
    // {
    //     return true; // أو أي شروط أخرى لعرضها في القائمة
    // }
}
