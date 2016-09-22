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
        sub = self.conn.pubsub()
        sub.subscribe(self.subcha)
        for msg in sub.listen():
            if msg['type'] == 'message':    #这里开始插件各自的自定义操作
                try:
                    jsonstr = request.urlopen(self.url).read().decode('utf-8')
                    conts = loads(jsonstr)      #这里做数据处理，将爬取到的数据转换成处理报警的服务能够解读的数据
                    print(conts)
                    conts['id']=msg['channel'].decode('utf-8')
                    sendaddrs=loads(msg['data'].decode('utf-8'))[1]
                    conts['sendaddrs']=sendaddrs
                    conts['script']=argv[0]
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
    obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/warn_test.php','/home/cph/jsons/plugin1.log')
    #obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/wrn_test.php','/home/cph/jsons/plugin1.log')
    obj.main()
