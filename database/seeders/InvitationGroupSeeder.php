<?php

// database/seeders/InvitationGroupSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvitationGroup;
use Illuminate\Support\Str; // لاستخدام Str::slug

class InvitationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'المديرين', 'slug_ar' => 'almodirin', 'name_en' => 'Managers', 'slug_en' => 'managers', 'description' => 'دعوات للمديرين المباشرين.'],
            ['name' => 'الأقران', 'slug_ar' => 'alaqran', 'name_en' => 'Peers', 'slug_en' => 'peers', 'description' => 'دعوات للزملاء في نفس المستوى.'],
            ['name' => 'المرؤوسين المباشرين', 'slug_ar' => 'almarusin', 'name_en' => 'Direct Reports', 'slug_en' => 'direct-reports', 'description' => 'دعوات للموظفين الذين تشرف عليهم مباشرة.'],
            ['name' => 'أخرى', 'slug_ar' => 'ukhra', 'name_en' => 'Others', 'slug_en' => 'others', 'description' => 'دعوات لأطراف أخرى ذات صلة.'],
        ];

        foreach ($groups as $group) {
            InvitationGroup::firstOrCreate(
                ['slug' => $group['slug_en']], // استخدم slug_en كمعرّف فريد مبدئيًا
                [
                    // سنحتاج لاحقاً لجعل الاسم متعدد اللغات
                    // حالياً سنستخدم الاسم الإنجليزي كمثال أو يمكنك اختيار العربي
                    'name' => $group['name_en'], // أو $group['name_ar']
                    // 'description' => $group['description'], // يمكنك جعل الوصف متعدد اللغات أيضاً
                ]
            );
        }

        // للتوضيح، إذا أردنا التعامل مع الأسماء متعددة اللغات بشكل صحيح لاحقاً
        // يمكن استخدام مكتبة مثل spatie/laravel-translatable
        // أو يمكنك إنشاء أعمدة منفصلة name_en, name_ar الخ.

        // مثال مبسط للاسم الإنجليزي والـslug الإنجليزي:
        $simpleGroups = [
            ['name' => 'Managers', 'slug' => 'managers', 'description' => 'Invitations for direct managers.'],
            ['name' => 'Peers', 'slug' => 'peers', 'description' => 'Invitations for colleagues at the same level.'],
            ['name' => 'Direct Reports', 'slug' => 'direct-reports', 'description' => 'Invitations for employees you supervise directly.'],
            ['name' => 'Others', 'slug' => 'others', 'description' => 'Invitations for other relevant parties.'],
        ];

        foreach ($simpleGroups as $groupData) {
            InvitationGroup::updateOrCreate(
                ['slug' => $groupData['slug']],
                [
                    'name' => $groupData['name'],
                    'description' => $groupData['description'],
                ]
            );
        }
    }
}
