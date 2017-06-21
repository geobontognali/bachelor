/**
 * Created by Geo on 20.06.2017.
 */
'use strict';


const applicationServerPublicKey = 'BCSL5PpuGjolDfmH8IpNGr_vhauCknFiAHPmI3z7a-JA2QMYBK4XnFygbVLeC8fi2xTY0s__0Wm2D2iGJ6uE1zM';

const pushButton = document.querySelector('.js-push-btn');

let isSubscribed = false;
let swRegistration = null;

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('Service Worker and Push is supported');

    navigator.serviceWorker.register('sw.js')
        .then(function(swReg) {
            console.log('Service Worker is registered', swReg);

            swRegistration = swReg;
            initialisePush();
        })
        .catch(function(error) {
            console.error('Service Worker Error', error);
        });
} else {
    console.warn('Push messaging is not supported');
    pushButton.textContent = 'Push Not Supported';
}

function initialisePush() {
    // Set the initial subscription value
    swRegistration.pushManager.getSubscription()
        .then(function(subscription) {
            isSubscribed = !(subscription === null);

            if (isSubscribed) {
                console.log('User is already subscribed for notifications.');
                subscribeUser();
            } else {
                console.log('User is not yet subscribed for notifications.');
                subscribeUser();
            }
        });
    }

function subscribeUser() {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
        .then(function(subscription) {
            console.log('User is subscribed.');

            updateSubscriptionOnServer(subscription);

            isSubscribed = true;

        })
        .catch(function(err) {
            console.log('Failed to subscribe the user: ', err);

        });
}

function updateSubscriptionOnServer(subscription) {
    // TODO: Send subscription to application server

    const subscriptionJson = document.querySelector('#txtOut');
    const subscriptionDetails =
        document.querySelector('#txtOut2');

    if (subscription) {
        subscriptionJson.textContent = JSON.stringify(subscription);
        subscriptionDetails.classList.remove('is-invisible');
    } else {
        subscriptionDetails.classList.add('is-invisible');
    }
}