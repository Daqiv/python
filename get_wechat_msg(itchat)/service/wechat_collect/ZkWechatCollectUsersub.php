<?php

/**
 * 微信公众号内容抓取 - 用户订阅
 * Created by PhpStorm.
 * User: ZK018
 * Date: 2018/7/23
 * Time: 19:23
 */
class ZkWechatCollectUsersub extends ZkWechatCollect
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 抓取处理
     */
    public function main(){

        if(!empty($this->biz)){

            if(FILE_PUT_CONTENTS_DEBUG==1){
                file_put_contents(date('Y-m-d')."_list_log.log", '');
                file_put_contents(date('Y-m-d').'_check.log', '');
            }

            //查找 wx_feed_burn里面biz对应的采集配置
            $arrCollectJob = self::getWxFeedBurn();
            $articleCount = 0;
            $content_update = false;
            if ($arrCollectJob) {
                $source_name = isset($arrCollectJob[0]) ? $arrCollectJob[0]['name'] : '';
                $url = isset($arrCollectJob[0]) ? $arrCollectJob[0]['biz'] : '';
            }

            foreach ($this->article_list as $oArticle) {
                /**
                 * Array
                 * (
                 * [comm_msg_info] => Array
                 * (
                 * [id] => 1000000257
                 * [type] => 49
                 * [datetime] => 1493960746
                 * [fakeid] => 3097913857
                 * [status] => 2
                 * [content] =>
                 * )
                 *
                 * [app_msg_ext_info] => Array
                 * (
                 * [title] => 杨强教授漫谈《西部世界》、生成式对抗网络及迁移学习
                 * [digest] => 杨强教授分享在&nbsp;“生成式对抗网络模型“&nbsp;和迁移学习等领域的独特见解和最新思考：通过&nbsp;GAN&q;来理解《西部世界》？！
                 * [content] =>
                 * [fileid] => 505678767
                 * [content_url] => http://mp.weixin.qq.com/s?__biz=MzA5NzkxMzg1Nw==&amp;amp;mid=2653162416&amp;amp;idx=1&amp;amp;sn=abbe6015ddc07fbf6e6413079f9c9c14&amp;amp;chksm=8b4936debc3ebfc809dc70689629fa735278f5871499d3a0f5a29e3319b2ef96b0460554a973&amp;amp;scene=27#wechat_redirect
                 * [source_url] => https://mp.weixin.qq.com/s/5x6L4WRd8V7bUaL61rUwLg
                 * [cover] => http://mmbiz.qpic.cn/mmbiz_jpg/cokWkYcF4Dc0uOOOEsk8Ce2XhEy1H9RdQmHdUauxx76nx13Q3HbiaZZr9nruOru4TSflobLePckNh9u250dANtQ/0?wx_fmt=jpeg
                 * [subtype] => 9
                 * [is_multi] => 0
                 * [multi_app_msg_item_list] => Array
                 * (
                 * )
                 *
                 * [author] => 第四范式
                 * [copyright_stat] => 101
                 * )
                 *
                 * )
                 */
                //如果没有url就跳过
                if (empty($oArticle['app_msg_ext_info']['content_url'])) {
                    continue;
                }
                //转url
                $articleUrl = self::formatWeixinArticleUrl($oArticle['app_msg_ext_info']['content_url']);
                // 入库数据
                $data = array(
                    'title' => filter_title((string)$oArticle['app_msg_ext_info']['title']),
                    'content' => content_filter('',(string)$oArticle['app_msg_ext_info']['digest']),
                    'url' => (string)$articleUrl,
                    'url_md5'=>md5((string)$articleUrl),
                    'stat' => 0,//重新抓取内容
                    'addtime' => (int)$oArticle['comm_msg_info']['datetime'],
                    'insert_time' => (int)time(),
                    'come_from' => $this->come_from,
                    'source' => $this->biz,
                    'list_pic'=>array('url'=>$oArticle['app_msg_ext_info']['cover']),
                    'biz' => $this->biz,
                );

                //遍历采集配置
                foreach ($arrCollectJob as $oJob) {
                    $articleData = self::dealInsertData($data,$oJob);//数据处理
                    if(FILE_PUT_CONTENTS_DEBUG==1){
                        file_put_contents(date('Y-m-d').'_guoqiang.log', var_export($articleData,true));
                    }

                    if(FILE_PUT_CONTENTS_DEBUG==1) {
                        file_put_contents(date('Y-m-d') . "_check.log", $oJob['app_id'] . '-' . $oJob['article_be_enabled'] . '-' . $articleData['title'] . '-' . $oJob['name'] . '-' . $articleData['unable'] . '=' . $articleData['stat'] . "\n", FILE_APPEND);
                        file_put_contents(date('Y-m-d') . '_list_log.log', var_export($articleData, true), FILE_APPEND);
                    }
                    //入库
                    $mongo = db_mongoDB_conn(ZK_MONGO_TB_ARTICLE_WECHAT);
                    $row = $mongo->where(array('biz' => $this->biz, 'url_md5' => $data['url_md5']))->getOne(ZK_MONGO_TB_ARTICLE_WECHAT);

                    if (!$row) {
                        $articleCount += 1;
                        $mongo = db_mongoDB_conn(ZK_MONGO_TB_ARTICLE_WECHAT);
                        try {
                            $mongo->insert(ZK_MONGO_TB_ARTICLE_WECHAT, $articleData);
                            $content_update = true;

                            //by lairongming at 2018-07-16 记录到analyse
                            if($this->db_feed_burn_analyse){
                                $db = db_mysql_conn($this->db_feed_burn_analyse);
                                $db->insert($this->db_feed_burn_analyse,array( 'uwfb_id'=>$oJob['id'], 'content_update'=>time(), 'history_time'=> (int)$oArticle['comm_msg_info']['datetime'] ));
                            }
                            //更新到频道表last_sync_time

                        } catch (Exception $e) {

                        }
                    }
                }

                // 多图文信息进行判断
                if ($oArticle['app_msg_ext_info']['is_multi'] == 1) {
                    foreach ($oArticle['app_msg_ext_info']['multi_app_msg_item_list'] as $oInnerArticle) {
                        $innerArticleUrl = self::formatWeixinArticleUrl($oInnerArticle['content_url']);

                        // 入库数据
                        $innerData = array(
                            'title' => filter_title((string)$oInnerArticle['title']),
                            'content' => content_filter('',(string)$oInnerArticle['digest']),
                            'url' => (string)$innerArticleUrl,
                            'url_md5'=>md5((string)$innerArticleUrl),
                            'stat' => 0,//重新抓取内容
                            'addtime' => (int)$oArticle['comm_msg_info']['datetime'],
                            'insert_time' => (int)time(),
                            'come_from' => $this->come_from,
                            'source' => $this->biz,
                            'list_pic'=>array('url'=>$oInnerArticle['cover']),
                            'biz' => $this->biz,
                        );
                        foreach ($arrCollectJob as $oJob) {
                            $innerArticleData = self::dealInsertData($innerData,$oJob);//数据处理

                            if(FILE_PUT_CONTENTS_DEBUG==1) {
                                file_put_contents(date('Y-m-d') . "_check.log", $oJob['app_id'] . '-' . $oJob['article_be_enabled'] . '-' . $innerArticleData['title'] . '-' . $oJob['name'] . '-' . $innerArticleData['unable'] . '=' . $innerArticleData['stat'] . "\n", FILE_APPEND);
                                file_put_contents(date('Y-m-d') . "_list_log.log", var_export($innerArticleData, true), FILE_APPEND);
                            }
                            //入库
                            $mongo = db_mongoDB_conn(ZK_MONGO_TB_ARTICLE_WECHAT);
                            $row = $mongo->where(array('biz' => $this->biz, 'url_md5' => $innerData['url_md5']))
                                ->getOne(ZK_MONGO_TB_ARTICLE_WECHAT);

                            if (!$row) {
                                $articleCount += 1;
                                $mongo = db_mongoDB_conn(ZK_MONGO_TB_ARTICLE_WECHAT);
                                try {
                                    $mongo->insert(ZK_MONGO_TB_ARTICLE_WECHAT, $innerArticleData);
                                    $content_update = true;

                                    //by lairongming at 2018-07-16 记录到analyse   由于单条数据已分析，不再进行分析
                                    //if($this->db_feed_burn_analyse){
                                    //    $db = db_mysql_conn($this->db_feed_burn_analyse);
                                    //    $db->insert($this->db_feed_burn_analyse,array( 'uwfb_id'=>$oJob['id'], 'content_update'=>time(), 'history_time'=> (int)$oArticle['comm_msg_info']['datetime'] ));
                                    //}
                                    //更新到频道表last_sync_time

                                } catch (Exception $e) {

                                }
                            }

                        }
                    }
                }
            }

            //修改last_sync_time和content_update
            $updateData = array('last_sync_time' => date("Y-m-d H:i:s"));
            if ($content_update) {
                $updateData['content_update'] = date("Y-m-d H:i:s");

                //by lairongming at 2018-08-21 更新到 wechat_apps 和 wechat_apps_sub 和 wx_feed_burn
                //由于两边都可能存在记录  通知3边
                if($this->db_wechat_apps){
                    $db = db_mysql_conn($this->db_wechat_apps);
                    $db->set('sync_stat',1);
                    $db->where('biz',$this->biz);
                    $db->update($this->db_wechat_apps);
                }
                if($this->db_wechat_apps_sub){
                    $db = db_mysql_conn($this->db_wechat_apps_sub);
                    $db->set('sync_stat',1);
                    $db->where('biz',$this->biz);
                    $db->update($this->db_wechat_apps_sub);
                }

                if($this->db_wechat_apps_default){
                    $db = db_mysql_conn($this->db_wechat_apps_default);
                    $db->set('sync_stat',1);
                    $db->where('biz',$this->biz);
                    $db->update($this->db_wechat_apps_default);
                }

            }
            //更新last_sync_time
            $db = db_mysql_conn($this->db_feed_burn);
            $db->where('id', $oJob['id']);
            $db->update($this->db_feed_burn, $updateData);

            //监控统计
            $logger = new Logger('WechatCollect');

            if($articleCount>0){
                $logger->addDebug('WechatCollectUserSub_YES', array("come_from"=>"weixin_usersub","biz"=>$this->biz,"num"=>$articleCount,"time"=>date('Y-m-d H:i:s'),'ext'=>$oJob));
            }else{
                $logger->addDebug('WechatCollectUserSub_NO', array("come_from"=>"weixin_usersub","biz"=>$this->biz,"num"=>$articleCount,"time"=>date('Y-m-d H:i:s'),'ext'=>$oJob));
            }

        }

        //下一个处理
        if(defined('USE_FAST_MODEL') && USE_FAST_MODEL){
            list($biz,$redirect_time) = self::getLongestNoSyncBizExt($this->urgent);
            if($redirect_time==0){
                $redirect_time = self::getRedirectTime();
            }
            // $url = "http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=" . $collectJob['biz'] . "#wechat_webview_type=1&wechat_redirect";//拼接公众号历史消息url地址（第一种页面形式）
            $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=" . $biz . "&scene=124#wechat_redirect";
            echo  "<script>setTimeout(function(){window.location.href='" . $url . "';}," . $redirect_time . ");</script>";//将下一个将要跳转的$url变成js脚本，由anyproxy注入到微信页面中。

        }else{
            echo self::next();
        }

    }

}