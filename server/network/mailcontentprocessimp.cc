#include "mailcontentprocessimp.h"

#include "object/mail.h"
#include "util/calulate.h"
#include "data/datainterface.h"
#include "base/util.h"
#include "base/logging.h"
#include "base/flags.h"
using namespace std;

void MailContentProcessImp::process(int socket_fd, const string& ip, int length){
  LOG(INFO) << "Process the Mail Content for:" << ip;
  char* buf;
  buf = new char[length+1];
  memset(buf,0,sizeof(buf));
  if (socket_read(socket_fd, buf, length) != length) {
    LOG(ERROR) << "Cannot read data from:" << ip;
    delete[] buf;
    return;
  }
  string read_data(buf, buf + length);
  delete[] buf;
  vector<string> datalist;
  spriteString(read_data, 1, datalist);
  vector<string>::iterator iter = datalist.begin();
  if (iter == datalist.end()) {
    LOG(ERROR) << "Cannot find mail_id from data for:" << ip;
    return;
  }
  int mail_id = atoi(iter->c_str());
  iter++;
  if (iter == datalist.end()) {
    LOG(ERROR) << "Cannot find user_id from data for:" << ip;
    return;
  }
  string user_id = *iter;
  iter++;
  Mail mail;
  mail = DataInterface::getInstance().getMail(mail_id);
  if ((mail.getToUser() != user_id) && (mail.getFromUser() != user_id)) {
    LOG(ERROR) << user_id  <<" cannot read the mail ";
    return;
  }
  if (!mail.getRead() && user_id == mail.getToUser()){
    mail.setRead(true);
    DataInterface::getInstance().setMailRead(mail);
  }
  string databuf;
  databuf = stringPrintf("%s\001%s\001%s\001%s", mail.getToUser().c_str(), 
                                                 mail.getFromUser().c_str(), 
                                                 mail.getTitle().c_str(), 
                                                 mail.getTime().c_str());
  databuf += "\001" + mail.getContent();
  string len = stringPrintf("%010d",databuf.length());
  if (socket_write(socket_fd, len.c_str(), 10)){
    LOG(ERROR) << "Send data failed to:" << ip;
    return;
  }
  if (socket_write(socket_fd, databuf.c_str(), databuf.length())) {
    LOG(ERROR) << "Cannot return data to:" << ip;
    return;
  }
  LOG(INFO) << "Process MailContent completed for" << ip;
}

