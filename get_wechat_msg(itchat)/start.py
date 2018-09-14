import  itchat
from itchat.content import *
import time
import urllib
import urllib.request
import urllib.parse
import json
from xml.dom import minidom
import sys


try:
	import xml.etree.cElementTree as ET
except ImportError:
	import xml.etree.ElementTree as ET


if len(sys.argv) >=2:
	wechat_user = sys.argv[1]
else:
	wechat_user = input('请输入微信账号：')

print('Hello',wechat_user)
	
msg_information = {}

#msg_xml

# <msg>
  # <appmsg appid="" sdkver="0">
    # <title><![CDATA[测试内容]]></title>
    # <des><![CDATA[测试的内容]]></des>
    # <action/>
    # <type>5</type>
    # <showtype>1</showtype>
    # <soundtype>0</soundtype>
    # <content><![CDATA[]]></content>
    # <contentattr>0</contentattr>
    # <url><![CDATA[http://mp.weixin.qq.com/s?__biz=MzU5MjY4NjQ4Ng==&mid=100000002&idx=1&sn=41fc5d5863d4099be3d311048b3aac6d&chksm=7e1abdfb496d34ed2204990f743a203452a445e2a5ff7a4d80c3c3cff4a2f96b022ecff0c1a9&scene=0&previewkey=W3B0ngVZXIOngjf2iCjtmMwqSljwj2bfCUaCyDofEow%25253D#rd]]></url>
    # <lowurl><![CDATA[]]></lowurl>
    # <appattach>
      # <totallen>0</totallen>
      # <attachid/>
      # <fileext/>
      # <cdnthumburl><![CDATA[]]></cdnthumburl>
      # <cdnthumbaeskey><![CDATA[]]></cdnthumbaeskey>
      # <aeskey><![CDATA[]]></aeskey>
    # </appattach>
    # <extinfo/>
    # <sourceusername/>
    # <sourcedisplayname><![CDATA[]]></sourcedisplayname>
    # <mmreader>
      # <category type="20" count="3">
        # <name><![CDATA[ming123jew]]></name>
        # <topnew>
          # <cover><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaaI0wvBvSArkcf9IzFwibI1kFXoOlEBUHeqRF2xscicdNYRNVfkO63mKQ/640?wxtype=jpeg&wxfrom=0]]></cover>
          # <width>0</width>
          # <height>0</height>
          # <digest><![CDATA[]]></digest>
        # </topnew>
        # <item>
          # <itemshowtype>0</itemshowtype>
          # <title><![CDATA[测试内容]]></title>
          # <url><![CDATA[http://mp.weixin.qq.com/s?__biz=MzU5MjY4NjQ4Ng==&tempkey=OTY4X3p1NS8wckx6amlveC9rb3NwUVBla1FCaHc1TV9PUVZLTXZrZXgxRXVTTHpCMGtYNXNJd0VYZ0owdWRGcTJYZS1UNkt6VHN1ekNPUzNnc1huUnUwckhmc3ZjN1Bpb3ZzNkpyZURndjdzNHNneDBZdVJLTkpWWFQ1NXprWkExd293Y0N6eVpLWjBXaWhhM2tBWm51bXIzY1RHaDV4Q0F3MG1xNmZ3WFF%2Bfg%3D%3D&chksm=7e1abdfb496d34ed2204990f743a203452a445e2a5ff7a4d80c3c3cff4a2f96b022ecff0c1a9&scene=0&previewkey=W3B0ngVZXIOngjf2iCjtmMwqSljwj2bfCUaCyDofEow%253D#rd]]></url>
          # <shorturl><![CDATA[]]></shorturl>
          # <longurl><![CDATA[]]></longurl>
          # <pub_time>1533537500</pub_time>
          # <cover><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaaI0wvBvSArkcf9IzFwibI1kFXoOlEBUHeqRF2xscicdNYRNVfkO63mKQ/640?wxtype=jpeg&wxfrom=0|0|0]]></cover>
          # <tweetid/>
          # <digest><![CDATA[测试的内容]]></digest>
          # <fileid>100000003</fileid>
          # <sources>
            # <source>
              # <name><![CDATA[ming123jew]]></name>
            # </source>
          # </sources>
          # <styles/>
          # <native_url/>
          # <del_flag>0</del_flag>
          # <contentattr>0</contentattr>
          # <play_length>0</play_length>
          # <play_url><![CDATA[]]></play_url>
          # <player><![CDATA[]]></player>
          # <template_op_type>0</template_op_type>
          # <weapp_username><![CDATA[]]></weapp_username>
          # <weapp_path><![CDATA[]]></weapp_path>
          # <weapp_version>0</weapp_version>
          # <weapp_state>0</weapp_state>
          # <music_source>0</music_source>
          # <pic_num>0</pic_num>
          # <show_complaint_button>0</show_complaint_button>
        # </item>
        # <item>
          # <itemshowtype>0</itemshowtype>
          # <title><![CDATA[获取单篇文章]]></title>
          # <url><![CDATA[http://mp.weixin.qq.com/s?__biz=MzU5MjY4NjQ4Ng==&tempkey=OTY4X0Z1ay9FNGNhQXBuQy9heXJwUVBla1FCaHc1TV9PUVZLTXZrZXgxRXVTTHpCMGtYNXNJd0VYZ0owdWRIaHRPSFN0MXEzUkJ0QjM2SlJxQkY4QTd3VmtJU0NTb01sNGh0Z2JlT1VCOHV6YjB6ZHR5NEt2bFhBN2JJWl9DMnYwc05zRG9nQjg3eGVWeld3MnV0VG5SRVkwV0JHZHFlZ0JmLU9SMk5xNlF%2Bfg%3D%3D&chksm=7e1abdfb496d34edba216fd014cd8243c936f7dac4262679b95050f7de974ff539758bf631fe&scene=0&previewkey=W3B0ngVZXIOngjf2iCjtmMwqSljwj2bfCUaCyDofEow%253D#rd]]></url>
          # <shorturl><![CDATA[]]></shorturl>
          # <longurl><![CDATA[]]></longurl>
          # <pub_time>1533537500</pub_time>
          # <cover><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaiccdDchic18JJc8RkribLYCpLoCZ0Ch4t7ZlVYgVhzO42o4vLibfCrp55A/300?wxtype=jpeg&wxfrom=0|0|0]]></cover>
          # <tweetid/>
          # <digest><![CDATA[我们的明天]]></digest>
          # <fileid>100000004</fileid>
          # <sources>
            # <source>
              # <name><![CDATA[ming123jew]]></name>
            # </source>
          # </sources>
          # <styles/>
          # <native_url/>
          # <del_flag>0</del_flag>
          # <contentattr>0</contentattr>
          # <play_length>0</play_length>
          # <play_url><![CDATA[]]></play_url>
          # <player><![CDATA[]]></player>
          # <template_op_type>0</template_op_type>
          # <weapp_username><![CDATA[]]></weapp_username>
          # <weapp_path><![CDATA[]]></weapp_path>
          # <weapp_version>0</weapp_version>
          # <weapp_state>0</weapp_state>
          # <music_source>0</music_source>
          # <pic_num>0</pic_num>
          # <show_complaint_button>0</show_complaint_button>
        # </item>
        # <item>
          # <itemshowtype>0</itemshowtype>
          # <title><![CDATA[获取多篇文章]]></title>
          # <url><![CDATA[http://mp.weixin.qq.com/s?__biz=MzU5MjY4NjQ4Ng==&tempkey=OTY4XzMvbk5GazhjWmZBSHBKdWRwUVBla1FCaHc1TV9PUVZLTXZrZXgxRXVTTHpCMGtYNXNJd0VYZ0owdWRGbmRTMEctRWtmb3dUZlZtTlpKY2tLOW12STg2OW56Z2dMUFV2X2J4VzE1akNqYWdQeDA2MUw0eWZGRE1HRVdFd2c0Zmt3QVNQWldNQU52dTByVzJ1NWhZcVFCdlZ0bWh6R0N2ZTVjY2s1YlF%2Bfg%3D%3D&chksm=7e1abdfb496d34ed975f9ab3c7e9c960f9f10f227e0b91aa2a12312b39b00807e53326ddc4d0&scene=0&previewkey=W3B0ngVZXIOngjf2iCjtmMwqSljwj2bfCUaCyDofEow%253D#rd]]></url>
          # <shorturl><![CDATA[]]></shorturl>
          # <longurl><![CDATA[]]></longurl>
          # <pub_time>1533537500</pub_time>
          # <cover><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaiccdDchic18JJc8RkribLYCpLoCZ0Ch4t7ZlVYgVhzO42o4vLibfCrp55A/300?wxtype=jpeg&wxfrom=0|0|0]]></cover>
          # <tweetid/>
          # <digest><![CDATA[我的天]]></digest>
          # <fileid>100000005</fileid>
          # <sources>
            # <source>
              # <name><![CDATA[ming123jew]]></name>
            # </source>
          # </sources>
          # <styles/>
          # <native_url/>
          # <del_flag>0</del_flag>
          # <contentattr>0</contentattr>
          # <play_length>0</play_length>
          # <play_url><![CDATA[]]></play_url>
          # <player><![CDATA[]]></player>
          # <template_op_type>0</template_op_type>
          # <weapp_username><![CDATA[]]></weapp_username>
          # <weapp_path><![CDATA[]]></weapp_path>
          # <weapp_version>0</weapp_version>
          # <weapp_state>0</weapp_state>
          # <music_source>0</music_source>
          # <pic_num>0</pic_num>
          # <show_complaint_button>0</show_complaint_button>
        # </item>
      # </category>
      # <publisher>
        # <username/>
        # <nickname><![CDATA[ming123jew]]></nickname>
      # </publisher>
      # <template_header/>
      # <template_detail/>
      # <forbid_forward>0</forbid_forward>
    # </mmreader>
    # <thumburl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaaI0wvBvSArkcf9IzFwibI1kFXoOlEBUHeqRF2xscicdNYRNVfkO63mKQ/640?wxtype=jpeg&wxfrom=0]]></thumburl>
  # </appmsg>
  # <fromusername/>
  # <appinfo>
    # <version>0</version>
    # <appname><![CDATA[ming123jew]]></appname>
    # <isforceupdate>1</isforceupdate>
  # </appinfo>
