#!/usr/bin/python3.5
# -*- coding: utf-8 -*-
# @Date    : 2016-09-22 11:44:12
# @Author  : 陈鹏欢 (cphchenpenghuan@gmail.com)
# @Link    : http://chenpenghuan.github.io
# @Version : 3.5


from json import loads, dumps
from os import path
from sys import argv
from time import time, localtime, strftime

from pymysql import connect, cursors
from redis import Redis


class formatdata(object):
    def __init__(self, ip, port, password, subcha, url, logfile, dbhost, dbuser, dbpass, dbname, dbport, dbcharset,
                 confsfile):
        self.ip = ip
        self.port = port
        self.password = password
        self.subcha = subcha
        self.conn = Redis(host=self.ip, port=self.port, password=self.password)
        self.url = url
        self.logfile = logfile
        self.dbhost = dbhost
        self.dbuser = dbuser
        self.dbpass = dbpass
        self.dbname = dbname
        self.dbport = dbport
        self.dbcharset = dbcharset
        self.confsfile = confsfile

    def writelog(self, content):
        logfile = open(self.logfile, 'a')
        logfile.write(strftime("%Y-%m-%d %H:%M:%S", localtime(time())) + "\t" + str(content) + "\r\n")
        logfile.close()

    def readdata(self, sql):
        conn = connect(
            host=self.dbhost,
            user=self.dbuser,
            password=self.dbpass,
            db=self.dbname,
            port=self.dbport,
            charset=self.dbcharset,
            cursorclass=cursors.DictCursor)
        cur = conn.cursor()
        count = cur.execute(sql)
        warn_cols_conf = cur.fetchall()
        cur.close()
        conn.close()
        return warn_cols_conf

    def main(self):
        sub = self.conn.pubsub()
        sub.subscribe(self.subcha)
        contstrs = []
        for msg in sub.listen():
            if msg['type'] == 'message':  # 这里开始插件各自的自定义操作
                # print('被调用')
                try:
                    if path.isfile(self.confsfile):
                        confsfile = open(self.confsfile)
                        confs = confsfile.read()
                        confsfile.close()
                    if confs != '':
                        warn_cols_conf = loads(confs)
                    else:
                        warn_cols_conf = self.readdata(
                            'select warn_colid,warn_type,warn_value,warn_logic,warn_center_id from warn_cols_conf')  # 查询出所有的已配置的统计报警信息

                        # 把查询出来的所有的已配置的统计报警信息写入配置文件以备下次直接调用。如果前端操作使数据库中的配置发生变化，则配置文件中的内容被清空，下次调用时重新刷入
                        confsfile = open(self.confsfile, 'w')
                        confsfile.write(dumps(warn_cols_conf))
                        confsfile.close()
                    data_collected = self.readdata(
                        'select contents.id,contents.cont_id,contents.cont_text,cont_conf.cont_title from contents,cont_conf where isshow=1 and contents.cont_id=cont_conf.id')
                    for line in data_collected:
                        for col in warn_cols_conf:
                            if line.get('cont_id') == col.get('warn_colid'):
                                if int(col['warn_logic']) == 1:  # 按大于匹配
                                    if line.get('cont_text').isdigit() and col.get('warn_value').isdigit():
                                        if int(line.get('cont_text')) > int(col.get('warn_value')):
                                            conts = {'id': int(col.get('warn_center_id')), 'params': 'default',
                                                     'title': str(col.get('warn_type')),
                                                     'content': '字段”' + str(line.get('cont_title')) + '“（id为' + str(
                                                         line.get('cont_id')) + '）的值为' + line.get(
                                                         'cont_text') + ',超出报警上限' + str(col.get('warn_value'))}
                                            contstr = dumps(conts, ensure_ascii=False)
                                            contstrs.append(contstr)
                                if int(col['warn_logic']) == 2:  # 按小于匹配
                                    if line.get('cont_text').isdigit() and col.get('warn_value').isdigit():
                                        if int(line.get('cont_text')) < int(col.get('warn_value')):
                                            conts = {'id': int(col.get('warn_center_id')), 'params': 'default',
                                                     'title': str(col.get('warn_type')),
                                                     'content': '字段”' + str(line.get('cont_title')) + '“（id为' + str(
                                                         line.get('cont_id')) + '）的值为' + line.get(
                                                         'cont_text') + ',超出报警下限' + str(col.get('warn_value'))}
                                            contstr = dumps(conts, ensure_ascii=False)
                                            contstrs.append(contstr)
                                if int(col['warn_logic']) == 3:  # 按包含匹配
                                    contains = col.get('warn_value').split(',')
                                    for cts in contains:
                                        if cts in line.get('cont_text'):
                                            conts = {'id': int(col.get('warn_center_id')), 'params': 'default',
                                                     'title': str(col.get('warn_type')),
                                                     'content': '字段”' + str(line.get('cont_title')) + '“（id为' + str(
                                                         line.get('cont_id')) + '）的值为' + line.get(
                                                         'cont_text') + ',包含报警字符串' + str(col.get('warn_value'))}
                                            contstr = dumps(conts, ensure_ascii=False)
                                            contstrs.append(contstr)
                    contstrs = list(set(contstrs))
                    for contstr in contstrs:
                        self.conn.publish('warn_center', contstr)
                    result = True
                except Exception as err:
                    result = err
                    # print(result)
                finally:
                    if result != True:
                        self.writelog(argv[0] + "\t" + str(result))


if __name__ == "__main__":
    subcha = ['warn_collect']  # 监听频道
    obj = formatdata('192.168.1.154', 6379, '123123', subcha, 'http://localhost/warn_test.php',
                     '/home/cph/jsons/plugin1.log', '192.168.1.154', 'root', '123123', 'winnerlook', 3306, 'utf8',
                     '/home/cph/jsons/warn_cols_config.json')
    # obj = formatdata('127.0.0.1', 6379, '123123', subcha,'http://localhost/wrn_test.php','/home/cph/jsons/plugin1.log')
    obj.main()
