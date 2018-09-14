# -*- coding: utf-8 -*-
# 获取新榜热门微信
from sys import version_info
if version_info.major == 2 and version_info.minor == 7:
    import sys
    reload(sys)
    sys.setdefaultencoding('utf8')

import time

# 使用Selenium和PhantomJS模拟登录
from selenium import webdriver
from selenium.webdriver.common.keys import Keys

from selenium.common.exceptions import TimeoutException
#引入ActionChains鼠标操作类
from selenium.webdriver.common.action_chains import ActionChains

import MySQLdb

# 打开数据库连接
DB = MySQLdb.connect("127.0.0.1", "root", "123456", "wechat_xinbang", charset='utf8')

# 获取数据库
def getMySql():
    # 使用cursor()方法获取操作游标
    cursor = DB.cursor()
    return DB,cursor


# 新榜url
url = 'https://www.newrank.cn/public/info/list.html?period=month&type=data'

# 订阅接口
url_sub = ''

# 滚到底部
def scroll(driver,timeout=1000):
    driver.execute_script(""" 
        (function () { 
            var y = document.body.scrollTop; 
            var step = 100; 
            window.scroll(0, y); 
            function f() { 
                if (y < document.body.scrollHeight) { 
                    y += step; 
                    window.scroll(0, y); 
                    setTimeout(f, 50); 
                }
                else { 
                    window.scroll(0, y); 
                    document.title += "scroll-done"; 
                } 
            } 
            setTimeout(f, """+str(timeout)+"""); 
        })(); 
        """)

def scroll2(driver):
    # 将页面滚动条拖到底部
    js = "setTimeout('var q=document.documentElement.scrollTop=100000;', 1000)"
    driver.execute_script(js)
    time.sleep(2)

    # 将滚动条移动到页面的顶部
    js = "var q=document.documentElement.scrollTop=0"
    driver.execute_script(js)
    time.sleep(1)


# 插入数据库
def send(name,gzh,category=''):
    db,cursor = getMySql()
    # SQL 插入语句
    if category != '':
        sql = """INSERT INTO zk_wechat_xinbang(name,
                     gzh, addtime,category)
                     VALUES ('""" + name + """', '""" + gzh + """', """ + str(int(time.time())) + """,'"""+category+"""')"""
    else:
        sql = """INSERT INTO zk_wechat_xinbang(name,
             gzh, addtime)
             VALUES ('"""+name+"""', '"""+gzh+"""', """+ str( int(time.time()) ) +""")"""
    try:
        time.sleep(0.05)
        print("now:",gzh)
        print name
        # 执行sql语句
        cursor.execute(sql)
        # 提交到数据库执行
        db.commit()
    except:
        # Rollback in case there is any error
        db.rollback()

    # 关闭数据库连接
    # db.close()
    # print (name,gzh)

def week(driver):
    # 预留点击
    category = {"时事", "民生", "财富", "科技", "创业", "汽车", "楼市", "职场", "教育", "学术", "政务", "企业", "文化", "百科", "健康", "时尚", "美食", "乐活", "旅行", "幽默", "情感", "体娱", "美体", "文摘"}
    #category = {"民生", "财富", "科技"}
    for item in category:
        # print item
        driver.find_element_by_xpath("//a[@data=\""+item+"\"]").click()
        time.sleep(1)
        scroll2(driver)
        time.sleep(1)
        # 解析拿 公众号名+ID
        for item_sub in driver.find_element_by_class_name('wx_main').find_elements_by_class_name("account-div"):
            try:
                name = item_sub.find_element_by_class_name('copyright_title').text
                gzh = item_sub.find_element_by_tag_name('p').find_element_by_tag_name('a').text
                if name != '' and gzh != '':
                    send(name, gzh, item)
            except:
                print "fuck"+item_sub
        time.sleep(5)


def main():
    option = webdriver.ChromeOptions()
    option.add_argument('headless')  # 加载浏览器的静默模式
    driver = webdriver.Chrome(chrome_options=option)
    driver.get(url)

    # 模拟点击登录
    # driver.find_element_by_xpath("//input[@class='input-submit login-btn']").click()
    # 总榜
    # driver.find_element_by_id("wx_month_all").click()
    # 周榜
    driver.find_element_by_id("week-bang").click()
    # time.sleep(3)

    # 滚动到底部
    # scroll(driver)
    time.sleep(1)

    # # 解析拿 公众号名+ID
    # for item in driver.find_element_by_class_name('wx_main').find_elements_by_class_name("account-div"):
    #     name = item.find_element_by_class_name('copyright_title').text
    #     gzh = item.find_element_by_tag_name('p').find_element_by_tag_name('a').text
    #     if name != '' and gzh != '':
    #         send(name, gzh)

    week(driver)





if __name__ == '__main__':
    main()
    DB.close()
