/**
 * Created by Geo on 24.02.2017.
 */

/**
 * CONSTANTS / VARIABLES
 */
const doorId = 1212; //Math.random(); // Generate random name
const signalingSrvAddr = "localhost";
const signalingSrvPort = "7007";
const PICK_UP = "PICK_UP";
var status = "waiting";

var RTCConnection;
var stream;


console.log("I am " + name);

/**
 * UI SELECTORS
 */
var localAudio = document.querySelector('#localAudio');
var remoteAudio = document.querySelector('#remoteAudio');
var hangUpBtn = document.querySelector('#hangUpBtn');


/**
 * INIT
 */
// Start connection to the Signaling server
var socketConn = new WebSocket("wss://"+signalingSrvAddr+":"+signalingSrvPort);
console.log("Connecting to the signaling server...");


/**
 * CALLBACKS
 */
// The connection has been established
socketConn.onopen = function ()
{
    console.log("Connected to the signaling server");

    setInterval(checkPendingCalls, 800);

};

// Message received from the server
socketConn.onmessage = function (msg)
{
    console.log("Message received: ", msg.data);
    var data = JSON.parse(msg.data);

    switch(data.type) {
        case "PICK_UP":
            pickUpCall(data.value);
            break;
        //when somebody wants to call us
        case "offer":
            handleOffer(data.offer, data.name);
            break;
        //when a remote peer sends an ice candidate to us
        case "candidate":
            handleCandidate(data.candidate);
            break;
        case "leave":
            handleLeave();
            status = "waiting";
            break;
        default:
            console.log(data);
            break;
    }
};

socketConn.onerror = function (err) {
    console.log("Got error", err);
};


/**
 * FUNCTIONS
 */
// Sends the message via the socket in JSON format
function send(message)
{
    socketConn.send(JSON.stringify(message));
}

// Check for pending incoming calls
function checkPendingCalls()
{
    if(status == "waiting")
    {
        send({
            type: PICK_UP,
            doorId: doorId
        });
    }
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
            type: "answer",
            answer: answer
        });

    }, function (error)
    {
        console.log("Error when giving an answer to the offer");
    });
};

function handleLeave()
{
    connectedUser = null;
    remoteAudio.src = null;

    RTCConnection.close();
    RTCConnection.onicecandidate = null;
    RTCConnection.onaddstream = null;
};

// Set up Webrtc to pick up the call and starting a peer connection
function pickUpCall(success) {
    if (success == "false")
    {
        console.log("No incoming call yet or already calling");
    }
    else
    {
        status = "calling";

        //getting local audio stream
        navigator.webkitGetUserMedia({ video: false, audio: true }, function (myStream) {
            stream = myStream;

            //displaying local audio stream on the page
            localAudio.src = window.URL.createObjectURL(stream);

            //using Google public stun server
            var configuration = {
                //"iceServers": [{ "url": "stun:stun2.1.google.com:19302" }]
            };

            RTCConnection = new webkitRTCPeerConnection(configuration);

            // setup stream listening
            RTCConnection.addStream(stream);

            //when a remote user adds stream to the peer connection, we display it
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


/**
 * LISTENERS
 */
// Hang up the call
hangUpBtn.addEventListener("click", function () {
    send({
        type: "leave"
    });

    handleLeave();
});









