/**
 * Created by Geo on 24.02.2017.
 */

/**
 * CONSTANTS / VARIABLES
 */
const doorId = 1212;
const signalingSrvAddr = "192.168.0.18";
const signalingSrvPort = "7007";


const CALL_REQUEST = "CALL_REQUEST";

var status = "DISCONNECTED";
var RTCConnection;
var stream;
var socketConn;

/**
 * FUNCTIONS
 */
// Start connection to the Signaling server
function startConnection()
{
    setTimeout(function() { checkIfFailed(); }, 6000)
    var remoteAudio = document.querySelector('#remoteAudio'); // UI Selector
    var remoteVideo = document.querySelector('#remoteVideo'); // UI Selector

    socketConn = new WebSocket("wss://"+signalingSrvAddr+":"+signalingSrvPort);
    console.log("Connecting to the signaling server...");
    status = "CONNECTING";

    // CALLBACKS
    // The connection has been established
    socketConn.onopen = function ()
    {
        console.log("Connected to the signaling server");
        status = "CONNECTED";
        requestCall();
    };

    // Message received from the server
    socketConn.onmessage = function (msg)
    {
        console.log("Message received: ", msg.data);
        var data = JSON.parse(msg.data);

        switch(data.type) {
            case CALL_REQUEST:
                setupRTC(data.value);
                break;
            case "offer":
                handleOffer(data.offer, data.name);
                break;
            case "answer":
                handleAnswer(data.answer);
                break;
            case "candidate":
                handleCandidate(data.candidate);
                break;
            case "leave":
                handleLeave();
                break;
            default:
                console.log(data);
                break;
        }
    };

    socketConn.onerror = function (err) {
        console.log("Got error ", err);
    };

}

function checkIfFailed()
{
    if(socketConn.readyState == 1) // If connected do nothing
    {
        return true;
    }
    else // ...retry
    {
        console.log("Connection attempt failed, retry...");
        socketConn.close();
        startConnection();
    }
}


// Sends the message via the socket in JSON format
function send(message)
{
    socketConn.send(JSON.stringify(message));
}

// Sends a request for a new call to the signaling server
function requestCall()
{
    send({
        type: CALL_REQUEST,
        doorId: doorId
    });
}

// Setup WebRTC for the call and starting a peer connection
function setupRTC(value)
{
    if(value == "false")
    {
        console.log("Error: cannot reach the requested door!");
    }
    else
    {
        console.log("Setting up WebRTC..." )

        // Getting local audio stream
        navigator.webkitGetUserMedia({video: false, audio: true}, function (myStream) // Request for audio and video
        {
            stream = myStream;

            // using Google public stun server (Remove for LAN connections)
            var configuration = {
                "iceServers": [{"url": "stun:stun2.1.google.com:19302"}]
            };

            // Init WebRTC
            RTCConnection = new webkitRTCPeerConnection(); // Add configuration for STUN Support
            // Setup listening stream
            RTCConnection.addStream(stream);
            // When remote user add a stream
            RTCConnection.onaddstream = function (e)
            {
                //remoteAudio.src = window.URL.createObjectURL(e.stream);
                remoteVideo.src = window.URL.createObjectURL(e.stream);
                $('#audioWave').show();
                $('#audioOff').hide();
            };
            // Setup ice handling
            RTCConnection.onicecandidate = function (event)
            {
                if (event.candidate)
                {
                    send({
                        type: "candidate",
                        candidate: event.candidate
                    });
                }
            };

        }, function (error)
        {
            console.log("Error setting up WebRTC");
            console.log(error);
        });

        status = "READY";
        $('#btnLeft').fadeTo('fast', 1); // Activate button
        console.log("Done! Now you can start the call");
    }
};

// Start the call by sending an offer to the signaling
function startCall()
{
    // create an offer
    RTCConnection.createOffer(function (offer) {
        send({
            type: "offer",
            offer: offer
        });

        RTCConnection.setLocalDescription(offer);
    }, function (error) {
        alert("Error when creating an offer");
    },
        { 'mandatory': { 'OfferToReceiveAudio': true, 'OfferToReceiveVideo': true }
    });
}


// When we got an answer after the offer
function handleAnswer(answer)
{
    RTCConnection.setRemoteDescription(new RTCSessionDescription(answer));
    status = "TRANSMITTING";
};

// When we got an ice candidate from a remote user
function handleCandidate(candidate)
{
    RTCConnection.addIceCandidate(new RTCIceCandidate(candidate));
};

// Close
function handleLeave()
{
    console.log("Call terminated!");
    remoteAudio.src = null;
    RTCConnection.close();
    RTCConnection.onicecandidate = null;
    RTCConnection.onaddstream = null;
    RTCConnection = null;
    setupRTC("true");
};


function closeCall()
{
    send({
        type: "leave"
    });

    handleLeave();
}

function triggerCall()
{
    if(status == "TRANSMITTING")
    {
        closeCall();
        changeBtn("off");
    }
    else if(status == "READY")
    {
        startCall();
        changeBtn("on");
    }
    else
    {
        console.log("Not ready yet!");
    }
}

function changeBtn(status)
{
    if(status == "on")
    {
        $('#btnLeft').css("background-image", "url('../img/mainbtn.png')");
        $('#iconLeft').css("background-image", "url('../img/speaker.png')");
    }
    else
    {
        $('#btnLeft').css("background-image", "url('../img/mainbtn_no.png')");
        $('#iconLeft').css("background-image", "url('../img/mute.png')");
        $('#audioWave').hide();
        $('#audioOff').show();
    }
}

function openDoor()
{
    $('#btnRight').css("background-image", "url('../img/mainbtn.png')");
    setTimeout(function() { $('#btnRight').css("background-image", "url('../img/mainbtn_no.png')") }, 3000)
}