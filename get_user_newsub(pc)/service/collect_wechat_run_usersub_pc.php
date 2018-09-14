<?php
/**
 * 微信抓取入口文件 - 用户订阅微信公众号 第二版  pc抓取专用
 */
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");

define('ZK_CORE_DEBUG',0);
define('USER_WX_FEED_BURN', 'poly.user_wx_feed_burn');
define('USER_WX_FEED_BURN_ANALYSE', 'poly.user_wx_feed_burn_analyse');

//加载库依赖
require_once(dirname(__FILE__) . "/../../aa_core/zk_init.php");
load_helper('article_collect');
load_helper('article');

//加载抓取类
require_once(dirname(__FILE__) ."/wechat_collect/ZkWechatCollect.php");
require_once(dirname(__FILE__) ."/wechat_collect/ZkWechatCollectUsersubPc.php");

//设置记录check.log list_log.log
define('FILE_PUT_CONTENTS_DEBUG',0);
//优先处理新增和当前时间段涉及的公众号
define('USE_FAST_MODEL',1);

@set_time_limit(30);


if(false){
    define('ZK_MONGO_TB_ARTICLE_WECHAT', '20.aa.article_wechat');
    define('ZK_MONGO_TB_ARTICLE_TEST', '35.aa.article');
    global $ZK_mongoDB_config;
    $ZK_mongoDB_config[ZK_MONGO_TB_ARTICLE_WECHAT] = array (
        'host' => ZK_MONGO_SERVER_20,
        'read_host' => ZK_MONGO_SERVER_20,
        'db' => 'aa',
        'collection' => 'article_wechat'
    );
    $ZK_mongoDB_config[ZK_MONGO_TB_ARTICLE_TEST] = array (
        'host' => ZK_MONGO_SERVER_35,
        'read_host' => ZK_MONGO_TB_35_READ_HOST,
        'db' => 'aa',
        'collection' => 'article'
    );
}

//20测试专用
//涉及数据表 poly.user_wx_feed_burn  poly.user_wx_feed_burn_analyse
if(false){

    define('ZK_MYSQL_TB_WECHAT_FEED_BURN', 'poly.wechat_feed_burn'); //总表biz唯一
    define('ZK_MYSQL_TB_USER_WX_FEED_BURN', 'poly.user_wx_feed_burn');//用户与小程序 总表 ,以后合并可以只使用ZK_MYSQL_TB_WECHAT_FEED_BURN表 biz唯一

    define('ZK_MYSQL_TB_USER_WX_FEED_BURN_ANALYSE', 'poly.user_wx_feed_burn_analyse');//分析表
    define('ZK_MYSQL_TB_WX_FEED_BURN', 'poly.wx_feed_burn');//默认订阅表
    define('ZK_MYSQL_TB_WECHAT_APPS_SUB', 'poly.wechat_apps_sub');//小程序订阅阅表
    define('ZK_MYSQL_TB_WECHAT_APPS', 'poly.wechat_apps');//用户订阅

    global $ZK_mysql_config;

    //用户与小程序 总表
    $ZK_mysql_config[ZK_MYSQL_TB_USER_WX_FEED_BURN] = array(
        'host' => ZK_MYSQL_SERVER_20,
        'read_host' => ZK_MYSQL_SERVER_20,
    );

    //默认订阅表
    $ZK_mysql_config[ZK_MYSQL_TB_WX_FEED_BURN] = array(
        'host' => ZK_MYSQL_SERVER_20,
        'read_host' => ZK_MYSQL_SERVER_20,
    );

    //分析表
    $ZK_mysql_config[ZK_MYSQL_TB_USER_WX_FEED_BURN_ANALYSE] = array(
        'host' => ZK_MYSQL_SERVER_20,
        'read_host' => ZK_MYSQL_SERVER_20,
    );

    //小程序订阅
    $ZK_mysql_config[ZK_MYSQL_TB_WECHAT_APPS_SUB] = array(
        'host' => ZK_MYSQL_SERVER_20,
        'read_host' => ZK_MYSQL_SERVER_20,
    );

    //用户订阅
    $ZK_mysql_config[ZK_MYSQL_TB_WECHAT_APPS] = array(
        'host' => ZK_MYSQL_SERVER_20,
        'read_host' => ZK_MYSQL_SERVER_20,
    );
}

//print_r($ZK_mysql_config[ZK_MYSQL_TB_USER_WX_FEED_BURN]);
//print_r($ZK_mysql_config[ZK_MYSQL_TB_WX_FEED_BURN]);
//print_r($ZK_mysql_config[ZK_MYSQL_TB_USER_WX_FEED_BURN_ANALYSE]);
//print_r($ZK_mysql_config[ZK_MYSQL_TB_WECHAT_APPS_SUB]);
//print_r($ZK_mysql_config[ZK_MYSQL_TB_WECHAT_APPS]);
//
//exit;

$obj = new ZkWechatCollectUsersubPc();
$obj->come_from = 'weixin_pc';
$obj->db_feed_burn = ZK_MYSQL_TB_USER_WX_FEED_BURN;
$obj->db_feed_burn_analyse = ZK_MYSQL_TB_USER_WX_FEED_BURN_ANALYSE;
$obj->db_wechat_apps_sub = ZK_MYSQL_TB_WECHAT_APPS_SUB;//小程序订阅表
$obj->db_wechat_apps = ZK_MYSQL_TB_WECHAT_APPS;//用户订阅表
$obj->db_wechat_apps_default = ZK_MYSQL_TB_WX_FEED_BURN;//默认订阅表
$obj->main();


