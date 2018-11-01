require "TSLib"

--新版微信(6.6.7)(轮询)

local nowTime = os.date("*t",os.time()) --返回一个 table

--循环2880次点击tab重起 每8小时
local runNum = 0
local maxNum = 2880  

function gobackIntoHistory()
	tap(40,98,50)
	mSleep(1000)
	tap(82,1068,50)

	--重置次数
	runNum = 0
end


function clickCancel()
	tap(213,1196,50)
end

repeat
	
	--无法打开网页 有鸟
	if (isColor(  40,   96, 0x181818, 85) and 
		isColor(  50,  108, 0x181818, 85) and 
		isColor( 355,  103, 0xf2f2f2, 85) and 
		isColor( 663,   98, 0x000000, 85) and 
		isColor( 248,  541, 0xd5dbe0, 85) and 
		isColor( 430,  520, 0x626262, 85) and 
		isColor( 473,  534, 0xfbbd00, 85) and 
		isColor( 472,  547, 0xfbbd00, 85) and 
		isColor( 373, 1205, 0xadadad, 85) and 
		isColor( 356, 1267, 0xffffff, 85)) then
		gobackIntoHistory()
	end
	
	--已停止访问该网页 - 用户投诉
	if (isColor(  39,   97, 0x181818, 85) and 
		isColor(  50,  109, 0x181818, 85) and 
		isColor( 318,  109, 0xf2f2f2, 85) and 
		isColor( 649,   99, 0x000000, 85) and 
		isColor( 677,   99, 0x000000, 85) and 
		isColor( 358,  274, 0xf2f2f2, 85) and 
		isColor( 361,  345, 0xf2f2f2, 85) and 
		isColor( 358,  397, 0xf76260, 85) and 
		isColor( 446,  324, 0xf76260, 85) and 
		isColor( 245, 1257, 0xf2f2f2, 85)) then
		gobackIntoHistory()
	end
	
	--页面无法打开  -  感叹号
	if (isColor(  40,   97, 0x181818, 85) and 
		isColor(  52,  109, 0x181818, 85) and 
		isColor( 284,  104, 0xf2f2f2, 85) and 
		isColor( 664,   98, 0x000000, 85) and 
		isColor( 678,   99, 0x000000, 85) and 
		isColor( 359,  239, 0xf76260, 85) and 
		isColor( 360,  307, 0xf2f2f2, 85) and 
		isColor( 364,  366, 0xf2f2f2, 85) and 
		isColor( 356,  411, 0xf76260, 85) and 
		isColor( 306, 1256, 0xf2f2f2, 85)) then
		gobackIntoHistory()
	end
	
	--弹出IE web
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
		clickCancel()
	end
	
	--验证
	if (isColor(  38,   97, 0x181818, 85) and 
		isColor(  50,  107, 0x181818, 85) and 
		isColor(  85,   82, 0x888888, 85) and 
		isColor(  84,  103, 0x898989, 85) and 
		isColor( 106,  110, 0x494949, 85) and 
		isColor( 121,   85, 0x353535, 85) and 
		isColor( 142,   96, 0x353535, 85) and 
		isColor( 342,  109, 0xf2f2f2, 85) and 
		isColor( 662,   98, 0x000000, 85) and 
		isColor( 286, 1250, 0xe1e0de, 85)) then
		gobackIntoHistory()
	end
	
	
	--页面无法打开
		--已停止访问该网页
	if false then
		gobackIntoHistory()
	end
	
	--页面无法打开
	if false then
		gobackIntoHistory()
	end
	
	--此账号已自主注销，内容无法查看
	if false then
		gobackIntoHistory()
	end
	
	--设置运行了n次，关闭重新打开
	if (isColor(  29,   84, 0xf2f2f2, 85) and 
		isColor(  39,   97, 0x181818, 85) and 
		isColor(  40,   89, 0xf2f2f2, 85) and 
		isColor(  51,  109, 0x181818, 85) and 
		isColor(  50,   86, 0x181818, 85) and 
		isColor( 204,   96, 0xf2f2f2, 85) and 
		isColor( 649,   98, 0x000000, 85) and 
		isColor( 662,   99, 0x000000, 85) and 
		isColor( 677,   98, 0x000000, 85) and 
		isColor( 702,   77, 0xf2f2f2, 85)) and  runNum>=maxNum then
		--dialog("fuck11"..runNum)
		gobackIntoHistory()
		initLog(logfile, 0)                 --初始化日志 test.log，把 0 换成 1 即生成形似 test_1397679553.log 的日志文件 
		logfile = "wechat_url_"..nowTime.day
		wLog(logfile, "reset now."..runNum)
	else
		runNum = runNum + 1
		--initLog(logfile, 0)                 --初始化日志 test.log，把 0 换成 1 即生成形似 test_1397679553.log 的日志文件 
		--logfile = "wechat_url_"..nowTime.day
		--wLog(logfile, " now."..runNum)
	end
	
	
	mSleep(10000)
until (false)