1��php.ini������

For windows: (�ļ�·������װĿ¼/php.ini)
{
  error_reporting  =  E_ALL
  display_errors = Off
  magic_quotes_gpc = Off
  upload_max_filesize = 8M
  output_buffering = 4096

  extension=php_sockets.dll
  extension=php_gd2.dll
}

For ubuntu: ���ļ�·����/etc/php5/apache2/php.ini��
{
  error_reporting  =  E_ALL
  display_errors = Off
  magic_quotes_gpc = Off
  upload_max_filesize = 8M
  output_buffering = 4096

  ��װgd��չ��
      sudo apt-get install php5-gd
  extension=gd.so

}

2, Ubuntu�����������ļ�Ϊ��д��flood/files, flood/contest/problems, flood/include/note.html 
   chmod o+w flood/files
   chmod o+w flood/contest/problems
   chmod o+w flood/include/note.html

3, �����ļ�:
flood/common/config.php
flood/admin/common/config.php

4��admin,files������һ���������ϣ������ɲ����ڶ����������
