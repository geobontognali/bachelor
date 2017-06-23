/**
 * Created by Geo on 21.06.2017.
 */
self.addEventListener('notificationclick', function(event) {
    console.log('On notification click: ', event.notification.tag);
    event.notification.close();

    // This looks to see if the current is already open and
    // focuses if it is
    event.waitUntil(clients.matchAll({
        type: "window"
    }).then(function(clientList) {
        for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url == 'https://bachelor.dev/client' && 'focus' in client)
                return client.focus();
        }
        if (clients.openWindow)
            return clients.openWindow('https://bachelor.dev/client');
    }));
});