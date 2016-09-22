#!/usr/bin/python3.5
# -*- coding: utf-8 -*-
# @Date    : 2016-09-22 11:44:12
# @Author  : 陈鹏欢 (cphchenpenghuan@gmail.com)
# @Link    : http://chenpenghuan.github.io
# @Version : 3.5)
'''
输入数据格式样例:

{'warn_level': 1, 'sendaddrs': 'cphchenpenghuan@gmail.com,kellychen@winnerlook.com,1034478083@qq.com', 'warn_cont': '通道319出现拥堵，已有9999条数据等待处理', 'script': 'testplugin.py', 'warn_title': '通道拥堵', 'id': '1'}}

其中：
    *warn_level为报警级别，据此判断发邮件、短信还是语音呼叫；
    *sendaddrs是接收人，在报警配置中已有预设在redis订阅信息中可以查到，插件制作者也可自定义；
    *warn_cont是报警内容；
    script是报警程序名，为了在出现异常时写入日志以方便追踪，如果不设置，出现异常时将id写入日志，如果id也没有，就记录未知程序未知频道
    warn_title是报警信息标题，比如邮件报警时，就是邮件的标题，
    *id是监控频道，这个与报警配置关联
'''

from redis import Redis
from json import loads
from pymysql import connect
from time import time,localtime,strftime
from email.header import Header
from email.mime.text import MIMEText
from email.utils import parseaddr, formataddr
import smtplib

class Recieve(object):

    def __init__(self, ip, port, password, subcha,url,logfile):
        self.ip = ip
        self.port = port
        self.password = password
        self.subcha = subcha
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.url=url
        self.logfile=logfile
    def writelog(self,content):
        logfile=open(self.logfile,'a',encoding='utf-8')
        logfile.write(strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+"\t"+str(content)+"\r\n")
        logfile.close()
    def writesql(self,sql):
        try:
            mydb=connect(host='192.168.1.193',user='root',password='123123',db='winnerlook',port=3306,charset='utf8')
            cur=mydb.cursor()
            cur.execute(sql)
            mydb.commit()
            cur.close()
            mydb.close()
            result=True
        except Exception as err:
            result=err
        finally:
            return result
    def _format_addr(self,s):
        name, addr = parseaddr(s)
        return formataddr((Header(name, 'utf-8').encode(), addr))
    def send_mail(self, from_addr='13919636933@139.com',password='575chenpenghuan',to_addr='cphchenpenghuan@gmail.com,1034478083@qq.com',smtp_server='smtp.139.com',content='邮件正文',from_user='发件人',to_user='云集管理员',title='邮件标题'):
        try:
            msg = MIMEText(content, 'plain', 'utf-8')
            msg['From'] = self. _format_addr(from_user+' <%s>' % from_addr)
            msg['To'] = self._format_addr(to_user+' <%s>' % to_addr)
            msg['Subject'] = Header(title, 'utf-8').encode()
            server = smtplib.SMTP(smtp_server, 25)
            server.set_debuglevel(0)
            server.login(from_addr, password)
            server.sendmail(from_addr, to_addr.split(','), msg.as_string())
            server.quit()
            result=True
        except Exception as err:
            result=err
        finally:
            return result
    def main(self):
        sub = self.conn.pubsub()
        sub.subscribe(self.subcha)
        for msg in sub.listen():
            if msg['type'] == 'message':
                try:
                    conts=loads(msg['data'].decode('utf-8'))
                    if int(conts.get('warn_level')) > 0:
                        sql = 'insert into warn_cont(warn_id,warn_cont,warn_date,warn_level) values(' + conts['id'] + ',"' + conts['warn_cont'] + '","'+strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+'",'+str(conts['warn_level'])+')'
                        print(sql)
                        #下面是错的sql，用来测试容错
                        #sql = 'insert int warn_cont(warn_id,warn_cont,warn_date,warn_level) values(' + msg['channel'].decode('utf-8') + ',"' + conts['warn_cont'] + '","'+strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+'",'+str(conts['warn_level'])+')'
                        result=self.writesql(sql)   #报警内容写入数据库，如果失败则写入日志文件
                        print(result)
                        if result!=True:
                            self.writelog(result)
                        sendaddrs=conts['sendaddrs']
                        if len(sendaddrs)>1:
                            result=self.send_mail(from_user='云集监控',to_user='云集管理员',content=conts.get('warn_cont'), title=conts.get('warn_title'),to_addr=sendaddrs)    # 发送报警邮件，如果失败则写入日志文件
                            print(result)
                            if result!=True:
                                self.writelog(conts['script']+result)
                    result=True
                except Exception as err:
                    result=err
                finally:
                    if result!=True:    #如果返回值不为True，则将错误信息写入数据库
                        if conts.get('script'):
                            self.writelog(conts.get('script')+"\t"+str(result))
                        elif conts.get('id'):
                            self.writelog(conts.get('id')+"\t"+str(result))
                        else:
                            self.writelog('未知脚本未知频道'+"\t"+str(result))

if __name__ == "__main__":
    subcha = ['dowarn']
    obj = Recieve('192.168.1.193', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/logfile.log')
    obj.main()
