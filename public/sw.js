const CACHE_NAME = 'mamina-cache-v1';
const urlsToCache = [
    '/',
    '/manifest.json',
    '/icon-192x192.png',
    '/icon-512x512.png'
];

// ad build asset to cache
const buildAssets = [
    '/build/assets/',
    '/build/manifest.json',
    '/build/manifest.webmanifest'
];

// Function to create a basic Inertia response that matches the requested page structure
function createInertiaResponse(url) {
    // Extract the component name from the URL
    let component = 'Home';
    let props = {};

    // Get auth data from the current page
    const auth = {
        user: {
            id: 1,
            nama: 'User',
            jenis_kelamin: 'L',
            telp: '',
            alamat: '',
            id_provinsi: null,
            id_kota: null,
            id_kecamatan: null,
            id_kelurahan: null,
            id_cabang: null
        }
    };

    if (url.includes('nutrition')) {
        component = 'Nutrition/Index';
        props = {
            foods: [],
            categories: [],
            children: [],
            filters: {
                search: '',
                category: ''
            },
            auth
        };
    } else if (url.includes('growth')) {
        component = 'Growth';
        props = {
            children: [],
            auth
        };
    } else if (url.includes('profile')) {
        component = 'Profile/Index';
        props = {
            user: auth.user,
            auth
        };
    } else if (url.includes('news')) {
        component = 'News/Index';
        props = {
            news: [],
            auth
        };
    } else if (url.includes('records')) {
        component = 'Record/Index';
        props = {
            child: null,
            records: [],
            filters: {
                search: ''
            },
            auth
        };
    } else {
        // Home page
        props = {
            children: [],
            news: [],
            auth
        };
    }

    const response = {
        component: component,
        props: props,
        url: url,
        version: null
    };

    return new Response(JSON.stringify(response), {
        headers: {
            'Content-Type': 'application/json',
            'X-Inertia': 'true'
        }
    });
}

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return Promise.all([
                    ...urlsToCache.map(url =>
                        cache.add(url).catch(err => {
                            console.warn(`Failed to cache ${url}:`, err);
                        })
                    ),
                    ...buildAssets.map(url =>
                        cache.add(url).catch(err => {
                            console.warn(`Failed to cache ${url}:`, err);
                        })
                    )
                ]);
            })
    );
});

self.addEventListener('fetch', (event) => {
    // Skip cross-origin requests, kayak api
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Handle Inertia requests
    if (event.request.headers.get('X-Inertia')) {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    // Cache successful responses
                    if (response.ok) {
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => cache.put(event.request, responseToCache))
                            .catch(err => console.warn('Failed to cache response:', err));
                    }
                    return response;
                })
                .catch(() => {
                    // For Inertia requests, return a response that matches the page structure
                    return createInertiaResponse(event.request.url);
                })
        );
        return;
    }

    // Handle regular requests
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                if (response) {
                    return response;
                }

                return fetch(event.request)
                    .then(response => {
                        if (!response || response.status !== 200) {
                            return response;
                        }

                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => cache.put(event.request, responseToCache))
                            .catch(err => console.warn('Failed to cache response:', err));

                        return response;
                    })
                    .catch(() => {
                        // For non-Inertia requests, try to serve from cache
                        return caches.match('/');
                    });
            })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
