#!/bin/bash

#IP=`nmap -T4 -sP 192.168.16.0/24 | grep "Nmap scan" | cut -d ' ' -f 5`
#MAC=`nmap -T4 -sP 192.168.16.0/24 | grep "MAC Address" | cut -d ' ' -f 3`

for X in `seq 1 10`
do
PING=`ping -c 2 -w 2 192.168.16.$X | grep packet | cut -d ' ' -f 6`
if [ "$PING" = "0%" ]
then
echo "192.168.16.$X => OK"
#OS=`ssh root@192.168.16.$X 'linuxspecs | tr -d " "'`
IP=`nmap -T4 -sP 192.168.16.$X | grep "Nmap scan" | cut -d ' ' -f 5`
MAC=`nmap -T4 -sP 192.168.16.$X | grep "MAC Address" | cut -d ' ' -f 3`
TYPE=`ssh root@192.168.16.$X 'uname'`
echo "-> MAC : $MAC OS : $TYPE"
echo "$IP $MAC $TYPE;" >> scanreseau.start
else 
echo "192.168.16.$X => BAD"
fi
done
rm scanreseau.txt
mv scanreseau.start scanreseau.txt
