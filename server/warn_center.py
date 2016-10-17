#!/usr/bin/python3.5
# -*- coding: utf-8 -*-
# @Date    : 2016-09-22 11:44:12
# @Author  : 陈鹏欢 (cphchenpenghuan@gmail.com)
# @Link    : http://chenpenghuan.github.io
# @Version : 3.5)

import smtplib
from email.header import Header
from email.mime.text import MIMEText
from email.utils import parseaddr, formataddr
from json import loads, dumps
from os import path
from time import time, localtime, strftime

from pymysql import connect
from redis import Redis


class Recieve(object):
    def __init__(self, ip, port, password, subcha, configfile, logfile):
        self.ip = ip
        self.port = port
        self.password = password
        self.subcha = subcha
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.configfile = configfile
        self.logfile = logfile
        self.dbhost = '192.168.1.154'
        self.dbuser = 'root'
        self.dbpassword = '123123'
        self.dbname = 'winnerlook'
        self.dbport = 3306
        self.dbcharset = 'utf8'

    def writelog(self, content):
        logfile = open(self.logfile, 'a', encoding='utf-8')
        logfile.write(strftime("%Y-%m-%d %H:%M:%S", localtime(time())) + "\t" + str(content) + "\r\n")
        logfile.close()

    def writesql(self, sql):
        try:
            mydb = connect(host=self.dbhost, user=self.dbuser, password=self.dbpassword, db=self.dbname,
                           port=self.dbport, charset=self.dbcharset)
            cur = mydb.cursor()
            cur.execute(sql)
            mydb.commit()
            cur.close()
            mydb.close()
            result = True
        except Exception as err:
            result = err
        finally:
            return result

    def readconf(self):
        result = False
        if path.isfile(self.configfile):
            try:
                file = open(self.configfile)
                contstr = file.read()
                file.close()
                if contstr == '':
                    result = False
                else:
                    result = loads(contstr)
            except Exception:
                result = False
        if result == False:
            conn = connect(host=self.dbhost, user=self.dbuser, password=self.dbpassword, db=self.dbname,
                           port=self.dbport, charset=self.dbcharset)
            cur = conn.cursor()
            count = cur.execute(
                'select id,warn_type,warn_level,warn_key,warn_value,warn_logic,warn_send from warn_conf')
            result = cur.fetchall()
            cur.close()
            conn.close()
            dic = {}
            for i in result:
                dic[i[0]] = (i[1], i[2], i[3], i[4], i[5], i[6])
            file = open(self.configfile, 'w', encoding='utf-8')
            file.write(dumps(dic))
            file.close()
            result = dic
        return result

        return self.configfile

    def _format_addr(self, s):
        name, addr = parseaddr(s)
        return formataddr((Header(name, 'utf-8').encode(), addr))

    def send_mail(self, from_addr='13919636933@139.com', password='575chenpenghuan',
                  to_addr='cphchenpenghuan@gmail.com,1034478083@qq.com', smtp_server='smtp.139.com',
                  smtp_server_port=25, content='邮件正文', from_user='发件人', to_user='云集管理员', title='邮件标题'):
        try:
            msg = MIMEText(content, 'plain', 'utf-8')
            msg['From'] = self._format_addr(from_user + ' <%s>' % from_addr)
            msg['To'] = self._format_addr(to_user + ' <%s>' % to_addr)
            msg['Subject'] = Header(title, 'utf-8').encode()
            server = smtplib.SMTP_SSL(smtp_server, smtp_server_port)
            server.set_debuglevel(0)
            server.login(from_addr, password)
            server.sendmail(from_addr, to_addr.split(','), msg.as_string())
            server.quit()
            result = True
        except Exception as err:
            result = err
        finally:
            return result

    def main(self):
        sub = self.conn.pubsub()
        sub.subscribe(self.subcha)
        for msg in sub.listen():
            if msg['type'] == 'message':
                # print(msg['data'].decode('utf-8'))
                try:
                    conts = loads(msg['data'].decode('utf-8'))
                    configs = self.readconf()
                    if configs.get(str(conts['id'])):
                        configs = configs.get(str(conts['id']))
                    # print(configs)
                    act = 0  # 只存储信息，不做报警处理
                    if int(configs[4]) == 4:  # 如果逻辑条件是无条件报警
                        act = 1
                    if int(configs[4]) == 1:  # 如果逻辑条件是大于
                        if int(conts[str(configs[2])]) > int(configs[3]):
                            act = 1
                    if int(configs[4]) == 2:  # 如果逻辑条件是小于
                        if int(conts[str(configs[2])]) < int(configs[3]):
                            act = 1
                    if int(configs[4]) == 3:  # 如果逻辑条件是包含
                        contains = configs[3].split(',')
                        for i in contains:
                            if i in conts[str(configs[2])]:
                                act = 1
                    # print(str(configs[3]))       #报警设置中的阀值
                    # print('act:' + str(act))
                    if act == 1:  # 需要报警
                        if len(configs[5]) > 1:
                            result = self.send_mail(from_addr='kellychen@winnerlook.com', password='cCpPhH573',
                                                    smtp_server='smtp.exmail.qq.com', smtp_server_port=465,
                                                    from_user='云集监控', to_user='云集管理员', content=conts.get('content'),
                                                    title=conts.get('title'), to_addr=configs[5])  # 发送报警邮件，如果失败则写入日志文件
                            # print(result)
                            # print("报警信息发送成功")
                            if result != True:
                                self.writelog(conts['script'] + result)
                    sql = 'insert into warn_cont(warn_send,warn_id,warn_value,warn_title,warn_cont,warn_date) values(' + str(
                        act) + ',' + str(conts['id']) + ',\'' + str(conts.get(str(configs[2]))) + '\',\'' + str(
                        conts.get('title')) + '\',\'' + str(conts.get('content')) + '\',\'' + strftime(
                        "%Y-%m-%d %H:%M:%S", localtime(time())) + '\')'
                    # print(sql)
                    result = self.writesql(sql)
                    # print("报警记录写入成功")
                    if result != True:
                        self.writelog(result)
                    result = True
                except Exception as err:
                    result = err
                finally:
                    if result != True:  # 如果返回值不为True，则将错误信息写入数据库
                        if conts.get('id'):
                            self.writelog(str(conts.get('id')) + "\t" + str(result))
                        else:
                            self.writelog('未知id' + "\t" + str(result))


if __name__ == "__main__":
    subcha = ['warn_center']
    obj = Recieve('192.168.1.154', 6379, '123123', subcha, '/home/cph/jsons/warn_config.json',
                  '/home/cph/jsons/logfile.log')
    obj.main()
