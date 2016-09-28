from redis import Redis
from urllib import request
from json import loads, dumps
from pymysql import connect
from time import time,localtime,strftime
from email import encoders
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
            mydb=connect(host='127.0.0.1',user='root',password='123123',db='winnerlook',port=3306,charset='utf8')
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
    def send_mail(self,nameadd='云集监控 <13919636933@139.com>',content='监控接口的主要内容',fromuser='云集监控',touser='云集管理员',title='监控接口返回的主题',toaddrs=['1034478083@qq.com']):
        try:
            name,add=parseaddr(nameadd)
            msg = MIMEText(content, 'plain', 'utf-8')
            #msg['From'] = formataddr((Header(fromuser,'utf-8').encode(),'13919636933@139.com'))
            msg['From'] = formataddr((Header(fromuser,'utf-8').encode(),add))
            #msg['To'] = formataddr((Header(touser,'utf-8').encode(),str(toaddrs)))
            msg['Subject']=Header(title,'utf-8').encode()
            server = smtplib.SMTP('smtp.139.com', 25) # SMTP协议默认端口是25
            server.set_debuglevel(0)
            #server.login('13919636933@139.com','573chenpenghuan')
            server.login(add,'575chenpenghuan')
            server.sendmail(add, toaddrs, msg.as_string())
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
                #print(msg['channel'].decode('utf-8'))
                jsonstr = request.urlopen(self.url).read().decode('utf-8')
                #print(jsonstr)
                try:
                    conts = loads(jsonstr)
                    if int(conts.get('warn_level')) > 0:
                        sql = 'insert into warn_cont(warn_id,warn_cont,warn_date,warn_level) values(' + msg['channel'].decode('utf-8') + ',"' + conts['warn_cont'] + '","'+strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+'",'+str(conts['warn_level'])+')'
                        #下面是错的sql，用来测试容错
                        #sql = 'insert int warn_cont(warn_id,warn_cont,warn_date,warn_level) values(' + msg['channel'].decode('utf-8') + ',"' + conts['warn_cont'] + '","'+strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+'",'+str(conts['warn_level'])+')'
                        result=self.writesql(sql)
                        #print(result)
                        #报警内容写入数据库，如果失败则写入日志文件
                        if result!=True:
                            self.writelog(result)
                        # 发送报警邮件，如果失败则写入日志文件
                        sendaddrs=loads(msg['data'].decode('utf-8'))
                        if len(sendaddrs)>1:
                            sendaddrs=sendaddrs[1].split(',')
                            print(sendaddrs)
                            result=self.send_mail(content=conts.get('warn_cont'),title=conts.get('warn_title'),toaddrs=sendaddrs)
                            print(result)
                            if result!=True:
                                self.writelog(result)
                    result=True
                except Exception as err:
                    result=err
        if result!=True:
            self.writelog(result)

if __name__ == "__main__":
    subcha = ['1']
    obj = Recieve('127.0.0.1', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/plugin1.log')
    obj.main()
