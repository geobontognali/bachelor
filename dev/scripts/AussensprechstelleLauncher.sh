#!/bin/bash
# This script executes the needed commands on startup to initialize the Aussensprechstelle
# path: /home/pi/Aussensprechstelle/Startup/AussensprechstelleLauncher.sh
#
# Activates the Camera Driver (Safe mode because of the chrome resolution bug)
sudo modprobe bcm2835-v4l2 gst_v4l2src_is_broken=1
#
# Clears the old TasterController PID of the process (In case of system shutdown)
file="/var/run/TasterController.pid"
if [ -f $file ] ; then
    rm $file
fi
#
# Starts the TasterController
sudo java -jar /home/door/Aussensprechstelle/TasterController/TasterController.jar &
#
# Creates the PID for the taster controller
sudo echo $! > /var/run/TasterController.pid
#
# Starts the watchdog service
sudo service watchdog start
