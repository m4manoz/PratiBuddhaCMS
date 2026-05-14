<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Models\LocalLevel;
use App\Models\SiteSetting;
use App\Models\Product;
use App\Models\Dealer;
use App\Models\Enquiry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'PRATIBUDDHA',
                'hero_title' => 'Nepal\'s Premier Electronics Importer',
                'hero_subtitle' => 'Delivering trusted electronics with clear guidance, genuine products, and nationwide support.',
                'about_text' => 'At Pratibuddha Enterprises, we focus on premium appliances, verified dealer support, and fast enquiry handling for every customer.',
                'hero_cta_products' => 'Explore Products',
                'hero_cta_dealers' => 'Find Authorized Dealer',
                'contact_address' => 'Janabahal, Kathmandu, Nepal',
                'contact_phone' => '+977-9849124657',
                'contact_email' => 'info@pratibuddha.com',
                'warehouse_location' => 'Sitapaila, Kathmandu',
                'seo_title' => 'PRATIBUDDHA | Premium Quality Products',
                'seo_description' => 'Explore premium electronics and authorized dealer support across Nepal.',
                'canonical_url' => config('app.url') ? config('app.url') . '/' : null,
                'og_image' => null,
                'analytics_script' => null,
                'about_page' => [
                    'hero_title' => 'Built for reliable appliances, service and growth',
                    'hero_subtitle' => 'From products to nearby service-ready dealers, we help you choose faster and safer.',
                    'hero_text' => 'Focused product sourcing, transparent enquiry management, and an expanding dealer footprint.',
                    'metrics' => [
                        ['value' => '+250', 'label' => 'local support interactions'],
                        ['value' => '24/7', 'label' => 'enquiry support'],
                        ['value' => '5+', 'label' => 'provinces covered'],
                    ],
                    'mission_points' => [
                        'Build a verified dealer network with location clarity and fast directions.',
                        'Provide transparent product details and dependable after-sales guidance.',
                        'Enable quick communication from enquiry to installation follow-up.',
                    ],
                    'milestones' => [
                        ['year' => '2016', 'label' => 'Company founded', 'text' => 'Started with a focus on practical electronics distribution for households and small businesses.'],
                        ['year' => '2018', 'label' => 'Nationwide dealer growth', 'text' => 'Expanded our authorized service network across every province with trusted technicians.'],
                        ['year' => '2021', 'label' => 'Digital-first workflow', 'text' => 'Introduced digital catalog and enquiry routing to speed up response times.'],
                        ['year' => '2024', 'label' => 'Modernized customer support', 'text' => 'Rolled out unified support and location-aware dealer discovery for customers.'],
                    ],
                ],
                'gallery_items' => [
                    ['title' => 'About us', 'summary' => 'Milestones, culture, and service commitments.', 'media' => 'section-about.svg'],
                    ['title' => 'Product Showcase', 'summary' => 'Premium appliances ready for smart home setups.', 'media' => 'hero-showroom.jpg'],
                    ['title' => 'Events & Workshops', 'summary' => 'Regional launches, training, and community sessions.', 'media' => 'section-events.svg'],
                    ['title' => 'Service Support Team', 'summary' => 'Technicians delivering safe installation and configuration.', 'media' => 'section-contact.svg'],
                ],
                'events_items' => [
                    ['title' => 'Annual Dealer Connect 2026', 'date' => '2026-05-20', 'venue' => 'Kathmandu Convention Center', 'status' => 'Upcoming', 'description' => 'Technology updates, sales goals, and training sessions for authorized dealers.'],
                    ['title' => 'Home Appliance Experience Week', 'date' => '2026-06-12', 'venue' => 'Lalitpur Exhibition Hall', 'status' => 'Upcoming', 'description' => 'Live demonstrations, energy-saving guidance, and financing tips.'],
                    ['title' => 'Dealer Service Excellence Workshop', 'date' => '2026-04-03', 'venue' => 'Pokhara Training Center', 'status' => 'Completed', 'description' => 'Skill sessions on installation, troubleshooting, and customer service.'],
                ],
                'careers_items' => [
                    ['title' => 'Sales Executive', 'type' => 'Full-time', 'location' => 'Kathmandu', 'description' => 'Drive product inquiries, coordinate with dealers, and nurture leads to closure.', 'closing' => 'Apply by July 30, 2026'],
                    ['title' => 'Service Coordinator', 'type' => 'Full-time', 'location' => 'Lalitpur', 'description' => 'Coordinate installation schedules and support teams for completed sales.', 'closing' => 'Apply by July 30, 2026'],
                    ['title' => 'Frontend Developer', 'type' => 'Contract', 'location' => 'Remote', 'description' => 'Help improve our product catalog and enquiry interfaces.', 'closing' => 'Rolling interviews'],
                ],
                'contact_page' => [
                    'office_title' => 'Head Office',
                    'hours' => 'Mon-Sat, 9:00 AM - 7:00 PM',
                    'address' => 'Janabahal, Kathmandu, Nepal',
                    'phone' => '+977-9849124657',
                    'email' => 'info@pratibuddha.com',
                    'social_links' => [
                        'facebook' => 'https://www.facebook.com/',
                        'instagram' => 'https://www.instagram.com/',
                        'youtube' => 'https://www.youtube.com/',
                        'tiktok' => 'https://www.tiktok.com/',
                        'linkedin' => 'https://www.linkedin.com/',
                        'twitter_x' => 'https://x.com/',
                        'viber' => 'viber://chat?number=+9779849124657',
                        'whatsapp' => 'https://wa.me/9779849124657',
                        'telegram' => 'https://t.me/',
                    ],
                ],
            ]
        );

        $locations = [
            'Bagmati' => [
                'Kathmandu' => [
                    'New Road' => [
                        [
                            'name' => 'Pratibuddha Electronics New Road',
                            'contact_number' => '01-4223344',
                            'email' => 'newroad@pratibuddha.com',
                            'address' => 'New Road, Kathmandu',
                            'ward' => '1',
                            'street_tole' => 'Bhimsengola Street',
                            'latitude' => 27.7172,
                            'longitude' => 85.3240,
                        ],
                        [
                            'name' => 'Lalitpur Home Tech Mall',
                            'contact_number' => '01-552211',
                            'email' => 'lalitpur@pratibuddha.com',
                            'address' => 'Kupondole, Lalitpur',
                            'ward' => '5',
                            'street_tole' => 'Jawalakhel Tole',
                            'latitude' => 27.6684,
                            'longitude' => 85.3185,
                        ],
                    ],
                    'Maharajgunj' => [
                        [
                            'name' => 'Maharajgunj Appliances',
                            'contact_number' => '01-433889',
                            'email' => 'maharajgunj@pratibuddha.com',
                            'address' => 'Maharajgunj, Kathmandu',
                            'ward' => '10',
                            'street_tole' => 'Maharajgunj Chowk',
                            'latitude' => 27.7060,
                            'longitude' => 85.3298,
                        ],
                    ],
                    'Koteshwor' => [
                        [
                            'name' => 'Koteshwor Electronics World',
                            'contact_number' => '01-499112',
                            'email' => 'koteshwor@pratibuddha.com',
                            'address' => 'Koteshwor, Kathmandu',
                            'ward' => '12',
                            'street_tole' => 'Koteshwor Road',
                            'latitude' => 27.6691,
                            'longitude' => 85.3605,
                        ],
                    ],
                ],
                'Lalitpur' => [
                    'Jawalakhel' => [
                        [
                            'name' => 'Jawalakhel Smart House',
                            'contact_number' => '01-553212',
                            'email' => 'jawalakhel@pratibuddha.com',
                            'address' => 'Jawalakhel, Lalitpur',
                            'ward' => '10',
                            'street_tole' => 'Ring Road Tole',
                            'latitude' => 27.6624,
                            'longitude' => 85.3158,
                        ],
                    ],
                    'Kumaripati' => [
                        [
                            'name' => 'Kumaripati Smart Store',
                            'contact_number' => '01-552211',
                            'email' => 'kumaripati@pratibuddha.com',
                            'address' => 'Kumaripati, Lalitpur',
                            'ward' => '7',
                            'street_tole' => 'Sundhara Tole',
                            'latitude' => 27.6762,
                            'longitude' => 85.3126,
                        ],
                    ],
                ],
                'Bhaktapur' => [
                    'Suryabinayak' => [
                        [
                            'name' => 'Bhaktapur Home Appliance Hub',
                            'contact_number' => '01-661223',
                            'email' => 'bhaktapur@pratibuddha.com',
                            'address' => 'Suryabinayak, Bhaktapur',
                            'ward' => '2',
                            'street_tole' => 'Mahendragunj Marg',
                            'latitude' => 27.6710,
                            'longitude' => 85.4277,
                        ],
                    ],
                ],
            ],
            'Gandaki' => [
                'Kaski' => [
                    'Lakeside' => [
                        [
                            'name' => 'Pokhara Premium Electro',
                            'contact_number' => '061-554433',
                            'email' => 'lakeside@pratibuddha.com',
                            'address' => 'Lakeside, Pokhara',
                            'ward' => '6',
                            'street_tole' => 'Phewa Marg',
                            'latitude' => 28.2096,
                            'longitude' => 83.9856,
                        ],
                    ],
                    'Mahendrapool' => [
                        [
                            'name' => 'Mahendrapool Electronics Mart',
                            'contact_number' => '061-555900',
                            'email' => 'mahendrapool@pratibuddha.com',
                            'address' => 'Mahendrapool, Pokhara',
                            'ward' => '4',
                            'street_tole' => 'Pashupati Tole',
                            'latitude' => 28.2012,
                            'longitude' => 83.9794,
                        ],
                    ],
                ],
            ],
            'Lumbini' => [
                'Rupandehi' => [
                    'Butwal' => [
                        [
                            'name' => 'Butwal Home Appliances',
                            'contact_number' => '071-550011',
                            'email' => 'butwal@pratibuddha.com',
                            'address' => 'Butwal Main Road, Rupandehi',
                            'ward' => '4',
                            'street_tole' => 'Siddhartha Chowk',
                            'latitude' => 27.6977,
                            'longitude' => 83.4484,
                        ],
                    ],
                    'Bhairahawa' => [
                        [
                            'name' => 'Bhairahawa Digital Center',
                            'contact_number' => '071-554778',
                            'email' => 'bhairahawa@pratibuddha.com',
                            'address' => 'Bhairahawa, Rupandehi',
                            'ward' => '11',
                            'street_tole' => 'Maitighar Tole',
                            'latitude' => 27.5068,
                            'longitude' => 83.4584,
                        ],
                    ],
                ],
            ],
        ];

        foreach ($locations as $provinceName => $districts) {
            $province = Province::firstOrCreate(['name' => $provinceName]);

            foreach ($districts as $districtName => $localLevels) {
                $district = District::firstOrCreate([
                    'name' => $districtName,
                    'province_id' => $province->id,
                ]);

                foreach ($localLevels as $localLevelName => $dealers) {
                    $localLevel = LocalLevel::firstOrCreate([
                        'name' => $localLevelName,
                        'zone_id' => $district->id,
                    ]);

                    foreach ($dealers as $dealerData) {
                        Dealer::updateOrCreate(
                            [
                                'name' => $dealerData['name'],
                                'area_id' => $localLevel->id,
                            ],
                            [
                                'contact_number' => $dealerData['contact_number'],
                                'email' => $dealerData['email'],
                                'address' => $dealerData['address'],
                                'ward' => $dealerData['ward'],
                                'street_tole' => $dealerData['street_tole'],
                                'latitude' => $dealerData['latitude'],
                                'longitude' => $dealerData['longitude'],
                                'is_active' => true,
                            ]
                        );
                    }
                }
            }
        }

        $products = [
            [
                'title' => 'Ultra Slim 4K OLED TV - 55\"',
                'category' => 'Home Entertainment',
                'price' => 125000,
                'description' => 'Experience true blacks and vibrant colors with our latest OLED technology. Features Dolby Atmos and smart AI integration.',
                'specifications' => [
                    'Resolution' => '3840 x 2160',
                    'Refresh Rate' => '120Hz',
                    'HDMI Ports' => '4 (v2.1)',
                    'Smart OS' => 'WebOS Pro',
                ],
            ],
            [
                'title' => 'Smart Inverter Refrigerator - 350L',
                'category' => 'Kitchen Appliances',
                'price' => 65000,
                'description' => 'Dual cooling system with smart inverter compressor for maximum energy efficiency. Keeps food fresh for up to 14 days.',
                'specifications' => [
                    'Capacity' => '350 Liters',
                    'Energy Rating' => '5 Star',
                    'Compressor' => 'Smart Inverter',
                    'Color' => 'Platinum Silver',
                ],
            ],
            [
                'title' => 'Turbo Steam Front Load Washing Machine',
                'category' => 'Home Appliances',
                'price' => 58000,
                'description' => 'Gentle on clothes, tough on stains. 9kg capacity with AI Direct Drive technology and Steam+ for allergy care.',
                'specifications' => [
                    'Capacity' => '9kg',
                    'Max Spin Speed' => '1400 RPM',
                    'Programs' => '14 Wash Modes',
                    'Warranty' => '10 Years on Motor',
                ],
            ],
            [
                'title' => 'Convection Microwave Oven - 28L',
                'category' => 'Kitchen Appliances',
                'price' => 22000,
                'description' => 'All-in-one cooking companion. Grill, bake, and reheat quickly with auto cook modes.',
                'specifications' => [
                    'Volume' => '28 Liters',
                    'Power' => '900W',
                    'Features' => 'Autocook Menu',
                    'Control' => 'Touch & Dial',
                ],
            ],
            [
                'title' => 'Dual Inverter Split Air Conditioner - 1.5 Ton',
                'category' => 'Climate Control',
                'price' => 88000,
                'description' => 'Fast cooling in hot summers with 4-in-1 convertible cooling and R32 eco-friendly refrigerant.',
                'specifications' => [
                    'Tonnage' => '1.5 Ton',
                    'Cooling Capacity' => '5200W',
                    'Refrigerant' => 'R32',
                    'Noise Level' => '31 dB',
                ],
            ],
            [
                'title' => 'Built-in Microwave - 23L',
                'category' => 'Kitchen Appliances',
                'price' => 18000,
                'description' => 'Built to match modern kitchens with preheat, grill and timer functions.',
                'specifications' => [
                    'Volume' => '23 Liters',
                    'Warranty' => '1 Year',
                    'Power' => '800W',
                    'Type' => 'Single Side Vents',
                ],
            ],
            [
                'title' => 'Wireless Soundbar Family Pack',
                'category' => 'Home Entertainment',
                'price' => 34000,
                'description' => 'Crisp surround sound with wireless subwoofer support and DTS compatibility.',
                'specifications' => [
                    'Channels' => '2.1',
                    'Bluetooth' => 'Yes',
                    'Output' => '120W',
                    'Connectivity' => 'Bluetooth 5.3, AUX, USB',
                ],
            ],
            [
                'title' => 'Energy Saver Ceiling Fan - Smart',
                'category' => 'Climate Control',
                'price' => 7800,
                'description' => 'Silent, low-power, and remote-controlled ceiling fan designed for everyday comfort.',
                'specifications' => [
                    'Wattage' => '45W',
                    'Control' => 'Remote + Timer',
                    'Speed Settings' => '5',
                    'Frame' => 'Brushless',
                ],
            ],
            [
                'title' => 'Portable Air Cooler',
                'category' => 'Climate Control',
                'price' => 16000,
                'description' => 'Compact and energy-efficient air cooler with a large ice tray and remote controls.',
                'specifications' => [
                    'Capacity' => '120L',
                    'Tank Capacity' => '6 Liters',
                    'Nozzle' => 'Auto Mist',
                    'Power' => '120W',
                ],
            ],
            [
                'title' => 'Wireless Router AX 1800',
                'category' => 'Electronics',
                'price' => 11900,
                'description' => 'Dual band router with Wi-Fi 6 support, ideal for home-office and smart home devices.',
                'specifications' => [
                    'Standard' => 'Wi-Fi 6',
                    'Speed' => '1800 Mbps',
                    'Coverage' => 'Up to 2500 sq ft',
                    'Warranty' => '1 Year',
                ],
            ],
        ];

        foreach ($products as $productData) {
            $slug = Str::slug($productData['title']);
            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $productData['title'],
                    'category' => $productData['category'],
                    'price' => $productData['price'],
                    'description' => $productData['description'],
                    'specifications' => $productData['specifications'],
                    'image_path' => null,
                ]
            );
        }

        $productIds = Product::query()->pluck('id', 'slug');
        $demoEnquiries = [
            [
                'product_slug' => 'ultra-slim-4k-oled-tv-55',
                'name' => 'Sita Sharma',
                'email' => 'sita.sharma@email.com',
                'phone' => '9800000001',
                'subject' => 'Bulk order for living room setup',
                'message' => 'Need a quote for two 55" units with installation support and delivery in Kathmandu.',
                'status' => 'new',
            ],
            [
                'product_slug' => 'smart-inverter-refrigerator-350l',
                'name' => 'Ram Kumar',
                'email' => 'ram.kumar@email.com',
                'phone' => '9800000002',
                'subject' => 'Warranty enquiry',
                'message' => 'What is the full warranty duration and service coverage for Butwal area?',
                'status' => 'in_progress',
            ],
            [
                'product_slug' => 'dual-inverter-split-air-conditioner-1.5-ton',
                'name' => 'Anita Rai',
                'email' => 'anita.rai@email.com',
                'phone' => '9800000003',
                'subject' => 'Dealer support request',
                'message' => 'Please suggest best dealer and installation slots in New Road, Kathmandu.',
                'status' => 'new',
            ],
        ];

        foreach ($demoEnquiries as $enquiryData) {
            $productId = $productIds[$enquiryData['product_slug']] ?? null;

            Enquiry::updateOrCreate(
                [
                    'name' => $enquiryData['name'],
                    'email' => $enquiryData['email'],
                    'phone' => $enquiryData['phone'],
                ],
                [
                    'product_id' => $productId,
                    'subject' => $enquiryData['subject'],
                    'message' => $enquiryData['message'],
                    'status' => $enquiryData['status'],
                ]
            );
        }
    }
}
