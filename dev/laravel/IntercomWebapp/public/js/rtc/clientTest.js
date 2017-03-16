/**
 * Created by Geo on 24.02.2017.
 */

/**
 * CONSTANTS / VARIABLES
 */
const doorId = 1212; //Math.random(); // Generate random name
const signalingSrvAddr = "192.168.0.18";
const signalingSrvPort = "7007";
const CALL_REQUEST = "CALL_REQUEST";

var RTCConnection;
var stream;
var socketConn;

/**
 * UI SELECTORS
 */
var callBtn = document.querySelector('#callBtn');
var hangUpBtn = document.querySelector('#hangUpBtn');
var localAudio = document.querySelector('#localAudio');
var remoteAudio = document.querySelector('#remoteAudio');



/**
 * FUNCTIONS
 */

// Start connection to the Signaling server
function startConnection()
{
    socketConn = new WebSocket("wss://"+signalingSrvAddr+":"+signalingSrvPort);
    console.log("Connecting to the signaling server...");

    /**
     * CALLBACKS
     */
// The connection has been established
    socketConn.onopen = function ()
    {
        console.log("Connected to the signaling server");
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
                console.log("Done! Now you can start the call");
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
        console.log("Something went wrong here....");
    }
    else
    {
        console.log("Setting up WebRTC..." )

        // Getting local audio myLocalStream
        navigator.webkitGetUserMedia({video: false, audio: true}, function (myStream)
        {
            stream = myStream;

            //displaying local audio myLocalStream on the page
            //localAudio.src = window.URL.createObjectURL(myLocalStream);

            // using Google public stun server (toglierlo per il traffico locale)
            var configuration = {
                "iceServers": [{"url": "stun:stun2.1.google.com:19302"}]
            };

            // DONT GIVE ANY CONFIGURATION FOR LOCAL TRAFFIC
            RTCConnection = new webkitRTCPeerConnection(configuration);

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
                        type: "candidate",
                        candidate: event.candidate
                    });
                }
            };

        }, function (error) {
            console.log(error);
        });
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
    });
}


// When we got an answer after the offer
function handleAnswer(answer) {
    RTCConnection.setRemoteDescription(new RTCSessionDescription(answer));
};

// When we got an ice candidate from a remote user
function handleCandidate(candidate) {
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


/**
 * LISTENERS
 */
//Hang up
hangUpBtn.addEventListener("click", function () {
    send({
        type: "leave"
    });

    handleLeave();
});

//initiating a call
callBtn.addEventListener("click", function () {
    alert("Hey");
    startCall();
});

setTimeout(function() { console.log("JIMMYY!")} , 10000);