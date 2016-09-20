from time import sleep
from urllib import request
from threading import Thread
from redis import Redis
from os import path
from pymysql import connect
from json import loads, dumps


class WarnCheck(object):

    def __init__(self, ip, port, password):
        self.ip = ip
        self.port = port
        self.password = password
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
    def main(self, confs):
        self.confs = confs
        t = 0
        while t < 100:
            sleep(5)
            if path.isfile('status.json'):
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
                statusfile = open('status.json', 'r', encoding='utf-8')
                status_str = statusfile.read()
                statusfile.close()
                config=loads(status_str)
                if config['status']=='0':
                    pass
                else:
                    for i in result:
                        a,b,c=i
                        self.confs[str(a)]=dumps([b,c],ensure_ascii=False)
            else:
                print('没有status文件')
            print(self.confs)
            for i in self.confs:
                self.conn.publish(i, self.confs[i])
            if t == 99:
                t = 0
            t = t + 1
if __name__ == "__main__":
    confs = {'1': '通道拥堵', '2': 'MAS报错', '3': '未知错误'}
    obj = WarnCheck('127.0.0.1', 6379, '123123')
    obj.main(confs)
