require "TSLib"

--旧版(6.5.19版本)(轮询)

local nowTime = os.date("*t",os.time()) --返回一个 table

--循环2880次点击tab重起 每8小时
local runNum = 0
local maxNum = 2880  

function gobackIntoHistory()
	tap(50,120,50)
	mSleep(1000)
	tap(346,1096,50)
	--重置次数
	runNum = 0
end


function clickCancel()
	tap(213,1196,50)
end


repeat
	
	--计时 公众号上条栏
	if (isColor(  48,   98, 0xffffff, 85) and 
		isColor(  34,   84, 0xffffff, 85) and 
		isColor(  46,   83, 0x393a3f, 85) and 
		isColor(  96,   77, 0x2e2e32, 85) and 
		isColor(  96,  115, 0x2e2e32, 85) and 
		isColor( 363,  106, 0x393a3f, 85) and 
		isColor( 672,   81, 0xffffff, 85) and 
		isColor( 671,  111, 0xffffff, 85) and 
		isColor( 706,  130, 0x393a3f, 85) and 
		isColor(  36,  108, 0xffffff, 85)) then
		--
		
	end
	
	--验证页面
	if (isColor(278,  221, 0xffffff, 100) and isColor(307,  212, 0xffffff, 100) and isColor(312,  224, 0xffffff, 100)) then
		--gobackIntoHistory()
	end
	
	--无法打开网页 有鸟
	if (isColor(267,  310, 0xf76260) and isColor(453,  328, 0xf76260) and isColor(361,  316, 0xffffff) and isColor(358,  416, 0xf76260) and isColor(354,  229, 0xf76260)) then
		gobackIntoHistory()
	end
	
	
	--页面无法打开  感叹号
	if (isColor(165,  162, 0xe1e0de) and isColor(289,  163, 0xe1e0de) and isColor(470,  160, 0xe1e0de) and isColor(482,  539, 0xe1e0de) and isColor(693,  170, 0xe1e0de)) then
		--gobackIntoHistory()
	end
	
	--web
	
	--上述为旧逻辑，暂时屏蔽 使用新的
	
	--无法打开网页 有鸟
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  59,   87, 0xffffff, 85) and 
		isColor(  96,   79, 0x2e2e32, 85) and 
		isColor( 672,   96, 0xffffff, 85) and 
		isColor( 471,  534, 0xfbbd00, 85) and 
		isColor( 280,  541, 0xd5dbe0, 85) and 
		isColor( 273,  629, 0x242424, 85) and 
		isColor( 453,  540, 0xffffff, 85) and 
		isColor( 374, 1205, 0xadadad, 85) and 
		isColor( 346, 1257, 0xffffff, 85)) then
		gobackIntoHistory()
	end
	
	--无法打开网页  有鸟2
	if (isColor(  48,   98, 0xffffff, 85) and 
		isColor(  60,  110, 0xffffff, 85) and 
		isColor(  96,   82, 0x2e2e32, 85) and 
		isColor( 122,   89, 0x393a3f, 85) and 
		isColor( 664,   98, 0xffffff, 85) and 
		isColor( 248,  543, 0xd5dbe0, 85) and 
		isColor( 457,  532, 0x626262, 85) and 
		isColor( 472,  533, 0xfbbd00, 85) and 
		isColor( 474,  548, 0xfbbd00, 85) and 
		isColor( 309, 1218, 0xaeaeae, 85)) then
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
	
	--此账号已申请账号迁移
	if (isColor(  48,   97, 0xffffff, 85) and 
		isColor( 364,  104, 0x393a3f, 85) and 
		isColor( 672,   98, 0xffffff, 85) and 
		isColor( 308,  308, 0xf76260, 85) and 
		isColor( 360,  295, 0xffffff, 85) and 
		isColor( 424,  306, 0xf76260, 85) and 
		isColor( 356,  392, 0xf76260, 85) and 
		isColor( 184,  492, 0x000000, 85) and 
		isColor( 454,  596, 0x999999, 85) and 
		isColor( 366, 1248, 0xffffff, 85)) then
		gobackIntoHistory()
	end
	
	--已停止访问该网页 - 用户投诉
	if (isColor(  47,   96, 0xffffff, 85) and 
		isColor(  60,   84, 0xffffff, 85) and 
		isColor(  96,   80, 0x2e2e32, 85) and 
		isColor( 360,  102, 0x393a3f, 85) and 
		isColor( 672,  112, 0xffffff, 85) and 
		isColor( 359,  278, 0xffffff, 85) and 
		isColor( 360,  345, 0xffffff, 85) and 
		isColor( 305,  311, 0xf76260, 85) and 
		isColor( 434,  314, 0xf76260, 85) and 
		isColor( 206,  480, 0x000000, 85)) then
		gobackIntoHistory()
	end
	
	--页面无法打开
		--已停止访问该网页
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  96,   80, 0x2e2e32, 85) and 
		isColor( 317,   96, 0x393a3f, 85) and 
		isColor( 664,   97, 0xffffff, 85) and 
		isColor( 299,  304, 0xf76260, 85) and 
		isColor( 360,  278, 0xffffff, 85) and 
		isColor( 412,  321, 0xf76260, 85) and 
		isColor( 360,  379, 0xf76260, 85) and 
		isColor( 327, 1249, 0xffffff, 85) and 
		isColor( 260,  502, 0x000000, 85)) then
		gobackIntoHistory()
	end
	
	--页面无法打开
	if (isColor(  48,   98, 0xffffff, 85) and 
		isColor(  59,  110, 0xffffff, 85) and 
		isColor(  96,   90, 0x2e2e32, 85) and 
		isColor( 380,  108, 0x393a3f, 85) and 
		isColor( 672,  112, 0xffffff, 85) and 
		isColor( 359,  283, 0xffffff, 85) and 
		isColor( 360,  362, 0xffffff, 85) and 
		isColor( 418,  252, 0xf76260, 85) and 
		isColor( 327,  404, 0xf76260, 85) and 
		isColor( 259,  502, 0x000000, 85)) then
		gobackIntoHistory()
	end
	
	--此账号已自主注销，内容无法查看
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  61,  111, 0xffffff, 85) and 
		isColor(  96,   92, 0x2e2e32, 85) and 
		isColor( 352,  116, 0x393a3f, 85) and 
		isColor( 672,  112, 0xffffff, 85) and 
		isColor( 360,  283, 0xffffff, 85) and 
		isColor( 362,  362, 0xffffff, 85) and 
		isColor( 422,  260, 0xf76260, 85) and 
		isColor( 374,  406, 0xf76260, 85) and 
		isColor(  84,  503, 0x000000, 85)) then
		gobackIntoHistory()
	end
	
	--验证
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  35,   86, 0xffffff, 85) and 
		isColor(  60,   85, 0xffffff, 85) and 
		isColor(  36,  109, 0xffffff, 85) and 
		isColor(  60,  109, 0xffffff, 85) and 
		isColor(  96,   78, 0x2e2e32, 85) and 
		isColor( 145,   85, 0xffffff, 85) and 
		isColor( 186,  111, 0xffffff, 85) and 
		isColor( 672,   97, 0xffffff, 85) and 
		isColor( 582,  102, 0x393a3f, 85)) then
		gobackIntoHistory()
	end
	
	--验证
	if (isColor(  47,   98, 0xffffff, 85) and 
		isColor(  60,  110, 0xffffff, 85) and 
		isColor( 127,  106, 0xffffff, 85) and 
		isColor(  96,   91, 0x2e2e32, 85) and 
		isColor( 165,   85, 0xffffff, 85) and 
		isColor( 185,   98, 0xffffff, 85) and 
		isColor( 190,  112, 0xfbfbfb, 85) and 
		isColor( 664,   97, 0xffffff, 85) and 
		isColor( 491,   96, 0x393a3f, 85) and 
		isColor( 279, 1261, 0xe1e0de, 85)) then
		gobackIntoHistory()
	end
	
	--设置运行了n秒，关闭重新打开   警报上面的条条 （被注释的为警报条条）  和  列表上面的条条位置有差异
	--if (isColor(  36,   85, 0xffffff, 85) and 
	--	isColor(  49,  109, 0x393a3f, 85) and 
	--	isColor(  48,   99, 0xffffff, 85) and 
	--	isColor(  61,   85, 0xffffff, 85) and 
	--	isColor(  96,   81, 0x2e2e32, 85) and 
	--	isColor(  96,  110, 0x2e2e32, 85) and 
	--	isColor( 234,  103, 0x393a3f, 85) and 
	--	isColor( 501,  104, 0x393a3f, 85) and 
	--	isColor( 671,   82, 0xffffff, 85) and 
	--	isColor( 672,  112, 0xffffff, 85)) then and runNum>=maxNum 
	if (isColor(  35,   83, 0xffffff, 85) and 
		isColor(  48,  106, 0x393a3f, 85) and 
		isColor(  48,   97, 0xffffff, 85) and 
		isColor(  60,   85, 0xffffff, 85) and 
		isColor(  96,   80, 0x2e2e32, 85) and 
		isColor(  96,  108, 0x2e2e32, 85) and 
		isColor( 383,   98, 0x393a3f, 85) and 
		isColor( 664,   82, 0xffffff, 85) and 
		isColor( 664,  111, 0xffffff, 85) and 
		isColor( 704,  102, 0x393a3f, 85))  and runNum>=maxNum  then
		--dialog("fuck11"..runNum)
		gobackIntoHistory()
		initLog(logfile, 0)                 --初始化日志 test.log，把 0 换成 1 即生成形似 test_1397679553.log 的日志文件 
		logfile = "wechat_url_"..nowTime.day
		wLog(logfile, "reset now."..runNum)
	else
		runNum = runNum + 1
		--initLog(logfile, 0)                 --初始化日志 test.log，把 0 换成 1 即生成形似 test_1397679553.log 的日志文件 
		--logfile = "wechat_url_"..nowTime.day
		--wLog(logfile, "reset now."..runNum)
		--dialog(runNum)
	end
	
	mSleep(10000)
until (false)