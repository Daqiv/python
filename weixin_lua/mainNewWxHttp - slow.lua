require "TSLib"
--新版(6.6.7)慢速度(稳定)

os.execute("settings put secure default_input_method com.touchsprite.android/.core.TSInputMethod")-- 切换到触动输入法

local appid = 'com.tencent.mm'

local nowTime = os.date("*t",os.time()) --返回一个 table

--local httpUrl = "xxx/collect_wechat.php?web=1"
local httpUrl = "xxx/collect_wechat_run_usersub_pc.php?web=1"

--获取等待时间
function getRedirectTime()
	-- body
	hour = nowTime.hour
	res = 40
	if hour < 7 then
		res = 150
	end
	if hour >= 7 and hour < 11 then
		res = 55
	end
	if hour >= 11 and hour < 18 then
		res = 150
	end
	if hour > 20 then
		res = 150
	end
	return res * 1000
end

--刚开始音量tab
function closeSound()
	if (isColor(  50,  120, 0xcfcfcf, 85) and 
		isColor(  68,  104, 0xcfcfcf, 85) and 
		isColor(  71,  115, 0xf7f7f7, 85) and 
		isColor(  68,  139, 0xcfcfcf, 85) and 
		isColor( 652,  120, 0xf7f7f7, 85) and 
		isColor( 652,  104, 0x737373, 85) and 
		isColor( 651,  137, 0x737373, 85) and 
		isColor( 157,   72, 0xf7f7f7, 85) and 
		isColor( 161,   69, 0x878787, 85) and 
		isColor( 199,   81, 0x828282, 85)) then
		--dialog("fuck11")
		return true
	else
		--dialog("fuck22")
		return false
	end
end

--关闭公众号页面tab
function closeTab()
	if (isColor(  28,   85, 0x181818, 85) and 
		isColor(  39,   90, 0xf2f2f2, 85) and 
		isColor(  40,   98, 0x181818, 85) and 
		isColor(  52,   86, 0x181818, 85) and 
		isColor( 334,  106, 0xf2f2f2, 85) and 
		isColor( 648,   98, 0x000000, 85) and 
		isColor( 678,   98, 0x000000, 85) and 
		isColor( 709,  132, 0xf2f2f2, 85) and 
		isColor(  51,  109, 0x181818, 85) and 
		isColor(  25,   97, 0xf2f2f2, 85)) then
			--关闭tab页面
			touchDown(38,97)
			mSleep(30)
			touchUp(38,97)
			mSleep(1000)
			--dialog("fuck1")
		return true
		
	else
		--dialog("fuck2")
		return false
	end
end

--关闭收藏页面
function closeFav()
	if (isColor(  31,   97, 0x181818, 85) and 
		isColor(  45,   83, 0x181818, 85) and 
		isColor( 266,   81, 0x777777, 85) and 
		isColor( 272,  106, 0xf2f2f2, 85) and 
		isColor( 661,   93, 0xf2f2f2, 85) and 
		isColor( 679,  112, 0x000000, 85) and 
		isColor( 674,   86, 0x2e2e2e, 85) and 
		isColor( 366,  603, 0xe9e9e9, 85) and 
		isColor( 359,  668, 0xffffff, 85) and 
		isColor( 382,  755, 0xe5e5e5, 85)) then
		--关闭tab页面
		touchDown(45,97)
		mSleep(30)
		touchUp(45,97)
		mSleep(1000)
		--dialog("fuckFav")
		return true
	else
		return false
	end
end

--关闭浏览器提示
function closeWeb()
	--if ( isColor(76, 1068, 0x00afec) and  isColor(454,970,0x00afec) and isColor( 59,879,0x323232) )   then
	if (isColor(  76, 1074, 0x00afec, 85) and 
		isColor(  89, 1096, 0x00afec, 85) and 
		isColor( 298, 1202, 0xffffff, 85) and 
		isColor( 614, 1202, 0xffffff, 85) and 
		isColor(  29,   79, 0x023542, 85) and 
		isColor( 697,   79, 0x094a4c, 85) and 
		isColor( 318,  494, 0x5c6364, 85) and 
		isColor( 687,  430, 0x09494c, 85) and 
		isColor(  39,  651, 0x023442, 85) and 
		isColor( 445,  970, 0x00afec, 85)) then
			mSleep(2000)
			tap(213,1196,50)
			mSleep(2000)
		return true
	else
		return false
	end
