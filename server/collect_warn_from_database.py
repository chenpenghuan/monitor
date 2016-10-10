#!/usr/bin/python3.5
# -*- coding: utf-8 -*-
# @Date    : 2016-09-22 11:44:12
# @Author  : 陈鹏欢 (cphchenpenghuan@gmail.com)
# @Link    : http://chenpenghuan.github.io
# @Version : 3.5


from redis import Redis
from urllib import request
from json import loads, dumps
from time import time,localtime,strftime
from sys import argv
from pymysql import connect,cursors


class formatdata(object):

    def __init__(self, ip, port, password, subcha,url,logfile,dbhost,dbuser,dbpass,dbname,dbport,dbcharset):
        self.ip = ip
        self.port = port
        self.password = password
        self.subcha = subcha
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.url=url
        self.logfile=logfile
        self.dbhost=dbhost
        self.dbuser=dbuser
        self.dbpass=dbpass
        self.dbname=dbname
        self.dbport=dbport
        self.dbcharset=dbcharset
    def writelog(self,content):
        logfile=open(self.logfile,'a')
        logfile.write(strftime("%Y-%m-%d %H:%M:%S",localtime(time()))+"\t"+str(content)+"\r\n")
        logfile.close()
        print(self.logfile)
    def main(self):
        sub = self.conn.pubsub()
        sub.subscribe(self.subcha)
        for msg in sub.listen():
            if msg['type'] == 'message':    #这里开始插件各自的自定义操作
                print('进来了')
                try:
                    conn=connect(
                        host=self.dbhost,
                        user=self.dbuser,
                        password=self.dbpass,
                        db=self.dbname,
                        port=self.dbport,
                        charset=self.dbcharset,
                        cursorclass=cursors.DictCursor)
                    cur = conn.cursor()
                    count = cur.execute('select * from warn_cols_conf')     # 查询出所有的已配置的统计报警信息
                    warn_cols_conf = cur.fetchall()
                    cur.close()
                    conn.close()
                    print(warn_cols_conf)
                    result=True
                except Exception as err:
                    result=err
                    #print(result)
                finally:
                    if result!=True:
                        self.writelog(argv[0]+"\t"+str(result))
if __name__ == "__main__":
    subcha = ['warn_collect']   #监听频道
    obj = formatdata('192.168.1.154', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/plugin1.log','192.168.1.154','root','123123','winnerlook',3306,'utf8')
    #obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/wrn_test.php','/home/cph/jsons/plugin1.log')
    obj.main()
