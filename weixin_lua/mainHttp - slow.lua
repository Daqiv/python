require "TSLib"
--旧版(6.5.19版本)慢速度(稳定)

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
	if (isColor(   3,   38, 0x303135, 85) and 
		isColor(  62,  129, 0x737373, 85) and 
		isColor(  76,  106, 0x737373, 85) and 
		isColor(  80,  134, 0x737373, 85) and 
		isColor( 651,  119, 0xf7f7f7, 85) and 
		isColor( 637,  112, 0x737373, 85) and 
		isColor( 350,   74, 0xf7f7f7, 85) and 
		isColor(  82,   67, 0x828282, 85) and 
		isColor( 714,   98, 0x393a3f, 85) and 
		isColor( 666,  129, 0x737373, 85)) then
		--dialog("fuck11")
		return true
	else
		--dialog("fuck22")
		return false
	end
end

--关闭公众号页面tab
function closeTab()
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  61,   85, 0xffffff, 85) and 
		isColor(  60,  110, 0xffffff, 85) and 
		isColor(  96,   80, 0x2e2e32, 85) and 
		isColor( 672,  113, 0xffffff, 85) and 
		isColor( 672,   82, 0xffffff, 85) and 
		isColor( 114,   97, 0x393a3f, 85) and 
		isColor( 143,   79, 0x393a3f, 85) and 
		isColor( 143,  118, 0x393a3f, 85) and 
		isColor( 679,   97, 0x393a3f, 85)) then
			--关闭tab页面
			touchDown(45,97)
			mSleep(30)
			touchUp(45,97)
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
	if (isColor(  48,   84, 0xffffff, 85) and 
	isColor(  62,   98, 0xffffff, 85) and 
	isColor(  96,   78, 0x2e2e32, 85) and 
	isColor( 319,   85, 0xffffff, 85) and 
	isColor( 320,  106, 0x393a3f, 85) and 
	isColor( 687,  112, 0xffffff, 85) and 
	isColor( 668,   90, 0x393a3f, 85) and 
	isColor( 364,  606, 0xe9e9e9, 85) and 
	isColor( 394,  740, 0xe5e5e5, 85) and 
	isColor( 339, 1261, 0xffffff, 85)) then
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

--关闭微信聊天窗口+ (6.5.19版本)
function closeExt()
	-- body
	if (isColor(  37,   97, 0xffffff, 85) and 
	isColor( 329,   83, 0xffffff, 85) and 
	isColor( 351,  101, 0x393a3f, 85) and 
	isColor( 673,   83, 0xffffff, 85) and 
	isColor( 604,  905, 0xfbfbfb, 85) and 
	isColor( 604,  931, 0x7c8186, 85) and 
	isColor( 572,  780, 0x6f7378, 85) and 
	isColor( 439, 1087, 0x7c8186, 85) and 
	isColor( 421, 1147, 0xfbfbfb, 85) and 
	isColor( 103,  920, 0x7c8186, 85)) then
		--dialog("fuckExt")
		touchDown(666,681)
		mSleep(30)
		touchUp(666,681)
		mSleep(1000)
		return true
	else
		return false
	end
end

--关闭微信聊天窗口+ (6.5.19版本，不知为啥同一机器会有不同的尺寸)
function closeExt2()

	if (isColor(  47,   85, 0xffffff, 85) and 
	isColor( 314, 1252, 0xf4f4f4, 85) and 
	isColor(  96,   77, 0x2e2e32, 85) and 
	isColor( 331,   82, 0xffffff, 85) and 
	isColor( 673,   84, 0xffffff, 85) and 
	isColor( 536,  124, 0x393a3f, 85) and 
	isColor( 606,  846, 0xfbfbfb, 85) and 
	isColor( 604,  872, 0x7c8186, 85) and 
	isColor( 440, 1045, 0x7c8186, 85) and 
	isColor( 118, 1046, 0x7c8186, 85)) then
		--dialog("fuckExt")
		touchDown(666,600)
		mSleep(30)
		touchUp(666,681)
		mSleep(1000)
		return true
	else
		return false
	end
end


repeat
	
	if  closeSound() == false and  closeTab() == false and closeFav() == false and closeWeb() == false and closeExt() == false and closeExt2() == false then
		
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