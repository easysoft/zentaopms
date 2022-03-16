#!/bin/bash
#This script is use to rename files
for file in * ;
do mv  $file `echo $file |  tr 'A-Z' 'a-z'`;
done
