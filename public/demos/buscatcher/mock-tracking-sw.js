// Mock for the BusCatcher live-tracking demo. The real app polls
// /*/tracking/locations every 2s over plain fetch (see setInterval(loadBusLocations, 2000)
// in tracking/index.blade.php) — no WebSockets/Pusher involved, so a service
// worker intercepting that one endpoint is enough to make the map genuinely
// move, using the real route polylines from window.BUSCATCHER_DEMO_TRACKING
// (see tracking-demo-data.js) the same way the repo's own simulate_buses.py
// walks a bus along its route: advance a fraction of the polyline each tick.

importScripts('tracking-demo-data.js');

const DATA = self.BUSCATCHER_DEMO_TRACKING;

function haversine(a, b) {
    const R = 6371000;
    const toRad = (d) => (d * Math.PI) / 180;
    const dLat = toRad(b[1] - a[1]);
    const dLng = toRad(b[0] - a[0]);
    const lat1 = toRad(a[1]);
    const lat2 = toRad(b[1]);
    const h = Math.sin(dLat / 2) ** 2 + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLng / 2) ** 2;
    return 2 * R * Math.asin(Math.sqrt(h));
}

// cumulative distance at each polyline vertex, so "40% along the route" is a
// real distance fraction, not just "40% of the vertices" (which would race
// through dense clusters of points and crawl through sparse ones)
function buildCumulative(polyline) {
    const cum = [0];
    for (let i = 1; i < polyline.length; i++) {
        cum.push(cum[i - 1] + haversine(polyline[i - 1], polyline[i]));
    }
    return cum;
}

const cumCache = new Map();

function positionAt(bus, t) {
    // t: 0..1 forward, "ping-pongs" back down 1..0 so the bus does a real
    // round trip instead of teleporting back to the start
    const cycle = t % 2;
    const frac = cycle <= 1 ? cycle : 2 - cycle;

    if (!cumCache.has(bus.id)) cumCache.set(bus.id, buildCumulative(bus.polyline));
    const cum = cumCache.get(bus.id);
    const total = cum[cum.length - 1];
    const target = frac * total;

    let i = 1;
    while (i < cum.length && cum[i] < target) i++;
    if (i >= cum.length) i = cum.length - 1;

    const segStart = cum[i - 1];
    const segEnd = cum[i];
    const segFrac = segEnd > segStart ? (target - segStart) / (segEnd - segStart) : 0;

    const [lngA, latA] = bus.polyline[i - 1];
    const [lngB, latB] = bus.polyline[i];
    const lat = latA + (latB - latA) * segFrac;
    const lng = lngA + (lngB - lngA) * segFrac;

    // rough speed estimate from how far we moved this "tick window" (2s of
    // real polling maps to a proportional slice of loop_seconds)
    const speedKmh = frac === 0 || frac === 1 ? 0 : 28 + (bus.id % 7) * 2;

    return { lat, lng, speedKmh };
}

function buildResponse() {
    const now = Date.now() / 1000;

    const active = DATA.active.map((bus) => {
        const t = (now % (bus.loop_seconds * 2)) / bus.loop_seconds;
        const { lat, lng, speedKmh } = positionAt(bus, t);
        const nextStop = bus.route_stops[0] || null;

        return {
            id: bus.id,
            bus_number: bus.bus_number,
            latitude: lat,
            longitude: lng,
            status: 'active',
            speed: speedKmh,
            last_update: Date.now(),
            has_location_data: true,
            driver: bus.driver,
            route: bus.route,
            route_stops: bus.route_stops,
            active_trip: {
                id: bus.id,
                status: 'active',
                trip_type: 'pickup',
                route_name: bus.route.name,
                encoded_polyline: JSON.stringify(bus.polyline),
            },
            next_stop: nextStop ? { id: nextStop.id, name: nextStop.name, eta_seconds: 180 } : null,
            final_stop: null,
        };
    });

    const offline = DATA.offline.map((bus) => ({
        id: bus.id,
        bus_number: bus.bus_number,
        latitude: null,
        longitude: null,
        status: 'inactive',
        speed: null,
        last_update: null,
        has_location_data: false,
        driver: null,
        route: null,
        route_stops: [],
        active_trip: null,
        next_stop: null,
        final_stop: null,
    }));

    return { success: true, buses: [...active, ...offline] };
}

self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    if (!url.pathname.endsWith('/tracking/locations')) return;

    event.respondWith(
        new Response(JSON.stringify(buildResponse()), {
            status: 200,
            headers: { 'Content-Type': 'application/json' },
        })
    );
});