# </msg>
#内容解析器
def xml_filer(msg_xml):
	result = {}
	print('Now will deal msg_xml..')
	tree = minidom.parseString(msg_xml)
	# 重新生成 xml 字符串
	xml_string = tree.toxml()
	print(xml_string)
	root = ET.fromstring(xml_string)# 获取根节点 <Element 'data' at 0x02BF6A80>
	#tree = ET.parse(msg_xml)  # <class 'xml.etree.ElementTree.ElementTree'>
	#root = tree.getroot()
	print(root)
	#print(root.tag, ":", root.attrib)  # 打印根元素的tag和属性

	# 遍历xml文档的第二层
	#for child in root:
		# 第二层节点的标签名称和属性
		#print(child.tag,":", child.attrib) 
		# 遍历xml文档的第三层
		#for children in child:
			# 第三层节点的标签名称和属性
			#print(children.tag, ":", children.attrib)

	#url
	#result['url'] = root[0][8].text
	result['url'] = ''
	for i,item in enumerate(root.iter('appmsg'),0):
		if item.find('url').text != '':
			result['url'] = item.find('url').text
	
	#biz	
	result['biz'] = ''
	if result['url'] != '':
		tmp = urllib.parse.urlparse(result['url'])
		#print(tmp.query)
		tmp_qs = urllib.parse.parse_qs(tmp.query)  # 结果转换成字典
		if '__biz' in tmp_qs.keys() and tmp_qs['__biz']:
			result['biz'] = tmp_qs['__biz'][0]
	
	#data
	result['data'] = {}
	
	#过滤非mp.weixin.qq.com连接
	if 'mp.weixin.qq.com' in result['url']:
		# 过滤出所有item标签,存入到字典
		for i,item in enumerate(root.iter("item"),0):
			result['data'].update(
				{
					i:{
						"title":item.find('title').text,
						"put_time":item.find('pub_time').text,
						"url":item.find('url').text,
						"cover":item.find('cover').text,
						"digest":item.find('digest').text,
					},
				}
			)

		#print(item.tag, ":", item.attrib, item.find('title').text, item.find('put_time').text, item.find('url').text)
		return result
	else:
		return False
	
