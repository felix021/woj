#include <vector>
#include <string>

#include "base/flags.h"
#include "base/logging.h"

#include "object/inc.h"
#include "data/datainterface.h"
#include "util/calulate.h"
#include "object/file.h"
#include "object/bufsize.h"
#include "object/problem.h"
#include "object/list.h"
#include "object/info.h"
#include "util/filetype.h"
#include "object/user.h"
#include "addinputandoutputfileprocessimp.h"

using namespace std;

DECLARE_FLAGS(string, root_dir);

void AddInputAndOutputProcessImp::process(int socket_fd, const string& ip, int length){
  LOG(INFO) << "Process add input and output file for:" << ip;
  BufSize buf;
  char len[11];
  char problem_buf[11];
  memset(len, 0, sizeof(len));
  memset(problem_buf, 0, sizeof(problem_buf));
  File input, output;
  if (socket_read(socket_fd, problem_buf, 10) != 10) {
    LOG(ERROR) << "Cannot read problem_id from:" << ip;
    return;
  }
  int problem_id = atoi(problem_buf);
  LOG(INFO) << "[Problem]:" << problem_id;
  //the sentence in not need because i have solved the file version for problem//DataInterface::getInstance().disableInputAndOutput(problem_id);
  Problem problem(problem_id);
  problem = DataInterface::getInstance().getProblem(problem_id);
  string path;
  string time = getLocalTimeAsString("%Y-%m-%d %H:%M:%S");
  for (int i = 0; i < length; i++) {
    if (socket_read(socket_fd, len, 10) != 10) {
      LOG(ERROR) << "Cannot read input length from:" << ip;
      return;
    }
    int input_len = atoi(len);
    LOG(DEBUG) << stringPrintf("input_len:%d", input_len);
    buf.alloc(input_len);
    if (socket_read(socket_fd, buf.getBuf(), input_len) != input_len) {
      LOG(ERROR) << "Cannot read input from:" << ip;
      return;
    }
    path = getProblemDataPath(problem_id) + stringPrintf("%d.in", i);
    input.setPath(path);
    input.setType(INFILE);
    int input_id = DataInterface::getInstance().addFilePathtoProblem(input, problem);
    DataInterface::getInstance().addFile(path, buf.getBuf(), input_len);
    if (socket_read(socket_fd, len, 10) != 10) {
      LOG(ERROR) << "Cannot read output length from:" << ip;
      return;
    }
    int output_len = atoi(len); 
    LOG(DEBUG) << stringPrintf("output_len:%d", output_len);
    buf.alloc(output_len);
    if (socket_read(socket_fd, buf.getBuf(), output_len) != output_len) {
      LOG(ERROR) << "Cannot read output from:" << ip;
      return;
    }
    path = getProblemDataPath(problem.getProblemId()) + stringPrintf("%d.out", i);
    output.setPath(path);
    output.setType(OUTFILE);
    int output_id = DataInterface::getInstance().addFilePathtoProblem(output, problem);
    DataInterface::getInstance().addFile(path, buf.getBuf(), output_len);
    DataInterface::getInstance().addInputtoOutput(input_id, output_id);
  }
  if (sendReply(socket_fd, 'Y') != 0) {
    LOG(ERROR) << "Send Reply failed.";
    return;
  }
  LOG(INFO) << "Process add input and output file completed for:" << ip;
}

