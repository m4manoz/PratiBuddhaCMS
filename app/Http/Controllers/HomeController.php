<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Province;
use App\Models\SiteSetting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(6)->get();
        $brands = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('welcome', compact('products', 'brands'));
    }

    public function about()
    {
        $siteSettings = SiteSetting::getSettings();
        $defaultMilestones = [
            ['year' => '2016', 'label' => 'Company founded', 'text' => 'Started with a focus on practical electronics distribution for households and small businesses.'],
            ['year' => '2018', 'label' => 'Nationwide dealer growth', 'text' => 'Expanded our authorized service network across every province with trusted technicians.'],
            ['year' => '2021', 'label' => 'Digital-first workflow', 'text' => 'Introduced digital catalog and enquiry routing to speed up response times.'],
            ['year' => '2024', 'label' => 'Modernized customer support', 'text' => 'Rolled out unified support and location-aware dealer discovery for customers.'],
        ];

        $aboutSettings = $siteSettings->about_page ?? [];
        $milestones = $aboutSettings['milestones'] ?? $defaultMilestones;
        $missionItems = $aboutSettings['mission_points'] ?? [];
        $metrics = $aboutSettings['metrics'] ?? [
            ['value' => '+250', 'label' => 'local support interactions'],
            ['value' => '24/7', 'label' => 'enquiry support'],
            ['value' => '5+', 'label' => 'provinces covered'],
        ];

        return view('about', compact('aboutSettings', 'milestones', 'missionItems', 'metrics'));
    }

    public function gallery()
    {
        $siteSettings = SiteSetting::getSettings();

        $defaultItems = [
            ['title' => 'About us', 'summary' => 'Milestones, culture, and service commitments.', 'media' => 'section-about.svg'],
            ['title' => 'Product Showcase', 'summary' => 'Premium appliances ready for smart home setups.', 'media' => 'hero-showroom.jpg'],
            ['title' => 'Events & Workshops', 'summary' => 'Regional launches, training, and community sessions.', 'media' => 'section-events.svg'],
            ['title' => 'Service Support Team', 'summary' => 'Technicians delivering safe installation and configuration.', 'media' => 'section-contact.svg'],
            ['title' => 'Careers', 'summary' => 'Growing team spots and talent building.', 'media' => 'section-careers.svg'],
            ['title' => 'Customer Consultation', 'summary' => 'Product advisory sessions for families and businesses.', 'media' => 'hero-interior.jpg'],
        ];

        $items = $siteSettings->gallery_items ?? $defaultItems;

        return view('gallery', compact('items'));
    }

    public function events()
    {
        $siteSettings = SiteSetting::getSettings();

        $defaultEvents = [
            [
                'title' => 'Annual Dealer Connect 2026',
                'date' => '2026-05-20',
                'venue' => 'Kathmandu Convention Center',
                'status' => 'Upcoming',
                'description' => 'Technology updates, sales goals, and training sessions for authorized dealers.',
            ],
            [
                'title' => 'Home Appliance Experience Week',
                'date' => '2026-06-12',
                'venue' => 'Lalitpur Exhibition Hall',
                'status' => 'Upcoming',
                'description' => 'Live demonstrations, energy-saving guidance, and financing tips.',
            ],
            [
                'title' => 'Dealer Service Excellence Workshop',
                'date' => '2026-04-03',
                'venue' => 'Pokhara Training Center',
                'status' => 'Completed',
                'description' => 'Skill sessions on installation, troubleshooting, and customer service.',
            ],
        ];

        $events = $siteSettings->events_items ?? $defaultEvents;

        return view('events', compact('events'));
    }

    public function careers()
    {
        $siteSettings = SiteSetting::getSettings();

        $defaultRoles = [
            [
                'title' => 'Sales Executive',
                'type' => 'Full-time',
                'location' => 'Kathmandu',
                'description' => 'Drive product inquiries, coordinate with dealers, and nurture leads to closure.',
                'closing' => 'Apply by July 30, 2026',
            ],
            [
                'title' => 'Service Coordinator',
                'type' => 'Full-time',
                'location' => 'Lalitpur',
                'description' => 'Coordinate installation schedules and support teams for completed sales.',
                'closing' => 'Apply by July 30, 2026',
            ],
            [
                'title' => 'Frontend Developer',
                'type' => 'Contract',
                'location' => 'Remote',
                'description' => 'Help improve our product catalog and enquiry interfaces.',
                'closing' => 'Rolling interviews',
            ],
        ];

        $openRoles = $siteSettings->careers_items ?? $defaultRoles;

        return view('careers', compact('openRoles'));
    }

    public function contact()
    {
        $siteSettings = SiteSetting::getSettings();

        $contactContent = $siteSettings->contact_page ?? [
            'office_title' => 'Head Office',
            'hours' => 'Mon-Sat, 9:00 AM - 7:00 PM',
            'address' => 'Janabahal, Kathmandu, Nepal',
            'phone' => '+977-9849124657',
            'email' => 'info@pratibuddha.com',
            'contact_person_name' => 'Sales & Support Team',
            'contact_person_role' => 'Customer Support',
            'contact_person_phone' => '+977-9849124657',
            'contact_person_email' => 'info@pratibuddha.com',
            'locations' => [
                [
                    'office_title' => 'Head Office',
                    'address' => 'Janabahal, Kathmandu, Nepal',
                    'phone' => '+977-9849124657',
                    'email' => 'info@pratibuddha.com',
                    'hours' => 'Mon-Sat, 9:00 AM - 7:00 PM',
                ],
                [
                    'office_title' => 'Warehouse',
                    'address' => 'Sitapaila, Kathmandu, Nepal',
                    'phone' => '+977-9849124657',
                    'email' => 'info@pratibuddha.com',
                    'hours' => 'Mon-Sat, 9:00 AM - 7:00 PM',
                ],
            ],
        ];

        $contactLocations = $contactContent['locations'] ?? [];

        if (! is_array($contactLocations) || empty($contactLocations)) {
            $contactLocations = [
                [
                    'office_title' => $contactContent['office_title'] ?? 'Head Office',
                    'address' => $contactContent['address'] ?? 'Janabahal, Kathmandu, Nepal',
                    'phone' => $contactContent['phone'] ?? '+977-9849124657',
                    'email' => $contactContent['email'] ?? 'info@pratibuddha.com',
                    'hours' => $contactContent['hours'] ?? 'Mon-Sat, 9:00 AM - 7:00 PM',
                ],
            ];
        }

        $contactLocations = array_values(array_map(function (array $location) use ($contactContent): array {
            return [
                'office_title' => $location['office_title'] ?? 'Office',
                'address' => $location['address'] ?? ($contactContent['address'] ?? 'Janabahal, Kathmandu, Nepal'),
                'phone' => $location['phone'] ?? ($contactContent['phone'] ?? '+977-9849124657'),
                'email' => $location['email'] ?? ($contactContent['email'] ?? 'info@pratibuddha.com'),
                'hours' => $location['hours'] ?? ($contactContent['hours'] ?? 'Mon-Sat, 9:00 AM - 7:00 PM'),
            ];
        }, $contactLocations));

        usort($contactLocations, function (array $a, array $b) {
            $aIsHead = stripos((string) $a['office_title'], 'head office') !== false;
            $bIsHead = stripos((string) $b['office_title'], 'head office') !== false;

            if ($aIsHead === $bIsHead) {
                return 0;
            }

            return $aIsHead ? -1 : 1;
        });

        return view('contact', compact('contactContent', 'contactLocations'));
    }

    public function products(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $brand = trim((string) $request->query('brand', ''));
        $sort = trim((string) $request->query('sort', 'newest'));
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $query = Product::query();
        $effectiveCategory = $category;

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = "%{$search}%";
                $q->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('category', 'like', $like);
            });
        }

        if ($brand !== '') {
            $effectiveCategory = $brand;
        }

        if (! empty($effectiveCategory)) {
            $query->where('category', $effectiveCategory);
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        match ($sort) {
            'name_asc' => $query->orderBy('title', 'asc'),
            'name_desc' => $query->orderByDesc('title'),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
        $brands = $categories;

        return view('products.index', compact('products', 'categories', 'brands', 'search', 'category', 'brand', 'sort', 'minPrice', 'maxPrice'));
    }

    public function productShow(Product $product)
    {
        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->latest()
            ->take(4)
            ->get();

        $availability = $product->price !== null && (float) $product->price > 0;

        return view('products.show', compact('product', 'relatedProducts', 'availability'));
    }

    public function storeEnquiry(Request $request, Product $product)
    {
        if ($request->filled('company_website')) {
            return back()->with('error', 'Unable to submit enquiry at this time.')->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:1500'],
        ]);

        Enquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'product_id' => $product->id,
            'status' => 'new',
        ]);

        return back()->with('enquiry_success', 'Your enquiry has been submitted successfully.');
    }

    public function storeContact(Request $request)
    {
        if ($request->filled('company_website')) {
            return back()->with('contact_error', 'Unable to submit contact message.')->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:12', 'max:2000'],
        ]);

        Enquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'] ?? 'General Inquiry',
            'message' => $validated['message'],
            'product_id' => null,
            'status' => 'new',
        ]);

        return back()->with('contact_success', 'Thanks for contacting us. Our team will respond shortly.');
    }

    public function dealers(Request $request)
    {
        $provinces = Province::with('districts.localLevels')->get();
        $initialFilters = [
            'province' => $request->query('province'),
            'district' => $request->query('district'),
            'local_level' => $request->query('local_level'),
            'ward' => trim((string) $request->query('ward', '')),
            'street_tole' => trim((string) $request->query('street_tole', '')),
            'q' => trim((string) $request->query('q', '')),
            'sort' => $request->query('sort', 'newest'),
            'radius' => $request->query('radius', ''),
        ];

        $locationFilters = $this->buildDealerLocationFilters();

        return view('dealers.index', compact('provinces', 'initialFilters', 'locationFilters'));
    }

    public function searchDealers(Request $request)
    {
        $provinceId = $request->integer('province');
        $districtId = $request->integer('district');
        $localLevelId = $request->integer('local_level');
        $ward = trim((string) $request->query('ward', ''));
        $streetTole = trim((string) $request->query('street_tole', ''));
        $search = trim((string) $request->query('q', ''));
        $sort = $request->string('sort')->toString();
        $radius = (float) $request->query('radius', 0);
        $latitude = $this->toNullableFloat($request->query('lat'));
        $longitude = $this->toNullableFloat($request->query('lng'));
        $hasCoordinates = $this->isValidCoordinate($latitude, $longitude);

        $appliedFilters = [
            'province' => $provinceId > 0 ? (string) $provinceId : null,
            'district' => $districtId > 0 ? (string) $districtId : null,
            'local_level' => $localLevelId > 0 ? (string) $localLevelId : null,
            'ward' => $ward !== '' ? $ward : null,
            'street_tole' => $streetTole !== '' ? $streetTole : null,
            'q' => $search !== '' ? $search : null,
            'sort' => $sort ?: 'newest',
            'radius' => $radius > 0 ? $radius : null,
            'lat' => $hasCoordinates ? (string) $latitude : null,
            'lng' => $hasCoordinates ? (string) $longitude : null,
        ];

        $query = Dealer::query()
            ->with('localLevel.district.province')
            ->where('is_active', true);

        if ($provinceId > 0) {
            $query->whereHas('localLevel.district', fn ($q) => $q->where('province_id', $provinceId));
        }

        if ($districtId > 0) {
            $query->whereHas('localLevel', fn ($q) => $q->where('zone_id', $districtId));
        }

        if ($localLevelId > 0) {
            $query->where('area_id', $localLevelId);
        }

        if ($ward !== '') {
            $query->where('ward', $ward);
        }

        if ($streetTole !== '') {
            $query->where('street_tole', $streetTole);
        }

        if ($search !== '') {
            $like = "%{$search}%";
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('contact_number', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        $usingSqlite = in_array(DB::connection()->getDriverName(), ['sqlite'], true);

        if ($hasCoordinates) {
            $query->select('dealers.*')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if (! $usingSqlite) {
                $query->selectRaw(
                    '(6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )) as distance',
                    [$latitude, $longitude, $latitude]
                );

                if ($radius > 0) {
                    $query->having('distance', '<=', $radius);
                }

                $query->orderBy('distance', 'asc');
            }
        } else {
            match ($sort) {
                'name_asc' => $query->orderBy('name', 'asc'),
                'name_desc' => $query->orderByDesc('name'),
                'nearest' => $this->applyDeterministicNearestSortFallback($query, $districtId, $localLevelId),
                default => $query->orderByDesc('created_at')->orderBy('name', 'asc'),
            };
        }

        if ($hasCoordinates && $usingSqlite) {
            $dealersCollection = $query->get();

            $dealersWithDistance = $dealersCollection->map(function (Dealer $dealer) use ($latitude, $longitude) {
                $dealer->distance = $this->haversineDistanceKm(
                    $latitude,
                    $longitude,
                    (float) $dealer->latitude,
                    (float) $dealer->longitude
                );

                return $dealer;
            });

            if ($radius > 0) {
                $dealersWithDistance = $dealersWithDistance->filter(
                    fn ($dealer) => $dealer->distance <= $radius
                );
            }

            $dealersWithDistance = $dealersWithDistance->sortBy('distance')->values();
            $dealers = $this->paginateCollection($dealersWithDistance, 12, (int) $request->query('page', 1), $request);
        } else {
            $dealers = $query->paginate(12)->withQueryString();
        }

        $dealers->through(function ($dealer) use ($hasCoordinates) {
            $payload = [
                'id' => $dealer->id,
                'name' => $dealer->name,
                'contact_number' => $dealer->contact_number,
                'email' => $dealer->email,
                'address' => $dealer->address,
                'ward' => $dealer->ward,
                'street_tole' => $dealer->street_tole,
                'latitude' => $dealer->latitude,
                'longitude' => $dealer->longitude,
                'local_level' => $dealer->localLevel->name ?? null,
                'district' => $dealer->localLevel->district->name ?? null,
                'province' => $dealer->localLevel->district->province->name ?? null,
                'area_id' => $dealer->area_id,
            ];

            if ($hasCoordinates && isset($dealer->distance)) {
                $payload['distance'] = round((float) $dealer->distance, 2);
            }

            return $payload;
        });

        return response()->json([
            'data' => $dealers->items(),
            'pagination' => [
                'current_page' => $dealers->currentPage(),
                'last_page' => $dealers->lastPage(),
                'per_page' => $dealers->perPage(),
                'total' => $dealers->total(),
                'next_page_url' => $dealers->nextPageUrl(),
                'prev_page_url' => $dealers->previousPageUrl(),
            ],
            'applied_filters' => $appliedFilters,
        ]);
    }

    private function paginateCollection($collection, int $perPage, int $page, Request $request): LengthAwarePaginator
    {
        $page = max(1, $page);
        $total = $collection->count();
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return (new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'pageName' => 'page',
            'query' => $request->query(),
        ]))->withPath($request->url())->withQueryString();
    }

    private function applyDeterministicNearestSortFallback($query, int $districtId, int $localLevelId): void
    {
        if ($localLevelId > 0) {
            $query->whereHas('localLevel', fn ($q) => $q->where('id', $localLevelId))
                ->orderBy('ward')
                ->orderBy('name', 'asc');

            return;
        }

        if ($districtId > 0) {
            $query->whereHas('localLevel', fn ($q) => $q->where('zone_id', $districtId))
                ->orderBy('area_id')
                ->orderBy('ward')
                ->orderBy('name', 'asc');

            return;
        }

        $query->orderByDesc('created_at')->orderBy('name', 'asc');
    }

    private function buildDealerLocationFilters(): array
    {
        $dealerRows = Dealer::query()
            ->with('localLevel.district')
            ->where('is_active', true)
            ->whereNotNull('area_id')
            ->get(['id', 'area_id', 'ward', 'street_tole']);

        $payload = [];

        foreach ($dealerRows as $dealer) {
            $localLevelId = (string) $dealer->area_id;
            $ward = trim((string) ($dealer->ward ?? ''));
            $streetTole = trim((string) ($dealer->street_tole ?? ''));

            if ($localLevelId === '' || $ward === '') {
                continue;
            }

            $payload[$localLevelId] ??= [
                'wards' => [],
                'streets_by_ward' => [],
            ];

            $payload[$localLevelId]['wards'][$ward] = true;
            if ($streetTole !== '') {
                $payload[$localLevelId]['streets_by_ward'][$ward] ??= [];
                $payload[$localLevelId]['streets_by_ward'][$ward][$streetTole] = true;
            }
        }

        $result = [];
        foreach ($payload as $localLevelId => $info) {
            $wards = array_values(array_map('strval', array_keys($info['wards'])));
            sort($wards);

            $streetsByWard = [];
            foreach ($info['streets_by_ward'] as $wardName => $streets) {
                $streetList = array_values(array_map('strval', array_keys($streets)));
                sort($streetList);
                $streetsByWard[$wardName] = $streetList;
            }

            $result[$localLevelId] = [
                'wards' => $wards,
                'streets_by_ward' => $streetsByWard,
            ];
        }

        return $result;
    }

    private function toNullableFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function isValidCoordinate(?float $latitude, ?float $longitude): bool
    {
        return $latitude !== null && $longitude !== null
            && $latitude >= -90 && $latitude <= 90
            && $longitude >= -180 && $longitude <= 180;
    }

    private function haversineDistanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * (sin($lngDelta / 2) ** 2);

        $c = 2 * asin(min(1, sqrt($a)));

        return $earthRadius * $c;
    }

}
