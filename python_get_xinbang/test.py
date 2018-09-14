# -*- coding: utf-8 -*-
import time

def getMySql():
    pass

def send(name,gzh):
    # SQL 插入语句
    sql = """INSERT INTO zk_wechat_xinbang(name,
             gzh, addtime)
             VALUES ('"""+name+"""', '"""+gzh+"""', """+ str(int(time.time())) +""")"""
    print (sql)

#send("aa", "bbb")

category = {"时事","民生", "财富", "科技", "创业", "汽车", "楼市", "职场", "教育", "学术", "政务", "企业", "文化", "百科", "健康", "时尚", "美食", "乐活", "旅行", "幽默", "情感", "体娱", "美体", "文摘",}

for item in category:
    print item
    time.sleep(5)