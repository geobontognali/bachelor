/**
 * Created by Geo on 24.02.2017.
 */

/** VARIABLES **************************************************/
/** ************************************************************/
/** Constants **/
const signalingSrvAddr = "192.168.0.18";
const signalingSrvPort = "7007";
const socketProtocol = "wss"; // use wss only

/** Signaling Types **/
const DOOR_ONLINE = "DOOR_ONLINE";
const DOOR_REQUEST = "DOOR_REQUEST";
const OFFER = "OFFER";
const ANSWER = "ANSWER";
const CANDIDATE = "CANDIDATE";
const LEAVE = "LEAVE";

/** Variables **/
var status = "offline";
var RTCConnection;
var stream;
var socketConn;


/**
 * UI SELECTORS
 */
var localAudio = document.querySelector('#localAudio');
var localVideo = document.querySelector('#localVideo'); // Currently not used
var remoteVideo = document.querySelector('#remoteVideo');
var remoteAudio = document.querySelector('#remoteAudio');


/**
 * FUNCTIONS
 */
// Init point, fires up the connection
function startWebSocket()
{
    console.log("My ID " + doorId);
    console.log("Connecting to the signaling server...");
    socketConn = new WebSocket(socketProtocol +"://"+signalingSrvAddr+":"+signalingSrvPort);

    // The connection has been established
    socketConn.onopen = function ()
    {
        console.log("Connected to the signaling server");
        goOnline();
    };

    // Message received from the server
    socketConn.onmessage = function (msg)
    {
        console.log("Message received: ", msg.data);
        var data = JSON.parse(msg.data);

        switch(data.type) {
            case DOOR_ONLINE:
                setupRTC(data.value);
                break;
            // When somebody wants to call us
            case OFFER:
                handleOffer(data.offer, data.name);
                break;
            // When a remote peer sends an ice candidate to us
            case CANDIDATE:
                handleCandidate(data.candidate);
                break;
            // When the call is terminated
            case LEAVE:
                handleLeave();
                break;
            default:
                console.log(data);
                break;
        }
    };

    socketConn.onerror = function (err)
    {
        console.log("Got error", err);
        socketConn.close();
    };

    socketConn.onclose = function(){
        console.log('Connection closed!');
        console.log("Reconnecting in 3 secs...");
        socketConn = null;
        setTimeout(function(){ startWebSocket(); }, 3000);
    };
}

// Setup Webrtc to pick up the call and starting a peer connection
function setupRTC(value) {
    if (value == "false")
    {
        console.log("Server didn't acknowledged that the door is online");
    }
    else
    {
        console.log("The server acknowledged that we are online! Setting up WebRTC");
        status = "configuring";

        //getting local audio myLocalStream
        navigator.webkitGetUserMedia({ video: true, audio: true }, function (myStream) {
            stream = myStream;

            //displaying local audio myLocalStream on the page
            //localAudio.src = window.URL.createObjectURL(myLocalStream);
            //localVideo.src = window.URL.createObjectURL(myLocalStream);

            //using Google public stun server
            var configuration = {
                "iceServers": [{ "url": "stun:stun2.1.google.com:19302" }]
            };

            // DONT GIVE ANY CONFIGURATION FOR LOCAL TRAFFIC
            RTCConnection = new webkitRTCPeerConnection(configuration); // Add configuration for STUN Support

            // setup myLocalStream listening
            RTCConnection.addStream(stream);

            //when a remote user adds myLocalStream to the peer connection, we display it
            RTCConnection.onaddstream = function (e) {
                remoteAudio.src = window.URL.createObjectURL(e.stream);
            };

            // Setup ice handling
            RTCConnection.onicecandidate = function (event) {
                if (event.candidate) {
                    send({
                        type: CANDIDATE,
                        candidate: event.candidate
                    });
                }
            };

        }, function (error) {
            console.log(error);
        });

        status = "ready";
        console.log("Status: " + status);
    }
};

// Sends the message via the socket in JSON format
function send(message)
{
    socketConn.send(JSON.stringify(message));
}

// Tells the signaling server that im online
function goOnline()
{
    send({
        type: DOOR_ONLINE,
        doorId: doorId
    });
}

// Adds the ICE Candidates received from the other peer
function handleCandidate(candidate)
{
    RTCConnection.addIceCandidate(new RTCIceCandidate(candidate));
};


// Replies with an answer for the offer received from the other peer
function handleOffer(offer, name)
{
    RTCConnection.setRemoteDescription(new RTCSessionDescription(offer));

    //create an answer to an offer
    RTCConnection.createAnswer(function (answer)
    {
        RTCConnection.setLocalDescription(answer);

        send({
            type: ANSWER,
            answer: answer
        });

        status = "calling";

    }, function (error)
    {
        console.log("Error when giving an answer to the offer");
    },
        { 'mandatory': { 'OfferToReceiveAudio': true, 'OfferToReceiveVideo': false } // Request for audio only
    });
};

// Terminate audiostream
function handleLeave()
{
    console.log("Call terminated!")
    remoteAudio.src = null;
    RTCConnection.close();
    RTCConnection.onicecandidate = null;
    RTCConnection.onaddstream = null;
    RTCConnection = null;
    console.log("Setting Up Again")
    setupRTC("true");

};

// INIT
startWebSocket();


// GUI & Stuff //////////////////////////////////////////
// Set a KeyPress listener
document.addEventListener("keypress", slideCarousel);
// Fired by the keypress listener
var ringing = false;
function slideCarousel(evt)
{
    // Get the pressed key as a char
    evt = evt || window.event;
    var charCode = evt.keyCode || evt.which;
    var charStr = String.fromCharCode(charCode);
    console.log(charStr);
    if(charStr == "l" || charStr == "L")
    {
        carousel.cycleActive('previous');
    }
    else if(charStr == "j" || charStr == "J")
    {
        carousel.cycleActive('next');
    }
    else if(charStr == "k" ||charStr == "K")
    {
        callTheResident();
    }
}


function callTheResident()
{
    if(ringing) { return false; } // Cancel if already ringing
    ringing = true;
    residentId = $('#scroller .carousel .item.active .residentId').text(); // get the user id of the current selected user

    // Send request to the relay server
    received = "";
    console.log("Sending via AJAX");
    $.getJSON("ajaxListener/playGong/" + residentId + "/" + doorId, function(data)
    {
        received = data; // Returned data

    }).done(function()
    {
        // Print the data
        if(received == true)
        {
            console.log("Done!");
        }
        else
        {
            console.log("Error trying to play the gong....");
        }
    })

    // GUI Animation & stuff
    $('.btnIconMiddle img').attr("src", "img/alarm.png");
    setTimeout(function() { $('.btnIconMiddle img').attr("src", "img/alarm_noring.png"); }, 1000);
    setTimeout(function() { $('.btnIconMiddle img').attr("src", "img/alarm.png"); }, 1500);
    setTimeout(function() { $('.btnIconMiddle img').attr("src", "img/alarm_noring.png"); }, 2500);
    setTimeout(function() { $('.btnIconMiddle img').attr("src", "img/alarm.png"); }, 3000);
    setTimeout(function() { $('.btnIconMiddle img').attr("src", "img/alarm_noring.png"); ringing = false; }, 4000);
}



