from time import sleep
from socketserver import (
    TCPServer as TCP,
    # StreamRequestHandler as SRH,
    ThreadingMixIn as TMI,
    BaseRequestHandler as BRH,
    ThreadingTCPServer as TTCP
)
# 变动位置
# from time import ctime
from urllib.request import urlopen
from json import dumps, loads
from pymysql import connect
from os import path
HOST = '192.168.168.130'
PORT = 8001
ADDR = (HOST, PORT)


class Server(TMI, TCP):  # 变动位置
    pass


class MyRequestHandler(BRH):
    safeip=['192.168.1.5','192.168.168.130']
    confsfile = '/home/cph/jsons/cont_conf.json'
    statusfile = '/home/cph/jsons/conf_status.json'
    confs = {}

    def checkconf(self):
        try:
            statusfile = open(self.statusfile)
            status = statusfile.read()
            statusfile.close()
            status = loads(status)
            # print(status)
            if int(status['status']) == 1 or not path.isfile(self.confsfile):
                status = True
                statusfile = open(self.statusfile, 'w')
                statusfile.write(dumps({'status': 0}))
                statusfile.close()
            else:
                status = False
        except Exception:
            status = True
        finally:
            return status
    def insert(self,sql):
        try:
            conn = connect(host='127.0.0.1', user='root', passwd='123123',db='winnerlook', port=3306, charset='utf8')
            cur = conn.cursor()
            cur.execute(sql)
            result=True
        except Exception as err:
            result=False
        finally:
            conn.commit()
            cur.close()
            conn.close()
            return result
    def readconf(self):
        try:
            conn = connect(host='127.0.0.1', user='root', passwd='123123',db='winnerlook', port=3306, charset='utf8')
            cur = conn.cursor()
            #sql = 'select cont_conf.item_id,contents.id,cont_conf.cont_var,cont_conf.cont_url,cont_conf.cont_sec,contents.cont_text,contents.update_sec from cont_conf left join contents on cont_conf.id=contents.cont_id order by item_id,cont_sec'
            sql = 'select item_id,id,cont_var,cont_url,cont_sec,cont_title from cont_conf'
            cur.execute(sql)
            data = cur.fetchall()
        except Exception as err:
            data = 'connection error:' + str(err)
        finally:
            conn.commit()
            cur.close()
            conn.close()
            return data

    def writeconf(self, data):
        try:
            for m in data:
                    # db.insert({"id": m[0], "name": m[1], "cont": m[2]})
                    # self.confs[m[0]][m[1]] = m[2]
                if self.confs.get(m[0]) is None:
                    self.confs[m[0]]={}
                self.confs[m[0]][m[1]] ={"cont_var":m[2],"cont_url":m[3],"cont_sec":m[4]}
            confsfile = open(self.confsfile, 'w', encoding='utf-8')
            confsfile.write(dumps(self.confs))
            confsfile.close()
            result = True
        except Exception:
            result = False
        finally:
            return result

    def handle(self):
        #print(self.readconf())
        if self.client_address[0] in self.safeip:
            print('此IP允许访问')
            self.data = self.request.recv(10240)
            self.request.sendall('收到的请求内容是'.encode('utf-8') + self.data)
            if self.checkconf():
                data = self.readconf()
                self.writeconf(data)
            else:
                confsfile = open(self.confsfile)
                self.confs = loads(confsfile.read())
                confsfile.close()
            for m in self.confs:
                for n in self.confs[m]:
                    #print(self.confs[m][n]['cont_url'][0:17])
                    data =loads(urlopen(self.confs[m][n]['cont_url'][0:17]+'test.php').read().decode('utf-8'))
                    sql='update contents set isshow=0 where cont_id='+str(n)
                    print(self.insert(sql))
                    upsec=1
                    for i in data:
                        if i.get(self.confs[m][n]['cont_var']):
                            #sql='insert into contents values('+str(n)+','+i.get(self.confs[m][n]['cont_var'])+','+str(upsec)+',"2016-06-08 13:37:59")'
                            sql='insert into contents(cont_id,cont_text,update_sec,update_date) values('+str(n)+',"'+i.get(self.confs[m][n]['cont_var'])+'",'+str(upsec)+',"2016-06-08 13:37:59")'
                            print(self.insert(sql))
                            upsec=upsec+1
                            print(sql)
            print(self.client_address)
        else:
            print('此IP不允许访问')
# 变动位置
if __name__=="__main__":
    TCP.allow_reuse_address=True
    tcpServ = TTCP(ADDR, MyRequestHandler)
    print('等待新的连接。。。。')
    tcpServ.serve_forever()

