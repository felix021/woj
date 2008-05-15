#ifndef _FLOOD_SERVER_DATA_JOB_H__
#define _FLOOD_SERVER_DATA_JOB_H__

#include <string>
using namespace std;

typedef struct _SET_ITEM_ {
  vector<int> problem_list;
  int number;
  int set_id;
}SetItem;

class Job{
public:
  Job(const string& description = "NULL");
  
  vector<int> getProblemList() const;
  vecotr<SetItem> getSetList() const;
  int getJobId() const;
  string getDescription() const;
  string getPublishTime() const;
  string getTeacher() const;
  int getYear() const;
  char getTerm() const;
  bool getAvailable() const;
  
  void setProblemList(const vector<int>&);
  void setSetList(const vector<SetItem>&);
  void setJobId(int);
  void setDescription(const string&); 
  void setPublishTime(const string&);
  void setTeacher(const string&);
  void setYear(int year);
  void setTerm(char term);
  void setAvailable(bool available);

private:
  vector<int> problem_list_;
  vector<SetItem> set_list_;
  int job_id_;
  string description_;
  string publish_time_;
  string user_id_;
  int year_;
  char term_;
  bool available_;
};

typedef struct _JOB_ITEM_ {
  int job_id;
  string description;
  string publish_time;
  string teacher;
  int year;
  char term;
  bool available;
}JobItem;

typedef vector<JobItem> JobList;
#endif /*JOB_H_*/
