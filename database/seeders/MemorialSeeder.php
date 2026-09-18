<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Memorial;
use App\Models\TimelineEntry;
use Illuminate\Database\Seeder;

/**
 * Seeds the memorial profile for Pastor Kenneth Osaghae Ayere using the
 * details confirmed on the printed funeral arrangement flyer. Biography
 * and full timeline milestones are left as placeholders for the family
 * to complete from the admin dashboard.
 */
class MemorialSeeder extends Seeder
{
    public function run(): void
    {
        $memorial = Memorial::updateOrCreate(
            ['slug' => 'kenneth-osaghae-ayere'],
            [
                'name' => 'Kenneth Osaghae Ayere',
                'title' => 'Pastor',
                'birth_date' => '1964-01-01',
                'death_date' => '2026-01-01',
                'portrait_path' => 'portraits/kenneth-ayere.jpg',
                'statement' => 'Well done, good and faithful servant.',
                'biography' => "Pastor Kenneth Osaghae Ayere lived a life devoted to God, to his family, and to the people he shepherded. Over sixty-one years, he built a legacy of quiet strength, warmth, and unwavering conviction.\n\n[Family: replace this paragraph with his full biography — early life, education, calling into ministry, career, and legacy.]",
                'status' => 'published',
            ]
        );

        TimelineEntry::updateOrCreate(
            ['memorial_id' => $memorial->id, 'year_label' => '1964'],
            ['title' => 'Born', 'description' => '[Place of birth and early family details to be added.]', 'sort_order' => 1]
        );
        TimelineEntry::updateOrCreate(
            ['memorial_id' => $memorial->id, 'year_label' => '—', 'title' => 'Education & Calling'],
            ['description' => '[Details of schooling, calling into ministry, and ordination to be added.]', 'sort_order' => 2]
        );
        TimelineEntry::updateOrCreate(
            ['memorial_id' => $memorial->id, 'year_label' => '—', 'title' => 'Marriage & Family'],
            ['description' => '[Details of marriage and children to be added.]', 'sort_order' => 3]
        );
        TimelineEntry::updateOrCreate(
            ['memorial_id' => $memorial->id, 'year_label' => '—', 'title' => 'Ministry & Service'],
            ['description' => '[Key milestones in pastoral and community service to be added.]', 'sort_order' => 4]
        );
        TimelineEntry::updateOrCreate(
            ['memorial_id' => $memorial->id, 'year_label' => '2026', 'title' => 'Called Home'],
            ['description' => 'Entered into rest at the age of 61, surrounded by the love of family and faith.', 'sort_order' => 5]
        );

        Event::updateOrCreate(
            ['memorial_id' => $memorial->id, 'title' => 'Service of Songs'],
            [
                'event_date' => '2026-10-08',
                'event_time' => '4:00 PM',
                'venue' => 'Dideolu Estate',
                'address' => "18 T. Y. Danjuma Street, Dideolu Estate\nOff Ligali Ayorinde Street\nVictoria Island, Lagos",
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        Event::updateOrCreate(
            ['memorial_id' => $memorial->id, 'title' => 'Funeral Service'],
            [
                'event_date' => '2026-10-09',
                'event_time' => '10:00 AM',
                'venue' => 'Love Estate',
                'address' => "17 Lucky Nwadei Close, Love Estate\nAjaba Road, Iyanayesi, Ota",
                'description' => 'Interment follows immediately after the funeral service, at his residence.',
                'is_published' => true,
                'sort_order' => 2,
            ]
        );
    }
}
