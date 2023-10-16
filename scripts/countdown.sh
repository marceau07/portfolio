#!/bin/bash

lastBoot=$(sudo uptime -s | cut -d ' ' -f 2)
nextBoot=$(date -d "$LAST_BOOT 4 hours")
#nextBoot=$(date -d "$lastBootBis 4 hours")
echo "Last: $lastBoot"
echo "Next boot: $nextBoot"

