#!/usr/bin/python3.5
# -*- coding: utf-8 -*-
# @Date    : 2016-09-22 11:44:12
# @Author  : 陈鹏欢 (cphchenpenghuan@gmail.com)
# @Link    : http://chenpenghuan.github.io
# @Version : 3.5


from redis import Redis
from urllib import request
from json import loads, dumps
from time import time, localtime, strftime
from sys import argv
from time import sleep
from os import path


class formatdata(object):

    def __init__(self, ip, port, password, subcha, url, logfile):
        '''
        ip为redis的ip，port为redis的port，password为redis的password，logfile为错误日志

        subcha（监听端口）、url(接口地址)主动获取时，在plugin中使用
        '''
        self.ip = ip
        self.port = port
        self.password = password
        self.subcha = subcha
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.url = url
        self.logfile = logfile

    def writelog(self, content):
        if path.isfile(self.logfile):
            logfile = open(self.logfile, 'a', encoding='utf-8')
        else:
            logfile = open(self.logfile, 'w', encoding='utf-8')
        logfile.write(strftime("%Y-%m-%d %H:%M:%S",
                               localtime(time())) + "\t" +
                      str(content) + "\r\n")
        logfile.close()

    def main(self, conts):
        try:
            contstr = dumps(conts, ensure_ascii=False)
            self.conn.publish('dowarn2', contstr)
            result = True
        except Exception as err:
            result = err
        finally:
            if result is not True:
                self.writelog(argv[0] + "\t" + str(result))


if __name__ == "__main__":
    subcha = ['1']  # 监听频道
    obj = formatdata('192.168.1.154', 6379, '123123', subcha,
                     'http://localhost/warn_test.php',
                     '/home/cph/jsons/plugin1.log')
    conts = {
        'id': 4,
        'params':'ok',
        'title': '报警信息标题',
        'content': '报警信息内容'
    }
    obj.main(conts)
    #print(dumps(conts))
