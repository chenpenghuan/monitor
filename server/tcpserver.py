from urllib.request import urlopen
from json import dumps, loads
from pymysql import connect
from os import path
from time import strftime, time, localtime, sleep


class Monitor(object):

    def __init__(self, confsfile, statusfile, logfile):
        self.confsfile = confsfile
        self.statusfile = statusfile
        self.confs = {}
        self.logfile = logfile

    def traceproc(self, logcont):
        lf = open(self.logfile, 'a')
        lf.write(
            logcont + "\t" + str(strftime("%Y-%m-%d %H:%M:%S", localtime(time())))+"\r\n")
        lf.close()

    def checkconf(self):
        result = {}
        result[0] = True
        result[1] = ''
        try:
            if path.isfile(self.statusfile):
                statusfile = open(self.statusfile)
                status = statusfile.read()
                statusfile.close()
                status = loads(status)
                if int(status['status']) == 0:
                    result[0] = False
                    result[1] = ''
        except Exception as err:
            result[0] = True
            result[1] = str(err)
        finally:
            return result

    def insert(self, sql):
        result = {}
        try:
            conn = connect(
                host='127.0.0.1',
                user='root',
                passwd='123123',
                db='winnerlook',
                port=3306,
                charset='utf8')
            cur = conn.cursor()
            cur.execute(sql)
            result[0] = True
            result[1] = None
        except Exception as err:
            result[0] = False
            result[1] = str(err)
        finally:
            conn.commit()
            cur.close()
            conn.close()
            return result

    def readconf(self):
        data = {}
        try:
            conn = connect(host='127.0.0.1', user='root',
                           passwd='123123', db='winnerlook', port=3306, charset='utf8')
            cur = conn.cursor()
            sql = 'select item_id,id,cont_var,cont_url,cont_sec,cont_title from cont_conf'
            cur.execute(sql)
            data[0] = True
            data[1] = cur.fetchall()
            conn.commit()
            cur.close()
            conn.close()
        except Exception as err:
            data[0] = False
            data[1] = 'connection error:' + str(err)
        finally:
            return data

    def writeconf(self, data):
        result = {}
        try:
            for m in data:
                    # db.insert({"id": m[0], "name": m[1], "cont": m[2]})
                    # self.confs[m[0]][m[1]] = m[2]
                if self.confs.get(m[0]) is None:
                    self.confs[m[0]] = {}
                self.confs[
                    m[0]][m[1]] = {"cont_var": m[2], "cont_url": m[3], "cont_sec": m[4]}
            confsfile = open(self.confsfile, 'w', encoding='utf-8')
            confsfile.write(dumps(self.confs))
            confsfile.close()
            result[0] = True
            result[1] = ''
            statusfile = open(self.statusfile, 'w')
            statusfile.write(dumps({'status': 0}))
            statusfile.close()
        except Exception as err:
            result[0] = False
            result[1] = str(err)
        finally:
            return result

    def handle(self):
        cc = self.checkconf()
        # print(cc)
        result = {}
        if cc.get(0):
            print('需要刷新配置数据')
            self.traceproc('需要刷新配置数据'+"\t"+str(cc[1]))
            data = self.readconf()
            while data[0] == False:
                sleep(3)
                print('数据库连接不上'+str(data[1]))
                self.traceproc('数据库连接不上'+"\t"+str(data[1]))
                data = self.readconf()
            data = data[1]
            result = self.writeconf(data)
            # print(result)
        if result.get(0) == False:
            self.logfile('写入配置文件错误'+"\t"+str(result[1]))
            return False
        confsfile = open(self.confsfile)
        self.confs = loads(confsfile.read())
        confsfile.close()
        for m in self.confs:
            for n in self.confs[m]:
                try:
                    urlcont = urlopen(
                        self.confs[m][n]['cont_url']).read().decode('utf-8')
                    # print(urlcont)
                    data = loads(urlcont)
                    sql = 'update contents set isshow=0 where cont_id=' + \
                        str(n)
                    it = self.insert(sql)
                    if it[0] == False:
                        self.traceproc('数据库写入错误'+"\t"+str(it[1]))
                    upsec = 1  # 控制行，字典中有多少元素，就显示刷多少行
                    for i in data:
                        if i.get(self.confs[m][n]['cont_var']):
                            sql = 'insert into contents(cont_id,cont_text,update_sec,update_date) values(' + str(n) + ',"' + i.get(
                                self.confs[m][n]['cont_var']) + '",' + str(upsec) + ',"' + strftime("%Y-%m-%d %H:%M:%S", localtime(time())) + '")'
                            # print(sql)
                            print(self.insert(sql))
                            upsec = upsec + 1
                except Exception as err:
                    self.traceproc(
                        'URL解析错误'+"\t"+str(self.confs[m][n]['cont_url'])+':'+str(err))
        print('此次刷新已完成')
# 变动位置
if __name__ == "__main__":
    flag = 1
    while flag < 100:
        obj = Monitor(
            confsfile='/home/cph/jsons/cont_conf.json',
            statusfile='/home/cph/jsons/conf_status.json',
            logfile='/home/cph/jsons/logfile.log')
        obj.handle()
        sleep(300)
        if flag > 80:
            flag = flag - 1
        else:
            flag = flag + 1