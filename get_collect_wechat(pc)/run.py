#coding=utf-8
#description:此脚本用于检测新的订阅号(订阅表newly_check字段)，然后将新的订阅链接提交到微信浏览器进行读取
#url:(监测URL)
#version:2.7.15
#author:lairongming
#time:2018-08-23
#pip install pyautogui
import time
import pyautogui
import os
import httplib
import logging
import random
import datetime
from Logger import Logger
import sys
reload(sys)
sys.setdefaultencoding('utf8')
#以上三行代码放处理中文的代码前面

#################################################################################################
#logging.basicConfig(level=logging.DEBUG,format='%(asctime)s %(filename)s[line:%(lineno)d] %(levelname)s %(message)s',datefmt='%a, %d %b %Y %H:%M:%S', filename='myapp.log',filemode='w')
#定义一个StreamHandler，将INFO级别或更高的日志信息打印到标准错误，并将其添加到当前的日志处理对象#
#console = logging.StreamHandler()
#console.setLevel(logging.INFO)
#formatter = logging.Formatter('%(name)-12s: %(levelname)-8s %(message)s')
#console.setFormatter(formatter)
#logging.getLogger('').addHandler(console)
#################################################################################################


#日志工具
logger = Logger('log/all.log',level='debug').logger


wechat = {}

wechat['window_x'] = 600#微信聊天窗口打字位置  x坐标
wechat['window_y'] = 600#微信聊天窗口打字位置  y坐标

wechat['content_x'] = 680#微信URL  x坐标
wechat['content_y'] = 445#微信URL  y坐标

wechat['window_web_x'] = 816#微信浏览器关闭位置  x坐标
wechat['window_web_y'] = 12#微信聊天窗口打字位置  y坐标

URL = ""
URL_IP = "shell.myzaker.com";
ANALYSE_USE_TIME_ARR = [] #模拟记录每次的处理时间（传入1：表示有处理，传入3：表示没处理）
ANALYSE_NUM_LEN = 2#记录x次
ANALYSE_ARR_SUM = 4#x次总和与此常量对比
ANALYSE_WAIT_TIME = 3
ANALYSE_DEAL_TIME = 1
WAIT_TIME = 30

logger.info(wechat)
print "pyton-collect-newsub is running, please goto see log."


#获取新关注的url
def getUrl():
	conn = httplib.HTTPConnection(URL_IP)
	conn.request("GET",URL)
	response = conn.getresponse()
	headers = response.getheaders()
	status = response.status
	res = response.read()
	log = '[http status:' + str(status) + ']'#记录请求返回的状态
	if res == '0':
		return False,log
	elif status == 200:
		return res,log
	else:
		return False,log

#判断是否过快操作
def analyseUseTime(t):
	flag = False #标记是否需要暂停，防止过快操作
	#计算ANALYSE_NUM_LEN次总时间
	if len(ANALYSE_USE_TIME_ARR) + 1 >= ANALYSE_NUM_LEN:
		arr_sum = sum(ANALYSE_USE_TIME_ARR)
		if arr_sum <= ANALYSE_ARR_SUM:#ANALYSE_NUM_LEN次总时间小于ANALYSE_ARR_SUM则说明过快操作
			flag = True
		del ANALYSE_USE_TIME_ARR[:]#清空
	else:
		ANALYSE_USE_TIME_ARR.append(t)
		logger.info(ANALYSE_USE_TIME_ARR)
	return flag

#获取跳转时间
def getRedirectTime(times=1):
	now = datetime.datetime.now()
	hour = now.hour
	res = 40
	if hour < 7:
		res = 150
	if hour >= 7 and hour < 11:
		res = 55
	if hour >= 11 and hour < 18:
		res = 150
	if hour > 20:
		res = 150
	return res * times
	
#主函数
def main():
	while (True):
		#监测链接
		result_url,log = getUrl()
		if result_url == False:
			#没新订阅则等待3秒
			logger.info('Rest 3 second.'+log)
			time.sleep(3)
			analyseUseTime(ANALYSE_WAIT_TIME)#记录分析
		elif 'mp.weixin.qq.com' in result_url:
			#打开微信
			logger.info('Will deal:'+result_url+log)
			os.system("C:\Progra~2\Tencent\WeChat\WeChat.exe")
			pyautogui.moveTo(wechat['window_x'], wechat['window_y'], 1, pyautogui.easeInQuad)
			pyautogui.mouseDown(wechat['window_x'], wechat['window_y'], 'left')
			pyautogui.mouseUp(wechat['window_x'], wechat['window_y'], 'left')
			#复制发送
			pyautogui.typewrite(result_url)
			pyautogui.press('enter')
			time.sleep(1)
			#移动到url位置并点击
			pyautogui.moveTo(wechat['content_x'],wechat['content_y'], 1, pyautogui.easeInQuad)
			pyautogui.mouseDown(wechat['content_x'],wechat['content_y'], 'left')
			pyautogui.mouseUp(wechat['content_x'],wechat['content_y'], 'left')
			time.sleep(15)
			#关闭
			pyautogui.moveTo(wechat['window_web_x'],wechat['window_web_y'], 1, pyautogui.easeInQuad)
			pyautogui.mouseDown(wechat['window_web_x'],wechat['window_web_y'], 'left')
			pyautogui.mouseUp(wechat['window_web_x'],wechat['window_web_y'], 'left')
			time.sleep(5)
			#if analyseUseTime(ANALYSE_DEAL_TIME):#记录分析,如处理过快则休息x秒
			#	wt = random.randint(5,20)*10
			#	logger.info('Deal too fast. rest ' + str(wt) + ' second.'+log)
			#	time.sleep(wt)
			wt = getRedirectTime(2.5)
			logger.info('Deal too fast. rest ' + str(wt) + ' second.'+log)
			time.sleep( wt )
		else:
			#没新订阅则等待3秒
			logger.info('Rest 3 second.'+log)
			time.sleep(3)

if __name__ == '__main__':
	main()
