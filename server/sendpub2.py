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
from time import sleep

class formatdata(object):

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
    def main(self,conts):
        try:
            contstr=dumps(conts,ensure_ascii=False)
            self.conn.publish('dowarn2',contstr)
            result=True
        except Exception as err:
            result=err
        finally:
            if result!=True:
                self.writelog(argv[0]+"\t"+str(result))

if __name__ == "__main__":
    i=0
    while i<100:
        sleep(5)
        subcha = ['1']   #监听频道
        obj = formatdata('192.168.1.193', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/plugin1.log')
        #obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/wrn_test.php','/home/cph/jsons/plugin1.log')
        conts={
            'id': 3,
            'params':'我是一只小小鸟',
            'title':'报警信息标题',
            'content':'报警信息内容'
            }
        #obj.main(conts)
        if i>80:
            i=0
        print(dumps(conts))
