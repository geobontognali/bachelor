/**
 * Created by Geo on 21.06.2017.
 */
navigator.serviceWorker.register('sw.js');
Notification.requestPermission(function(result) {
    if (result === 'granted') {
        console.log("Service Worker Registered");
    }
});



function showNotification(title, body)
{
    var payload = {
        title: title,
        body: body,
        icon: 'https://192.168.0.18/favicon.ico',
        url: 'https://192.168.0.18/client'
    };


    navigator.serviceWorker.ready.then(function(registration) {

        registration.showNotification(payload.title, {
            body: payload.body,
            icon: payload.icon,
            tag: payload.url + payload.body + payload.icon + payload.title,
            actions: [
                {action: 'connect', title: '👍 Open Intercom App' }]
        });

    });
}