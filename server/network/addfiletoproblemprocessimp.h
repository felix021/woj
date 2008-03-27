#ifndef _FLOOD_SERVER_NETWORK_ADDFILETOPROBLEMPROCESSIMP_H__
#define _FLOOD_SERVER_NETWORK_ADDFILETOPROBLEMPROCESSIMP_H__

#include <string>
#include <vector>

#include "processimp.h"
using namespace std;

class AddFileToProblemProcessImp : public ProcessImp{
public:
  AddFileToProblemProcessImp() {}
  virtual ~AddFileToProblemProcessImp() {}

  void process(int socket_fd, const string& ip, int length);
private:
};

#endif

