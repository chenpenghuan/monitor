from time import sleep
from urllib import request
from threading import Thread
from redis import Redis
from os import path
from pymysql import connect
from json import loads, dumps


class WarnCheck(object):

    def __init__(self, ip, port, password,statusfile,configfile):
        self.ip = ip
        self.port = port
        self.password = password
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.statusfile=statusfile
        self.configfile=configfile
    def writeconfs(self):
        conn = connect(
            host='127.0.0.1',
            user='root',
            password='123123',
            db='winnerlook',
            charset='utf8',
            port=3306)
        cur = conn.cursor()
        count = cur.execute('select id,warn_type,warn_send from warn_conf')
        result = cur.fetchall()
        cur.close()
        conn.close()
        dic={}
        for i in result:
            dic[i[0]]=(i[1],i[2])
        file=open(self.configfile,'w',encoding='utf-8')
        file.write(dumps(dic))
        file.close()
        file=open(self.statusfile,'w',encoding='utf-8')
        file.write(dumps({"status":0}))
        file.close()
        return dic
    def main(self):
        t = 0
        while t < 100:
            sleep(5)
            if path.isfile(self.statusfile):
                statusfile = open(self.statusfile, 'r', encoding='utf-8')
                status_str = statusfile.read()
                statusfile.close()
                config=loads(status_str)
                if config['status']==0:
                    configfile = open(self.configfile, 'r', encoding='utf-8')
                    config_str = configfile.read()
                    configfile.close()
                    dic=loads(config_str)
            else:
                dic=self.writeconfs()
            for i in dic:
                self.conn.publish(i, dumps(dic[i],ensure_ascii=False))
            if t == 99:
                t = 0
            t = t + 1
if __name__ == "__main__":
    obj = WarnCheck('127.0.0.1', 6379, '123123','/home/cph/jsons/warn_status.json','/home/cph/jsons/warn_config.json')
    obj.main()
