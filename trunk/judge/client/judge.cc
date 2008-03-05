// Copyright 2008 Flood Team of Wuhan Univ.
// Author: yewen@mail.whu.edu.cn (Wen, YE)

#include <string>
#include <stdlib.h>

#include <sys/wait.h>

#include "args.h"
#include "client.h"
#include "logging.h"
#include "util.h"

#include "judge.h"

static int compareFiles(const string& standard_output_file_name,
                        const string& users_output_file_name) {
  FILE* standard_output_file = fopen(standard_output_file_name.c_str(), "r");
  FILE* users_output_file = fopen(users_output_file_name.c_str(), "r");
  if (standard_output_file == 0) {
    LOG(SYS_ERROR) << "Fail to open file " << standard_output_file_name;
    return SYSTEM_ERROR;
  }
  if (users_output_file == 0) {
    LOG(SYS_ERROR) << "Fail to open file " << users_output_file_name;
    return SYSTEM_ERROR;
  }

  int result = ACCEPTED;

  int c1 = fgetc(standard_output_file);
  int c2 = fgetc(users_output_file);

  while (c1 > 0 && c2 > 0) {
    if (c1 == c2) {
      c1 = fgetc(standard_output_file);
      c2 = fgetc(users_output_file);
    } else if (isspace(c1) || isspace(c2)) {
      while (isspace(c1)) {
        c1 = fgetc(standard_output_file);
      }
      while (isspace(c2)) {
        c2 = fgetc(users_output_file);
      }
      result = PRESENTATION_ERROR;
    } else {
      return WRONG_ANSWER;
    }
  }

  if (isspace(c1)) {
    while (isspace(c1))
      c1 = fgetc(standard_output_file);
    result = PRESENTATION_ERROR;
  }
  if (isspace(c2)) {
    while (isspace(c2))
      c2 = fgetc(users_output_file);
    result = PRESENTATION_ERROR;
  }

  if (c1 > 0 || c2 > 0)
    return WRONG_ANSWER;

  return result;
}

// TODO: Finish this function
static int runSpecialJudge(const string& special_judge_executable_file_name,
                           const string& standard_input_file_name,
                           const string& users_output_file_name) {
  int result = ACCEPTED;
  return result;
}

int doJudge(int communicate_socket,
            const string& standard_input_file_name,
            const string& standard_output_file_name,
            const string& users_output_file_name,
            const string& special_judge_executable_file_name) {
  sendReply(communicate_socket, JUDGING);
  int result;
  if (!special_judge_executable_file_name.empty()) {
    LOG(INFO) << "Run special judge for " << users_output_file_name;
    result = runSpecialJudge(special_judge_executable_file_name,
                             standard_input_file_name,
                             users_output_file_name);
  } else {
    LOG(INFO) << "Judge between " << standard_output_file_name << " and "
              << users_output_file_name;
    result = compareFiles(standard_output_file_name,
                          users_output_file_name);
  }
  sendReply(communicate_socket, result);
  switch(result) {
    case ACCEPTED : 
      LOG(INFO) << "Accepted";
      break;
    case PRESENTATION_ERROR :
      LOG(INFO) << "Presentation Error";
      break;
    case WRONG_ANSWER :
      LOG(INFO) << "Wrong Answer";
      break;
  }
  return result;
}