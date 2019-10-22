#!/bin/bash
while true; do

if [ -f /home/sales/km/data/cennik_sr.fods ]; then

libreoffice --minimized -pt  "FS-1120MFP" "/home/sales/km/data/cennik_sr.fods"
rm -f "/home/sales/km/data/cennik_sr.fods"

fi

if [ -f /home/sales/km/data/hkod.fods ]; then

libreoffice --minimized -pt  "TSC_TDP-225" "/home/sales/km/data/hkod.fods"
rm -f "/home/sales/km/data/hkod.fods"

fi

sleep 2
done
