/**
 * Created by Geo on 24.02.2017.
 */

/** VARIABLES **************************************************/
/** ************************************************************/
/** Constants **/
const signalingSrvAddr = "127.0.0.1";
const signalingSrvPort = "7007";
const userId = 1;

/** Signaling Types **/
const DOOR_REQUEST = "DOOR_REQUEST";
const OFFER = "OFFER";
const ANSWER = "ANSWER";
const CANDIDATE = "CANDIDATE";
const LEAVE = "LEAVE";

const DOOR_AVAILABLE = 1;
const DOOR_BUSY = 0;
const DOOR_OFFLINE = -1;

/** Variables **/
var micMuted = true;
var micBtnEnabled = false;
var reconnectAttempts = 2;
var RTCConnection;
var myLocalStream;
var socketConn;


/** SIGNALING PROCESS FUNCTIONS ********************************/
/** ************************************************************/
/** Opens a secure socket connection with the signaling server **/
function startConnection()
{
    // Set timer to retry to connect after 6 seconds if failed
    setTimeout(function() { checkIfFailed(); }, 8000)

    // DOM Selectors
    remoteAudio = document.querySelector('#remoteAudio');
    remoteVideo = document.querySelector('#remoteVideo');

    // Opens secure socket connection
    socketConn = new WebSocket("wss://"+signalingSrvAddr+":"+signalingSrvPort);
    console.log("Connecting to the signaling server...");


    // Defines the listeners for the socket connection
    socketConn.onopen = function ()  // When the connection is open
    {
        console.log("Connected to the signaling server");
        requestDoor();
    };

    socketConn.onmessage = function (msg) // When a message is received from the server
    {
        //console.log("Message received: ", msg.data);
        var data = JSON.parse(msg.data);

        // This switch handles the different phases during the signaling process
        switch(data.type) {
            case DOOR_REQUEST:
                setupRTC(data.value);
                break;
            case ANSWER:
                handleAnswer(data.answer);
                break;
            case CANDIDATE:
                handleCandidate(data.candidate);
                break;
            default:
                console.log(data);
                break;
        }
    };

    socketConn.onerror = function (err) // When an error occurs
    {
        console.log("Got error ", err);
    };
}

/** Checks if the connection has failed, tries again **/
function checkIfFailed()
{
    if(socketConn.readyState == 1) // If connected do nothing
    {
        return true;
    }
    else if(socketConn.readyState == 0) // ...retry
    {
        reconnectAttempts -= 1;
        if(reconnectAttempts == 0)
        {
            showFatalError("Unable to connect to the signaling server");
            return false;
        }
        console.log("Connection attempt failed, retry...");
        socketConn.close();
        startConnection();
    }
}

/** Sends the message via the socket in JSON format **/
function send(message)
{
    socketConn.send(JSON.stringify(message));
}

/** WEB RTC STUFF *****************************************************/
/** *******************************************************************/
/** Setup WebRTC for the call, collect the user media, ICE process and prepare a peer connection **/
function setupRTC(value)
{
    if(value == DOOR_OFFLINE) // The door is offline
    {
        showFatalError("The requested door is offline!")
    }
    else if(value == DOOR_BUSY) // The door is busy
    {
        showFatalError("Another user is connected to this door already!")
    }
    else if(value == DOOR_AVAILABLE) // The door is online and avialable
    {
        console.log("Setting up WebRTC..." )

        // Getting the user media (Cam & Mic)
        navigator.webkitGetUserMedia({video: false, audio: true}, function (myStream) // The client shares only audio
        {
            myLocalStream = myStream;

            // Using Google public stun server
            var configuration = {
                "iceServers": [{"url": "stun:stun2.1.google.com:19302"}]
            };

            // Init WebRTC
            RTCConnection = new RTCPeerConnection(configuration);   // For use in & outside the local network
            //RTCConnection = new RTCPeerConnection();              // For use only inside LAN

            RTCConnection.addStream(myLocalStream); // Adds the local media stream, to the peer connection stream

            RTCConnection.onaddstream = function (e)  // When remote user add his stream to the peer stream
            {
                remoteVideo.src = window.URL.createObjectURL(e.stream); // Bind the remote video stream to HTML element
            };

            RTCConnection.onicecandidate = function (event) // Setup ICE handling
            {
                if (event.candidate)
                {
                    send({
                        type: "candidate",
                        candidate: event.candidate
                    });
                }
            };

            // Used to check when the connection has been established
            RTCConnection.oniceconnectionstatechange = function()
            {
                if(RTCConnection.iceConnectionState == 'connected')
                {
                    // Dont send any audio at the beginning (muted)
                    myLocalStream.getAudioTracks()[0].enabled = false;
                    // Set GUI elements
                    GUIMicStatus(false);
                    micBtnEnabled = true;
                    $('#btnLeft').fadeTo('fast', 1);
                    $('#audioOff').hide();
                    $('#audioWave').show();
                }
            };

        }, function (error) // Error handling
        {
            console.log("Error setting up WebRTC");
            console.log(error);
        });

        $('#connectionStatus').html('Loading...');  // Change loading text
        console.log("Done!");
        setTimeout(function(){ startCall() }, 400); // Delay needed for the RTC object to be populated (not so elegant)
    }
};


