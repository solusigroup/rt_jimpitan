const CACHE_NAME = 'jimpitan-pwa-v1';
const ASSETS_TO_CACHE = [
  'index.php',
  'jadwal.php',
  'laporan.php',
  'login.php',
  'menu.php',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
];

// Install Event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('[Service Worker] Caching static assets');
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate Event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) {
            console.log('[Service Worker] Removing old cache', key);
            return caches.delete(key);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch Event (Network First Strategy for dynamic content, Cache First for static files)
self.addEventListener('fetch', event => {
  const requestUrl = new URL(event.request.url);

  // Cek jika request adalah halaman dinamis (PHP) atau aksi form/POST
  if (requestUrl.pathname.endsWith('.php') || event.request.method === 'POST') {
    event.respondWith(
      fetch(event.request).catch(() => {
        // Fallback jika offline, cari di cache jika tersedia
        return caches.match(event.request);
      })
    );
  } else {
    // Untuk static assets, gunakan Cache First dengan fallback ke Network
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
            });
          }
          return networkResponse;
        });
      })
    );
  }
});
