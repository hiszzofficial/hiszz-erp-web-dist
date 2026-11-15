// The admin site does not use a service worker. If an older shop worker is
// still registered on erp.hiszz.com, drop it so the browser stops opening the store.
self.addEventListener('install', function (event) {
  event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys.map(function (key) { return caches.delete(key); }));
    }).then(function () {
      return self.registration.unregister();
    }).then(function () {
      return self.clients.matchAll({ type: 'window' });
    }).then(function (clients) {
      clients.forEach(function (client) {
        if (client.url.indexOf('erp.hiszz.com') !== -1 && 'navigate' in client) {
          client.navigate(client.url);
        }
      });
    })
  );
});
