#!/bin/bash

A=`nmap -sT -p 443 google.fr | grep open | cut -d " " -f 2`

if [ "$A" != "open" ]
then
echo "le port est ouvert"
else
echo "le port est fermé"
fi
