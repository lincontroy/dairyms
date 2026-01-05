const CACHE_NAME = "laravel-pwa-v1";
const OFFLINE_URL = "/offline";

const ASSETS = [
    "/",
    "/offline",
    "/css/app.css",
    "/js/app.js"
];

self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

self.addEventListener("fetch", event => {
    event.respondWith(
        fetch(event.request).catch(() =>
            caches.match(event.request).then(response => {
                return response || caches.match(OFFLINE_URL);
            })
        )
    );
});

self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            )
        )
    );
});
