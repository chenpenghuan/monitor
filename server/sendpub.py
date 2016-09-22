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
    def main(self):
        try:
            conts={'warn_level': 1, 'sendaddrs': 'cphchenpenghuan@gmail.com,kellychen@winnerlook.com,1034478083@qq.com', 'warn_cont': '通道319出现拥堵，已有9999条数据等待处理', 'script': 'testplugin.py', 'warn_title': '通道拥堵', 'id': '1'}
            print(conts)
            contstr=dumps(conts,ensure_ascii=False)
            self.conn.publish('dowarn',contstr)
            result=True
        except Exception as err:
            result=err
            print(result)
        finally:
            if result!=True:
                self.writelog(argv[0]+"\t"+str(result))
            else:
                print(conts)

if __name__ == "__main__":
    subcha = ['1']   #监听频道
    obj = formatdata('192.168.1.193', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/plugin1.log')
    #obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/wrn_test.php','/home/cph/jsons/plugin1.log')
    obj.main()
