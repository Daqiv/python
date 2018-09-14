/**
 * author: brittyu
 * date: 2017/5/3 17:44
 * email: yu.britt@gmail.com
 */

var http = require("http");
var querystring = require('querystring');

var msgExp = /var msgList = \'.*?\'/;

function postMsgList(msgList, serverResData, callback) {
  var strMsgList = JSON.stringify(msgList);
  var postData = {
    list: strMsgList
  };
  var postData = querystring.stringify(postData);
  var postOption = {
    host: "121.9.213.58",
    post: "80",
    path: '/shell.myzaker.com/zaker/collect_wechat_run_usersub_pc.php',
    method: "POST",
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Content-Length': Buffer.byteLength(postData),
    }
  };

  var postReq = http.request(postOption, function(res) {
    res.setEncoding("utf8");
    res.on("data", function(chunk) {
        callback(chunk + serverResData);
    });
  });
  postReq.on('error', function(err) {
    callback('<script>setTimeout(function(){location.reload()}, 60000);</script>');
  });

  postReq.write(postData);
  postReq.end();
}

function parseParam(url, name) {
  var arrUrl = url.split('?');
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");

  if (arrUrl.length <= 1) {
    return null;
  }

  var r = arrUrl[1].match(reg);

  if (r != null) {
    return unescape(r[2]);
  }

  return null;
}

module.exports = {
  replaceRequestOption: function(req, options) {
    // 过滤高通gps辅助服务器请求
    if (options.hostname.indexOf('izatcloud') != -1) {
      options.headers = {}
    }
  },

  replaceServerResDataAsync: function(req,res,serverResData,callback){
    if (req.headers.host.indexOf("izatcloud") != -1) {
      var newDataStr = "hello";
      callback(newDataStr);
    }
    if (req.headers.host == 'mp.weixin.qq.com') {
      var biz = parseParam(req.url, '__biz');
      var version = parseParam(req.url, 'version');
      var cookie = req.headers.cookie;
      var wechatKey = req.headers['x-wechat-key'];
      var wechatUin = req.headers['x-wechat-uin'];
      var passTicket = parseParam(req.url, 'pass_ticket');

      if (!biz || !version || !passTicket) {
        callback(serverResData);
        return null;
      }

      if (res.statusCode === 302) {
        callback(serverResData);
        return null;
      }
      var matchArr = msgExp.exec(serverResData.toString());
      if (! matchArr) {
        callback(serverResData);
        return null;
      }

      var strMsgList = matchArr[0];
      strMsgList = strMsgList.replace('var msgList = ', '');
      strMsgList = strMsgList.substring(0, strMsgList.length-1);
      strMsgList = strMsgList.substring(1, strMsgList.length);
      strMsgList = strMsgList.replace(/&quot;/g, "\"");
      var msgListResult = JSON.parse(strMsgList);
      msgListResult['biz'] = biz;

      postMsgList(msgListResult, serverResData, callback);

    } else {
      callback(serverResData);
    }
  }
};
