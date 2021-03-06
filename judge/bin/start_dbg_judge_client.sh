#!/bin/bash
set -e

#if [ "$1" == "" ]; then
#  echo "Usage : start.sh <server address>:<server port>"
#  exit 1
#fi

#address=${1%:*}
#port=${1##*:}

address="127.0.0.1"
port=3241

if [ "`which gcc`" != "" ]; then
  support_lang=$support_lang,c
fi

if [ "`which g++`" != "" ]; then
  support_lang=$support_lang,cc
fi

if [ "`which fpc`" != "" ]; then
  support_lang=$support_lang,pas
fi

if [ "`which gcj`" != "" ]; then
  support_lang=$support_lang,java
fi

ids=`cat /etc/passwd | grep flood | awk -F ':' '{print "--uid=" $3, "--gid=" $4}'`
cmd="./judge_client --support_lang=\"$support_lang\" --daemon --root_dir=\"/home/flood/worktemp\" --server_address=\"$address\" --server_port=$port $ids --log_level=5 --logtostderr=true"
echo $cmd
eval $cmd
