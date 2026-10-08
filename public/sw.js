// ============================================================
// SAHAJA AI — Service Worker
// ============================================================

const CACHE_VERSION = 'sahaja-ai-v1';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;
const OFFLINE_URL = '/offline';

// Assets yang di-pre-cache (static)
const PRECACHE_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    'https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
];

// Install event — pre-cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.log('Precache partial fail:', err);
            });
        })
    );
    self.skipWaiting();
});

// Activate event — cleanup cache lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== STATIC_CACHE && key !== DYNAMIC_CACHE) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event — network-first untuk HTML/API, cache-first untuk static
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Skip non-GET
    if (request.method !== 'GET') return;

    // Skip chrome-extension, dsb
    if (!request.url.startsWith('http')) return;

    const url = new URL(request.url);

    // Skip POST /api endpoints (jangan cache)
    if (url.pathname.startsWith('/send') || 
        url.pathname.startsWith('/deep-research') || 
        url.pathname.startsWith('/profile') || 
        url.pathname.startsWith('/session')) {
        return;
    }

    // HTML pages — network-first (fallback ke offline page)
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const responseClone = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then((cached) => {
                        return cached || caches.match(OFFLINE_URL);
                    });
                })
        );
        return;
    }

    // Static assets — cache-first
    event.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;

            return fetch(request).then((response) => {
                // Cache CSS, JS, fonts, images
                if (response.ok && (
                    url.pathname.match(/\.(css|js|woff2?|png|jpg|jpeg|svg|ico|webp)$/) ||
                    url.hostname.includes('cdnjs') ||
                    url.hostname.includes('fonts.googleapis') ||
                    url.hostname.includes('fonts.gstatic') ||
                    url.hostname.includes('i.ibb.co.com')
                )) {
                    const responseClone = response.clone();
                    caches.open(STATIC_CACHE).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }
                return response;
            }).catch(() => {
                // Kalau offline, coba cache
                return caches.match(request);
            });
        })
    );
});

// Message handler — untuk trigger skipWaiting dari client
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