/** Sends a request for the given door, to ensure the door is available **/
function requestDoor()
{
    send({
        type: DOOR_REQUEST,
        doorId: doorId
    });
}

/** Start the call by sending an offer to the peer **/
function startCall()
{
    // Create an offer
    RTCConnection.createOffer(function (offer) {
        send({
            type: OFFER,
            offer: offer
        });
        RTCConnection.setLocalDescription(offer);
    }, function (error) {
        alert("Error when creating an offer");
    },
        { 'mandatory': { 'OfferToReceiveAudio': true, 'OfferToReceiveVideo': true }
    });
}


/** Includes the information given by the peer with his answer **/
function handleAnswer(answer)
{
    RTCConnection.setRemoteDescription(new RTCSessionDescription(answer));
};

/** Includes the ICE data received from the peer **/
function handleCandidate(candidate)
{
    RTCConnection.addIceCandidate(new RTCIceCandidate(candidate));
};


/** Terminates the call and reconfigures WebRTC **/
function closeConnection()
{
    // Informs the peer that I'm leaving
    send({ type: LEAVE });
    // Close what needs to be closed
    remoteVideo.src = null;
    RTCConnection.close();
    //RTCConnection = null;
    socketConn.close();
    console.log("Connection terminated!");
    // Reconfigures for future calls
    //setupRTC(1);
}

function showFatalError(msg)
{
    console.log(msg);
    $('#connectionErrorLog').html(msg);
    $('#myModal').modal();
}

/** GUI CONTROL & MISC ************************************************/
/** *******************************************************************/
/** Turn the Mic on & Off **/
function triggerMic()
{
    if(micBtnEnabled & micMuted)
    {
        myLocalStream.getAudioTracks()[0].enabled = true;
        GUIMicStatus(true);
        micMuted = false;
    }
    else if(micBtnEnabled)
    {
        myLocalStream.getAudioTracks()[0].enabled = false;
        GUIMicStatus(false);
        micMuted = true;
    }
}

/** Change the Mic Status graphically **/
function GUIMicStatus(active)
{
    if(active)
    {
        $('#btnLeft').css("background-image", "url('../img/mainbtn.png')");
        $('#iconLeft').css("background-image", "url('../img/mic.png')");
        //$('#audioOff').hide();
        //$('#audioWave').show();
    }
    else
    {
        $('#btnLeft').css("background-image", "url('../img/mainbtn_no.png')");
        $('#iconLeft').css("background-image", "url('../img/mutedmic.png')");
        //$('#audioWave').hide();
        //$('#audioOff').show();
    }
}

/** Connects and disconects WEBRTC using the visibility API  **/
function addVisibilityListener()
{
    console.log("Visibility listener added.")
    document.addEventListener('visibilitychange', function ()
    {
        if (document.hidden)
        {
            closeConnection();
        }
        else
        {
            //setTimeout(function() { startConnection() }, 1000);
            location.reload();
        }
    });

}
$( document ).ready(function() { addVisibilityListener(); });


/** Animates the door buttons and send the command to open the door **/
function openDoorAnimation()
{
    $('#btnRight').css("background-image", "url('../img/mainbtn.png')");
    setTimeout(function() { $('#btnRight').css("background-image", "url('../img/mainbtn_no.png')") }, 3000)
}

/** Send a request to open the door via AJAX **/
function openDoor()
{
    openDoorAnimation();
    received = "";
    console.log("Sending via AJAX");
    $.getJSON("ajaxListener/openDoor/" + doorId, function(data)
    {
        received = data; // Returned data

    }).done(function()
    {
        // Print the data
        if(received == true)
        {
            console.log("Door open!");
        }
        else
        {
            console.log("Error");
            showFatalError("There was a problem trying to open the door. Door is not open!");
        }
    })
}

/***
 * Check if a notification has to be shown
 * @returns {boolean}
 */
function checkForNotification()
{
    //
    console.log("Sending via AJAX");
    $.getJSON("ajaxListener/checkNotification/" + userId, function(data)
    {
        received = data; // Returned data

    }).done(function()
    {
        // Print the data
        if(received == true)
        {
            console.log("Received!");
            console.log(received);
        }
        else
        {
            console.log("Error 000");
        }
    })
}
setInterval('checkForNotification', 2000);
