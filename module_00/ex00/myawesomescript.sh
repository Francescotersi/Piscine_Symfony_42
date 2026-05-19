#!/bin/bash

#use $1, $2, $3, ... to access arguements passed by terminal
if [ -z "$1" ]; then
	echo "Not Enough Arguments"
elif [ -n "$2" ]; then
	echo "Too Many Arguments"
else
	curl --head -s $1 | grep -i Location | cut -d' ' -f2
fi
