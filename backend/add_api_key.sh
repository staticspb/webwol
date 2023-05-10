#!/bin/bash
root=storage
if [ "$#" -ne 1 ]; then
	echo "API key parameter is missing"
	exit 2
fi
mkdir $root/$(echo -n $1 | md5sum | awk '{print $1}')