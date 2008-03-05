// Copyright 2008 Flood Team of Wuhan Univ.
// Author: yewen@mail.whu.edu.cn (Wen, YE)

#ifndef _FLOOD_JUDGE_CLIENT_UTIL_H__
#define _FLOOD_JUDGE_CLIENT_UTIL_H__

#include <string>

using namespace std;

int socket_write(int communicate_socket,
                 const void* buffer,
                 size_t buffer_length);

static inline int sendReply(int communicate_socket, char reply_message) {
  return socket_write(communicate_socket, &reply_message, 1);
}

string getLocalTimeAsString(const char* format);

#endif
