const CACHE = 'app-v1';
const STATIC = [
    '/id/',
    '/manifest.webmanifest',
    '/offline.html',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE).then((cache) => cache.addAll(STATIC))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Only handle same-origin GET requests
    if (request.method !== 'GET' || url.origin !== self.location.origin) {
        return;
    }

    // Cache-first for static build assets and icons
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/icons/') || url.pathname.startsWith('/fonts/')) {
        event.respondWith(
            caches.match(request).then((cached) =>
                cached ?? fetch(request).then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE).then((cache) => cache.put(request, clone));
                    return response;
                })
            )
        );
        return;
    }

    // Network-first for HTML pages — falls back to offline page if no network and no cache
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE).then((cache) => cache.put(request, clone));
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) =>
                        cached ?? caches.match('/offline.html')
                    )
                )
        );
    }
});