end

--关闭微信聊天窗口+ (6.6.7版本)
function closeExt()
	-- body
	if (isColor(  43,   84, 0xebebeb, 85) and 
		isColor(  38,   96, 0x303030, 85) and 
		isColor( 258,   86, 0xffffff, 85) and 
		isColor( 452,   98, 0x303030, 85) and 
		isColor( 650,   98, 0xffffff, 85) and 
		isColor( 677,   98, 0xffffff, 85) and 
		isColor( 556,  697, 0x6f7378, 85) and 
		isColor( 567,  716, 0x6f7378, 85) and 
		isColor( 439, 1053, 0x7c8186, 85) and 
		isColor( 442, 1088, 0xfbfbfb, 85)) then
		--dialog("fuckExt")
		touchDown(666,592)
		mSleep(30)
		touchUp(666,592)
		mSleep(1000)
		return true
	else
		return false
	end
end



repeat
	
	if  closeSound() == false and  closeTab() == false and closeFav() == false and closeWeb() == false and closeExt() == false then
		
		--日志文件
		logfile = "wechat_url_"..nowTime.day

		--启动微信程序
		r = runApp(appid)
		if r ~= 0 then
			lua_restart() --重新加载脚本

		else
			initLog(logfile, 0)                 --初始化日志 test.log，把 0 换成 1 即生成形似 test_1397679553.log 的日志文件 
		--	wLog("test","[DATE] Test_1 OK!!!"); --写入日志，日志内容：当前时间 Test_1 OK!!!
		--	mSleep(500);                        --间隔时间 500 毫秒
		--	wLog("test",type(webdata));
		--	closeLog("test");                   --关闭日志
		
			--获取URL
			webdata = httpPost(httpUrl,'t='..os.time())
			
			mSleep(3000)
			--dialog(webdata)
			if webdata == false or webdata == nil then
				wLog(logfile,'not deal.')
				wLog(logfile,webdata)
				find_a = false
			else
				find_a,find_b = string.find(webdata,'mp.weixin.qq.com')
			end
			
			if find_a ~= false then
				--记录日志
				wLog(logfile,webdata)
				mSleep(1000)
				--点击聊天窗口 400,182
				tap(310,1250,50)
				-- 脚本启动,获取输入法包名
				ipm = getInPutMethod()  
				-- 切换到触动输入法
				os.execute("settings put secure default_input_method com.touchsprite.android/.core.TSInputMethod")
				--switchTSInputMethod(true)   -- 切换到触动/帮你玩输入法
				--输入文字
				mSleep(50)
				inputText(webdata)
				mSleep(2000)
				--点击发送
				touchDown(660,1250)
				mSleep(30)
				touchUp(660,1250)
				mSleep(1000)
				
				--点击链接
				touchDown(370,1111)
				mSleep(30)
				touchUp(370,1111)
				mSleep(8000)
				
				--随机等待
				mSleep(getRedirectTime()*3)
				
			else
				--获取不到连接 等待10秒
				mSleep(10000)
			end
			
		end

	end
	
	mSleep(3000)
until (false)



--function gobackIntoHistory()
--	tap(50,120,50)
--	mSleep(1000)
--	tap(346,1096,50)
--end


--function clickCancel()
--	tap(213,1196,50)
--end


--repeat
--	if (isColor(278,  221, 0xffffff, 100) and isColor(307,  212, 0xffffff, 100) and isColor(312,  224, 0xffffff, 100)) then
--		gobackIntoHistory()
--	end
	
--	if (isColor(267,  310, 0xf76260) and isColor(453,  328, 0xf76260) and isColor(361,  316, 0xffffff) and isColor(358,  416, 0xf76260) and isColor(354,  229, 0xf76260)) then
--		gobackIntoHistory()
--	end
	
--	if (isColor(165,  162, 0xe1e0de) and isColor(289,  163, 0xe1e0de) and isColor(470,  160, 0xe1e0de) and isColor(482,  539, 0xe1e0de) and isColor(693,  170, 0xe1e0de)) then
--		gobackIntoHistory()
--	end
	
--	if (isColor(76, 1068, 0x00afec)) then
--		clickCancel()
--	end
	
--	mSleep(10000)
--until (false)