#发送器(将捕获到的内容发送到对应api处理)
def	send(data):
	#post接收地址
	url = 'http://121.9.213.58/shell.myzaker.com/zaker/collect_wechat_run_usersub_itchat.php'
	#准备一下头
	headers = {
		'User-Agent': 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
	}
	#还有我们准备用Post传的值，这里值用字典的形式
	values = {}
	#将字典格式化成能用的形式
	#data = urllib.parse.urlencode(data).encode('utf-8')
	data = json.dumps(data).encode('utf-8')
	try:
		#创建一个request,放入我们的地址、数据、头
		request = urllib.request.Request(url, data, headers)
		#访问
		html = urllib.request.urlopen(request).read().decode('utf-8')
		#利用json解析包解析返回的json数据 拿到翻译结果
		print(html)
	except urllib.error.HTTPError as e:
		print(e)
	
	
#捕获器(注册SHARING类型监听)
@itchat.msg_register([SHARING],isMpChat=True)
def handle_receive_msg(msg):
	msg_time_rec = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())   #接受消息的时间
	if msg['MsgType'] == 49:
		msg_time = msg['CreateTime']    #信息发送的时间
		msg_id = msg['MsgId']    #每条信息的id
		msg_from = itchat.search_mps(userName=msg['FromUserName'])['NickName']   #在公众号列表中查询发送信息的昵称
		msg_content = msg['Text']
		msg_xml = msg['Content']         #记录xml内容
		msg_share_url = msg['Url']       #记录分享/文章的url
		#print(msg_xml)
		#print(msg_from)

    #将信息存储在字典中，每一个msg_id对应一条信息
	msg_information.update(
			{
				msg_id: {
				"msg_from": msg_from, 
				"msg_time": msg_time,
				"msg_time_rec": msg_time_rec,
				"msg_type": msg["Type"],
				"msg_content": msg_content,
				"msg_share_url": msg_share_url,
				"msg_xml": msg_xml,
			}
		}
	)
	if msg_xml != '':
		content = xml_filer(msg_xml)
	
	if content != False:
		send(content)


#单开
if wechat_user != '':
	wechat_user = wechat_user + '.pkl'
else:
	wechat_user = 'wechat_login.pkl'
itchat.auto_login(hotReload=True, statusStorageDir=wechat_user)
itchat.run(True)

#多开
#newInstance = itchat.new_instance()
#newInstance.auto_login(hotReload=True, statusStorageDir='newInstance.pkl')

