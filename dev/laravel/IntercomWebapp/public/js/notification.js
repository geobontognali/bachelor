/**
 * Created by Geo on 21.06.2017.
 */


/***
 * Add the service worker for the notification system
 */
navigator.serviceWorker.register('sw.js');
Notification.requestPermission(function(result) {
    if (result === 'granted') {
        console.log("Service Worker Registered");
    }
});

/***
 * Check if a notification has to be shown
 */
function checkForNotification()
{
    $.getJSON("ajaxListener/checkNotification/" + userId, function(data)
    {
        received = data; // Returned data

    }).done(function()
    {
        var count = Object.keys(received).length;
        if(count>0)
        {
            console.log("Notifications found. Amount: " + count);

            for(i = 0; i<count; i++)
            {
                showNotification(received[i]);
            }
            setTimeout('clearNotification()', 4000);
            return true;
        }
        setTimeout('checkForNotification()', 2000);
        return true;
    })
}


/***
 * Send confirmation to the backend so that the notification can be deleted
 */
function clearNotification()
{
    $.getJSON("ajaxListener/clearNotification/" + userId, function(data)
    {
        received = data; // Returned data

    }).done(function()
    {
        console.log("Notification cleared from db.")
    })
    setTimeout('checkForNotification()', 2000);
    return true;
}


/***
 * Actually visualise the notification with the HTML5 notification api
 */
function showNotification(notification)
{
    // First transform the date in a readable form
    var dt = new Date(notification.not_time*1000);
    if(dt.getHours().length == 1) { hours = "0" + dt.getHours(); } else { hours = dt.getHours(); }
    if(dt.getMinutes().length == 1) { minutes = "0" + dt.getMinutes(); } else { minutes = dt.getMinutes(); }
    if(dt.getDay().length == 1) { day = "0" + dt.getDay(); } else { day = dt.getDay(); }
    if(dt.getMonth().length == 1) { month = "0" + dt.getMonth(); } else { month = dt.getMonth(); }
    var date = (day + '.' + month + '.' + dt.getFullYear() + hours + ':' + minutes);

    // Set the payload
    var payload = {
        title: 'Jemand steht vor der Tür!',
        body: 'Jemand steht vor der ' + notification.door_desc + '. \n' + 'Zeit: ' + date,
        icon: 'https://bachelor.dev/img/keyic.png',
        url: 'https://192.168.0.18/client'
    };

    // Show notification
    navigator.serviceWorker.ready.then(function(registration) {

        registration.showNotification(payload.title, {
            body: payload.body,
            icon: payload.icon,
            tag: payload.url + payload.body + payload.icon + payload.title,
            actions: [
                {action: 'connect', title: '👍 Go to App' }]
        });

    });
}

// Init
console.log("Started checking for notification...");
setTimeout('checkForNotification()', 2000);