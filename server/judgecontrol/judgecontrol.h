#ifndef _SERVER_JUDGECONTROL_JUDGECONTROL_H__
#define _SERVER_JUDGECONTROL_JUDGECONTROL_H__

#include <vector>

#include <pthread.h>

#include "object/list.h"
#include "judgecontrol/semaphore.h"
#include "judgecontrol/judgethread.h"

using namespace std;

#define MAXJUDGE 50

class JudgeControl{
public:
  friend class JudgeThread;
  JudgeControl();
  ~JudgeControl();
  void initJudge();
  void addMission(const JudgeMission& );
  JudgeMission getMission();
  void start();
  void join();
  void addRejudgeItem(const Status& status);
  void putReJudgeItem(const Status& status);

  static JudgeControl& getInstance() {
    if (instance == NULL) {
      instance = new JudgeControl;
      instance->initJudge();
    }
    return *instance;
  }

  static void destroy() {
    if (instance != NULL)
      delete instance;
    instance = NULL;
  }
private:
  static JudgeControl* instance;
  Semaphore semaphore;
  pthread_mutex_t queue_lock;
  pthread_mutex_t socket_lock;
  pthread_mutex_t set_lock;
  JudgeQueue judge_queue;
  RejudgeSet rejudge_set;
  JudgeThread judge_pool_[MAXJUDGE];
  int max_client_;
  int port_;
  int listen_fd_;
};

#endif

