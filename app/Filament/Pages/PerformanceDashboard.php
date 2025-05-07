<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
// use Filament\Pages\Tab; // This line was likely for an older or different Tab class
use Filament\Infolists\Components\Tabs\Tab; // Correct Tab class for this context

class PerformanceDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static string $view = 'filament.pages.performance-dashboard';
    protected static ?string $navigationGroup = 'Performance Management';
    protected static ?int $navigationSort = 1;

    // Set the default active tab
    public ?string $activeTab = 'invitations_management';

    /**
     * Get the title of the page.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __('Performance Dashboard');
    }

    /**
     * Define the tabs for the page.
     * Each tab is an instance of Filament\Infolists\Components\Tabs\Tab.
     *
     * @return array
     */
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
}
