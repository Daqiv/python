#coding=utf-8
import datetime
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

print getRedirectTime()