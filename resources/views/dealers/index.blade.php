@extends('layouts.app')
@section('title', 'Authorized Dealers | PRATIBUDDHA')

@section('content')
<section class="page-shell">
<div class="container">
<div class="page-title-wrap">
<p class="home-kicker">Authorized Dealer Network</p>
<h1>Find the nearest dealer in your area</h1>
<p>Search by province, district, and local level, then use live location for nearest results.</p>
</div>

<div class="dealer-panel">
<form id="dealer-filters" onsubmit="event.preventDefault(); fetchDealers(1);" class="dealer-filters">
<label class="filter-field dealer-search-field">
<span>Search</span>
<input id="dealer-search" type="text" class="form-control" placeholder="Search by name, local level, phone, email" value="{{ $initialFilters['q'] }}">
</label>
<label class="filter-field">
<span>Province</span>
<select id="province-select" class="form-control">
<option value="">All Provinces</option>
@foreach($provinces as $province)
<option value="{{ $province->id }}" {{ (string)$initialFilters['province'] === (string)$province->id ? 'selected' : '' }}>
{{ $province->name }}
</option>
@endforeach
</select>
</label>
<label class="filter-field">
<span>District</span>
<select id="district-select" class="form-control">
<option value="">All Districts</option>
</select>
</label>
<label class="filter-field">
<span>Location</span>
<select id="local-level-select" class="form-control">
<option value="">All Locations</option>
</select>
</label>
<label class="filter-field filter-field--compact">
<span>Radius (km)</span>
<input id="radius-km" type="number" min="1" max="200" class="form-control" value="{{ $initialFilters['radius'] ?? '' }}" placeholder="e.g. 20">
</label>
<label class="filter-field">
<span>Sort</span>
<select id="dealer-sort" class="form-control">
<option value="newest" {{ ($initialFilters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
<option value="name_asc" {{ ($initialFilters['sort'] ?? '') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
<option value="name_desc" {{ ($initialFilters['sort'] ?? '') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
<option value="nearest" {{ ($initialFilters['sort'] ?? '') === 'nearest' ? 'selected' : '' }}>Nearest</option>
</select>
</label>
<div class="dealer-filter-actions">
<button type="button" id="detect-location" class="btn btn-outline">Use My Location</button>
<button type="button" id="clear-filters" class="btn btn-outline">Clear filters</button>
<button type="submit" class="btn btn-primary">Search</button>
</div>
</form>
</div>

<p id="google-maps-banner" class="google-map-banner">Using free OpenStreetMap tiles for dealer locations.</p>

<div class="dealer-layout">
<div>
<div id="dealer-list" class="dealer-list" aria-live="polite"></div>
<div id="dealer-pagination" class="pagination-wrap"></div>
</div>
<div class="dealer-map-wrap">
<div id="dealer-map" class="dealer-map"></div>
<p id="dealer-map-empty" class="dealer-map-empty">Map is waiting for available location data.</p>
</div>
</div>
</div>
</section>

<div id="call-options-dialog" class="call-options-dialog" aria-hidden="true">
    <div class="call-options-overlay" data-call-dialog-close></div>
    <div class="call-options-panel" role="dialog" aria-modal="true" aria-labelledby="call-dialog-title">
        <h3 id="call-dialog-title">Call Dealer</h3>
        <p id="call-dialog-subtitle">Choose a communication method.</p>
        <div class="call-option-buttons">
            <a id="call-dialog-viber" href="#" class="btn btn-primary">Viber</a>
            <a id="call-dialog-sim" href="#" class="btn btn-outline">Call via SIM Dialer</a>
            <a id="call-dialog-whatsapp" href="#" class="btn btn-outline">WhatsApp</a>
        </div>
        <button id="call-dialog-close" type="button" class="btn btn-outline">Cancel</button>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .dealer-filters {
        grid-template-columns: repeat(6, minmax(140px, 1fr));
    }

    .dealer-filters .form-control {
        height: 42px;
    }

    .filter-field--compact {
        max-width: 180px;
    }

    .dealer-search-field {
        max-width: 280px;
    }

    .filter-field--compact .form-control {
        max-width: 150px;
        padding: 0.52rem 0.65rem;
        height: 38px;
    }

    .dealer-filter-actions {
        justify-content: flex-end;
        justify-self: end;
        grid-column: 1 / -1;
        align-self: end;
        display: flex;
        gap: 0.6rem;
    }

    .dealer-filter-actions .btn {
        white-space: nowrap;
    }

    .call-options-dialog {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(2, 6, 23, 0.35);
        pointer-events: auto;
    }

    .call-options-dialog.is-open {
        display: flex;
    }

    .call-options-overlay {
        position: absolute;
        inset: 0;
        cursor: pointer;
        z-index: 0;
    }

    .call-options-panel {
        position: relative;
        z-index: 1;
        width: min(95%, 420px);
        background: #fff;
        border-radius: 0.85rem;
        padding: 1rem;
        border: 1px solid var(--line);
        box-shadow: var(--shadow-md);
        display: grid;
        gap: 0.7rem;
        transform: translateY(0);
    }

    .call-option-buttons {
        display: grid;
        gap: 0.6rem;
        margin-top: 0.2rem;
    }

    @media (max-width: 1024px) {
        .dealer-search-field {
            max-width: none;
        }

        .filter-field--compact {
            max-width: none;
        }

        .dealer-filter-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
const provinces = @json($provinces);
const defaultFilters = @json($initialFilters);
const provinceSelect = document.getElementById('province-select');
const districtSelect = document.getElementById('district-select');
const localLevelSelect = document.getElementById('local-level-select');
const dealerList = document.getElementById('dealer-list');
const paginationContainer = document.getElementById('dealer-pagination');
const mapElement = document.getElementById('dealer-map');
const mapBanner = document.getElementById('google-maps-banner');
const mapEmptyText = document.getElementById('dealer-map-empty');
const dealerSearch = document.getElementById('dealer-search');
const dealerSort = document.getElementById('dealer-sort');
const radiusInput = document.getElementById('radius-km');
const detectLocationBtn = document.getElementById('detect-location');
const clearFiltersBtn = document.getElementById('clear-filters');
const preservedWardFilter = new URLSearchParams(window.location.search).get('ward') || '';
const preservedStreetToleFilter = new URLSearchParams(window.location.search).get('street_tole') || '';
let mapInitialized = false;
let markers = new Map();
let activeMarkerPopup = null;
let currentPosition = { lat: null, lng: null };

let map;

function initSelectState() {
if (defaultFilters.province) provinceSelect.value = defaultFilters.province;
if (defaultFilters.district) districtSelect.value = defaultFilters.district;
if (defaultFilters.local_level) localLevelSelect.value = defaultFilters.local_level;
}

function provinceChanged() {
const provinceId = provinceSelect.value;
districtSelect.innerHTML = '<option value="">All Districts</option>';
localLevelSelect.innerHTML = '<option value="">All Local Levels</option>';

const selectedProvince = provinces.find(p => String(p.id) === String(provinceId));
if (!selectedProvince) {
districtSelect.disabled = true;
localLevelSelect.disabled = true;
return;
}
districtSelect.disabled = false;

selectedProvince.districts.forEach(district => {
const option = document.createElement('option');
option.value = district.id;
option.textContent = district.name;
districtSelect.appendChild(option);
});

if (defaultFilters.district) {
districtSelect.value = defaultFilters.district;
}
districtChanged();
}

function districtChanged() {
const districtId = districtSelect.value;
localLevelSelect.innerHTML = '<option value="">All Local Levels</option>';

const selectedProvince = provinces.find(p => String(p.id) === String(provinceSelect.value));
if (!selectedProvince) {
localLevelSelect.disabled = true;
return;
}
localLevelSelect.disabled = false;

const selectedDistrict = selectedProvince.districts.find(d => String(d.id) === String(districtId));
if (!selectedDistrict) {
localLevelSelect.disabled = true;
return;
}
localLevelSelect.disabled = false;

selectedDistrict.local_levels.forEach(localLevel => {
const option = document.createElement('option');
option.value = localLevel.id;
option.textContent = localLevel.name;
localLevelSelect.appendChild(option);
});

if (defaultFilters.local_level) {
localLevelSelect.value = defaultFilters.local_level;
}
localLevelChanged();
}

function localLevelChanged() {
if (!localLevelSelect.value) {
return;
}
}

function clearFilterDefaults() {
    defaultFilters.province = '';
    defaultFilters.district = '';
    defaultFilters.local_level = '';
}

function markDealerCard(dealerId, isActive) {
document.querySelectorAll('.dealer-card').forEach((card) => {
card.classList.toggle('is-active', String(card.dataset.dealerId) === String(dealerId) && isActive);
});
}

function showDealerOnMap(dealerId, shouldCenter = true) {
const marker = markers.get(String(dealerId));
if (!marker || !marker.marker) {
return;
}

if (activeMarkerPopup) {
activeMarkerPopup.closePopup();
}

activeMarkerPopup = marker.marker;
marker.marker.openPopup();
markDealerCard(dealerId, true);

if (shouldCenter && marker.hasCoordinates && marker.position && marker.position.length === 2) {
if (typeof map !== 'undefined' && map?.invalidateSize) {
map.invalidateSize();
}
const zoom = map?.getZoom ? Math.max(map.getZoom(), 13) : 13;
map.setView(marker.position, zoom, { animate: true });
}
}

function hideDealerSelection() {
if (activeMarkerPopup) {
activeMarkerPopup.closePopup();
}
markDealerCard(null, false);
}

    function renderDealerCards(dealers) {
        if (!dealers.length) {
            dealerList.innerHTML = '<div class="dealer-empty">No dealers found for the selected criteria.</div>';
            return;
        }

        dealerList.innerHTML = dealers.map(dealer => `
            <article class="dealer-card" data-dealer-id="${dealer.id}">
                <h3>${dealer.name}</h3>
                <p><i class="fa-solid fa-location-dot" aria-hidden="true"></i> ${dealer.address ?? ''}</p>
                <p>${dealer.local_level ?? ''}${dealer.district ? ' • ' + dealer.district : ''}${dealer.province ? ' • ' + dealer.province : ''}</p>
                <p><strong>Ward:</strong> ${dealer.ward ?? ''} | <strong>Street/Tole:</strong> ${dealer.street_tole ?? ''}</p>
                ${dealer.distance !== undefined ? `<p class=\"dealer-distance\">~ ${dealer.distance} km away</p>` : ''}
                <div class="dealer-actions">
                    ${dealer.contact_number ? `<a href="#" class=\"dealer-call-link\" data-dealer-name=\"${dealer.name}\" data-dealer-phone=\"${dealer.contact_number}\" aria-label=\"Call ${dealer.name}\"><i class=\"fa-solid fa-phone\" aria-hidden=\"true\"></i>Call now</a>` : ''}
                    ${dealer.email ? `<a href=\"mailto:${dealer.email}\"><i class=\"fa-solid fa-envelope\" aria-hidden=\"true\"></i>${dealer.email}</a>` : ''}
                    ${dealer.latitude && dealer.longitude ? `
                        <a href=\"#\"
                           class=\"dealer-direction-link\"
                           data-dealer-id=\"${dealer.id}\"
                           data-dealer-lat=\"${dealer.latitude}\"
                           data-dealer-lng=\"${dealer.longitude}\"
                           aria-label=\"Get directions to ${dealer.name}\">
                            <i class=\"fa-solid fa-location-arrow\" aria-hidden=\"true\"></i>Get directions
                        </a>` : ''}
                </div>
            </article>
        `).join('');

        document.querySelectorAll('.dealer-card').forEach((card) => {
            const dealerId = card.dataset.dealerId;

            card.addEventListener('mouseenter', () => {
                showDealerOnMap(dealerId, false);
            });

            card.addEventListener('focusin', () => {
                showDealerOnMap(dealerId, false);
            });

            card.addEventListener('click', (event) => {
                if (event.defaultPrevented) {
                    return;
                }
                showDealerOnMap(dealerId, true);
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        document.querySelectorAll('.dealer-call-link').forEach((callLink) => {
            callLink.addEventListener('click', (event) => {
                event.preventDefault();
                const phone = callLink.dataset.dealerPhone || '';
                const name = callLink.dataset.dealerName || '';
                openCallOptions(phone, name);
            });
        });

        document.querySelectorAll('.dealer-direction-link').forEach((directionLink) => {
            directionLink.addEventListener('click', (event) => {
                event.preventDefault();
                const linkedDealerId = directionLink.dataset.dealerId;
                const lat = Number(directionLink.dataset.dealerLat);
                const lng = Number(directionLink.dataset.dealerLng);
                const linkedCard = document.querySelector(`.dealer-card[data-dealer-id=\"${linkedDealerId}\"]`);
                const hasCurrentLocation = Number.isFinite(currentPosition.lat) && Number.isFinite(currentPosition.lng);

                if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                    if (linkedCard) {
                        linkedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                if (!hasCurrentLocation) {
                    alert('Use My Location once to set your current location, then click Get directions again for a route from your position.');
                }

                window.open(`https://www.openstreetmap.org/directions?from=${hasCurrentLocation ? `${encodeURIComponent(currentPosition.lat)},${encodeURIComponent(currentPosition.lng)}` : ''}&to=${encodeURIComponent(lat)},${encodeURIComponent(lng)}&route=` + (hasCurrentLocation ? 'car' : ''), '_blank', 'noopener');

                showDealerOnMap(linkedDealerId, true);
                if (mapElement) {
                    mapElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                if (map) {
                    map.invalidateSize();
                }
                if (linkedCard) {
                    linkedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }

function sanitizePhone(rawPhone) {
return String(rawPhone || '').replace(/[^+\d]/g, '');
}

function dealerContactMessage(dealerName = 'Dealer') {
return `Hi, I need to connect with ${dealerName} regarding products and dealership details.`;
}

function openCallOptions(rawPhone, dealerName) {
const normalized = sanitizePhone(rawPhone);
const digitsOnly = normalized.replace(/\D/g, '');
const message = dealerContactMessage(dealerName);

const dialog = document.getElementById('call-options-dialog');
const title = document.getElementById('call-dialog-title');
const subtitle = document.getElementById('call-dialog-subtitle');
const simButton = document.getElementById('call-dialog-sim');
const whatsappButton = document.getElementById('call-dialog-whatsapp');
const viberButton = document.getElementById('call-dialog-viber');

if (!dialog || !title || !subtitle || !simButton || !whatsappButton || !viberButton) {
window.location.href = `tel:${normalized}`;
return;
}

title.textContent = `Call ${dealerName}`;
subtitle.textContent = `Connect using ${normalized} via your preferred method.`;
simButton.href = `tel:${normalized}`;
whatsappButton.href = `https://wa.me/${digitsOnly}?text=${encodeURIComponent(message)}`;
whatsappButton.target = '_blank';
whatsappButton.rel = 'noopener';
viberButton.href = `viber://chat?number=${encodeURIComponent(normalized)}&text=${encodeURIComponent(message)}`;

dialog.classList.add('is-open');
dialog.setAttribute('aria-hidden', 'false');
document.body.style.overflow = 'hidden';
}

function closeCallOptions() {
const dialog = document.getElementById('call-options-dialog');

if (dialog) {
dialog.classList.remove('is-open');
dialog.setAttribute('aria-hidden', 'true');
document.body.style.overflow = '';
}
}

function renderPagination(pagination) {
paginationContainer.innerHTML = '';
if (!pagination) return;

const createPageBtn = (label, pageNumber, disabled = false, active = false) => {
const btn = document.createElement('button');
btn.type = 'button';
btn.textContent = label;
btn.disabled = disabled;
btn.className = `page-btn ${active ? 'is-active' : ''}`;
if (!disabled && !active) {
btn.addEventListener('click', () => fetchDealers(pageNumber));
}
return btn;
};

paginationContainer.appendChild(createPageBtn('Prev', pagination.current_page - 1, !pagination.prev_page_url));

for (let p = 1; p <= pagination.last_page; p++) {
if (p === 1 || p === pagination.last_page || (p >= pagination.current_page - 1 && p <= pagination.current_page + 1)) {
paginationContainer.appendChild(createPageBtn(String(p), p, false, p === pagination.current_page));
}
}

paginationContainer.appendChild(createPageBtn('Next', pagination.current_page + 1, !pagination.next_page_url));
}

function renderMap(dealers) {
mapBanner.style.display = 'none';
mapElement.style.display = 'block';
const validCoordinates = dealers.filter(d => d.latitude !== null && d.longitude !== null);

const getCoord = (value) => {
const num = Number(value);
return Number.isFinite(num) ? num : null;
};

const normalizedCoords = validCoordinates.map((dealer) => {
const lat = getCoord(dealer.latitude);
const lng = getCoord(dealer.longitude);

if (lat === null || lng === null) {
return null;
}

return { dealer, lat, lng };
}).filter(Boolean);

if (!map) {
map = L.map('dealer-map', {
scrollWheelZoom: false,
}).setView([27.7172, 85.3240], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
maxZoom: 19,
attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',
}).addTo(map);
} else if (!mapInitialized) {
map.setView([27.7172, 85.3240], 10);
}

markers.forEach(markerData => {
markerData.marker.remove();
});
markers = new Map();
if (activeMarkerPopup) {
activeMarkerPopup.closePopup();
}

if (!normalizedCoords.length) {
mapEmptyText.style.display = 'block';
mapEmptyText.textContent = 'No dealers with coordinates match current filters.';
return;
}

mapEmptyText.style.display = 'none';
const bounds = L.latLngBounds();
mapInitialized = true;

normalizedCoords.forEach(({ dealer, lat, lng }) => {
const position = [lat, lng];
const marker = L.marker(position);
const markerContent = `<strong>${dealer.name}</strong><br>${dealer.address || ''}<br>${dealer.contact_number || ''}`;
marker.bindPopup(markerContent);
marker.on('click', () => {
showDealerOnMap(dealer.id, false);
});

markers.set(String(dealer.id), {
marker,
position: [lat, lng],
hasCoordinates: true,
});
marker.addTo(map);
bounds.extend(position);
});

if (normalizedCoords.length > 1) {
map.fitBounds(bounds, { padding: [30, 30] });
} else {
map.setView(normalizedCoords[0].dealer ? [getCoord(normalizedCoords[0].dealer.latitude), getCoord(normalizedCoords[0].dealer.longitude)] : [27.7172, 85.3240], 13);
}
}

async function fetchDealers(page = 1) {
const payload = new URLSearchParams();
if (provinceSelect.value) payload.set('province', provinceSelect.value);
if (districtSelect.value) payload.set('district', districtSelect.value);
if (localLevelSelect.value) payload.set('local_level', localLevelSelect.value);
if (dealerSearch.value) payload.set('q', dealerSearch.value);
if (dealerSort.value) payload.set('sort', dealerSort.value);
if (preservedWardFilter) {
payload.set('ward', preservedWardFilter);
}
if (preservedStreetToleFilter) {
payload.set('street_tole', preservedStreetToleFilter);
}
if (currentPosition.lat !== null && currentPosition.lng !== null && currentPosition.lat !== undefined && currentPosition.lng !== undefined) {
payload.set('lat', currentPosition.lat);
payload.set('lng', currentPosition.lng);
}
if (radiusInput.value) payload.set('radius', radiusInput.value);
payload.set('page', page);

const response = await fetch(`{{ route('dealers.search') }}?${payload.toString()}`, {
headers: { 'Accept': 'application/json' }
});

if (!response.ok) {
dealerList.innerHTML = '<div class="dealer-empty">Unable to load dealers. Please try again.</div>';
mapEmptyText.style.display = 'block';
mapEmptyText.textContent = 'Unable to load dealers for current selection.';
return;
}

const result = await response.json();
const dealers = result.data || [];
const appliedFilters = result.applied_filters || {};

if (appliedFilters.lat && appliedFilters.lng) {
const parsedLat = Number(appliedFilters.lat);
const parsedLng = Number(appliedFilters.lng);

if (Number.isFinite(parsedLat) && Number.isFinite(parsedLng)) {
currentPosition.lat = parsedLat;
currentPosition.lng = parsedLng;
}
}

renderDealerCards(dealers);
renderPagination(result.pagination);
renderMap(dealers);

const stateParams = new URLSearchParams(window.location.search);
stateParams.set('page', String(page));
['province', 'district', 'local_level', 'q', 'sort', 'radius'].forEach((key) => {
const source = {
province: provinceSelect,
district: districtSelect,
local_level: localLevelSelect,
sort: dealerSort,
}[key];
if (key === 'radius') {
if (radiusInput.value) {
stateParams.set(key, radiusInput.value);
} else {
stateParams.delete(key);
}
return;
}
if (key === 'q') {
if (dealerSearch.value) {
stateParams.set(key, dealerSearch.value);
} else {
stateParams.delete(key);
}
return;
}
if (source && source.value) {
stateParams.set(key, source.value);
} else {
stateParams.delete(key);
}
});
history.replaceState(null, '', `${window.location.pathname}?${stateParams.toString()}`);
}

function setMyLocation() {
if (!navigator.geolocation) {
alert('Geolocation is not supported in this browser.');
return;
}

detectLocationBtn.disabled = true;
const originalLabel = detectLocationBtn.textContent;
detectLocationBtn.textContent = 'Locating...';

navigator.geolocation.getCurrentPosition(
(position) => {
const latitude = Number(position.coords.latitude);
const longitude = Number(position.coords.longitude);
if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
    alert('Unable to read a valid location from your browser.');
    detectLocationBtn.disabled = false;
    detectLocationBtn.textContent = originalLabel;
    return;
}

currentPosition.lat = latitude;
currentPosition.lng = longitude;
dealerSort.value = 'nearest';
fetchDealers(1);
detectLocationBtn.disabled = false;
detectLocationBtn.textContent = originalLabel;
},
    (error) => {
        const message = error?.code === error.PERMISSION_DENIED
            ? 'Location access was denied. Please enable location permission and try again.'
            : 'Unable to fetch your location. Please allow location access and retry.';
        alert(message);
        detectLocationBtn.disabled = false;
        detectLocationBtn.textContent = originalLabel;
    },
{ enableHighAccuracy: true, timeout: 10000 }
);
}

function clearFilters() {
    provinceSelect.value = '';
    districtSelect.value = '';
    localLevelSelect.value = '';
    dealerSearch.value = '';
    radiusInput.value = '';
    currentPosition = { lat: null, lng: null };

    clearFilterDefaults();
    provinceChanged();
    fetchDealers(1);
}

provinceSelect.addEventListener('change', () => {
provinceChanged();
fetchDealers(1);
});
districtSelect.addEventListener('change', () => {
districtChanged();
fetchDealers(1);
});
localLevelSelect.addEventListener('change', () => {
localLevelChanged();
fetchDealers(1);
});
dealerSort.addEventListener('change', () => fetchDealers(1));
radiusInput.addEventListener('change', () => fetchDealers(1));
detectLocationBtn.addEventListener('click', setMyLocation);
clearFiltersBtn.addEventListener('click', clearFilters);
document.getElementById('call-dialog-close')?.addEventListener('click', () => closeCallOptions());
document.querySelectorAll('[data-close-call-dialog]')?.forEach((closeTrigger) => {
closeTrigger.addEventListener('click', () => closeCallOptions());
});

document.addEventListener('keydown', (event) => {
if (event.key === 'Escape') {
closeCallOptions();
}
});

dealerSearch.addEventListener('keyup', () => {
if (dealerSearch.dataset.timer) {
clearTimeout(Number(dealerSearch.dataset.timer));
}
dealerSearch.dataset.timer = setTimeout(() => fetchDealers(1), 400);
});

initSelectState();
provinceChanged();
fetchDealers(1);
</script>
@endpush
@endsection

