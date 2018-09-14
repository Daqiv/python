<?php
/**
 * 微信公众号内容抓取 - itchat [ 暂定用于公司媒体公众号 ]
 * Created by PhpStorm.
 * User: ZK018
 * Date: 2018/7/23
 * Time: 19:23
 */
class ZkWechatCollectUsersubItchat extends ZkWechatCollect
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getWxFeedBurn(){
        //print_r("\n".$this->db_feed_burn."\n");

//        $db = db_mysql_conn($this->db_feed_burn);
//        $db->where('biz', trim($this->biz));
//        $db->where('stat',0);
//        $db->limit(1);
//        $query = $db->get($this->db_feed_burn);
//        $arrCollectJob = $query->result_array();
        //查找 wx_feed_burn里面biz对应的采集配置
        $db = db_mysql_conn(ZK_MYSQL_TB_WX_FEED_BURN);
        $db->where('biz', trim($this->biz));
        $query = $db->get(ZK_MYSQL_TB_WX_FEED_BURN);
        $arrCollectJob = $query->result_array();
        return $arrCollectJob;
    }

    /**
     * 抓取处理
     */
    public function main(){
        //print_r($this->biz);
        print_r($this->article_list);

        if(!empty($this->biz)){
            if(FILE_PUT_CONTENTS_DEBUG==1){
                file_put_contents(dirname(__FILE__).'/'.date('Y-m-d')."_test.log",var_export($this->article_list,true));
                file_put_contents(dirname(__FILE__).'/'.date('Y-m-d')."_list_log.log", '');
                file_put_contents(dirname(__FILE__).'/'.date('Y-m-d').'_check.log', '');
            }

            //查找 wx_feed_burn里面biz对应的采集配置
            $arrCollectJob = self::getWxFeedBurn();
            //print_r($arrCollectJob);
            $articleCount = 0;
            if ($arrCollectJob) {
                $source_name = isset($arrCollectJob[0]) ? $arrCollectJob[0]['name'] : '';
                $url = isset($arrCollectJob[0]) ? $arrCollectJob[0]['biz'] : '';
            }

            /**
             * Array
             * (
             * [0] => Array
             * (
             * [title] => u6d4bu8bd52
             * [pub_time] => 1533610288
             * [url] => http://mp.weixin.qq.com/s?__biz=MzU5MjY4NjQ4Ng==&tempkey=OTY4X1Z5d2tsUW1qMW9IcWhWdEFwUVBla1FCaHc1TV9PUVZLTXZrZXgxRXVTTHpCMGtYNXNJd0VYZ0owdWRGZzR6bWxkM1JCdGp2d1NoQVBCR0g2NDMtQUFENk5RX29wa0F3N2ZqRWVHQ2p0aEJLUm5uSURUd1BqaE81TWpFdmxpaGZRQzJ4alQxZ1BRM2JKS0JUa2l5X0ROWDJ6b2dOODNoMkhNM1pTOGd%2Bfg%3D%3D&chksm=7e1abdfe496d34e86bd25bdd6b833f48f28f9b7d3e106e5956b245920bb60e87b9777213191e&scene=0&previewkey=W3B0ngVZXIOngjf2iCjtmMwqSljwj2bfCUaCyDofEow%253D#rd
             * [cover] => http://mmbiz.qpic.cn/mmbiz_jpg/VbxPtsmq7g9YB6xSTrtdh3EexNFQTlniaaI0wvBvSArkcf9IzFwibI1kFXoOlEBUHeqRF2xscicdNYRNVfkO63mKQ/640?wxtype=jpeg&wxfrom=0|0|0
             * [digest] => u6211u4eecu7684u554au554au554a
             * )
             * )
             */
            foreach ($this->article_list as $oArticle) {
                //如果没有url就跳过
                if (empty($oArticle['url'])) {
                    continue;
                }
                //转url
                $articleUrl = self::formatWeixinArticleUrl($oArticle['url']);
                //itchat的连接做一下转换(scene=0#rd)=>(scene=27)
                $articleUrl = str_replace("scene=0#rd","scene=27",$articleUrl);

                // 入库数据
                $data = array(
                    'title' => filter_title((string)$oArticle['title']),
                    'content' => content_filter('',(string)$oArticle['digest']),
                    'url' => (string)$articleUrl,
                    'url_md5'=>md5((string)$articleUrl),
                    'stat' => 0,//重新抓取内容
                    'addtime' => (int)$oArticle['pub_time'],
                    'insert_time' => (int)time(),
                    'come_from' => $this->come_from,
                    'source' => $this->biz,
                    'list_pic'=>array('url'=>$oArticle['cover']),
                    'biz' => $this->biz,
                );

                //遍历采集配置
                foreach ($arrCollectJob as $oJob) {
                    $content_update = false;
                    $articleData = self::dealInsertData($data,$oJob);//数据处理
                    if(FILE_PUT_CONTENTS_DEBUG==1){
                        file_put_contents(dirname(__FILE__).'/'.date('Y-m-d').'_guoqiang.log', var_export($articleData,true));
                    }

                    if(FILE_PUT_CONTENTS_DEBUG==1) {
                        file_put_contents(dirname(__FILE__).'/'.date('Y-m-d') . "_check.log", $oJob['app_id'] . '-' . $oJob['article_be_enabled'] . '-' . $articleData['title'] . '-' . $oJob['name'] . '-' . $articleData['unable'] . '=' . $articleData['stat'] . "\n", FILE_APPEND);
                        file_put_contents(dirname(__FILE__).'/'.date('Y-m-d') . '_list_log.log', var_export($articleData, true), FILE_APPEND);
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
                        } catch (Exception $e) {

                        }
                    }

                    //修改last_sync_time和content_update
                    $updateData = array('last_sync_time' => date("Y-m-d H:i:s"));
                    if ($content_update) {
                        $updateData['content_update'] = date("Y-m-d H:i:s");

                        //by lairongming at 2018-07-16 记录到analyse
                        if($this->db_feed_burn_analyse){
                            $db = db_mysql_conn($this->db_feed_burn_analyse);
                            $db->insert($this->db_feed_burn_analyse,array( 'uwfb_id'=>$oJob['id'], 'content_update'=>time(), 'history_time'=> (int)$oArticle['comm_msg_info']['datetime'] ));
                        }
                        //更新到频道表last_sync_time

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
                    $db = db_mysql_conn($this->db_feed_burn);
                    $db->where('id', $oJob['id']);
                    $db->update($this->db_feed_burn, $updateData);
                }

            }


            //监控统计
            $logger = new Logger('WechatCollect');

            if($articleCount>0){
                $logger->addDebug('WechatCollectItchat_YES', array("come_from"=>"weixin_itchat","biz"=>$this->biz,"num"=>$articleCount,"time"=>date('Y-m-d H:i:s'),'ext'=>$oJob));
            }else{
                $logger->addDebug('WechatCollectItchat_NO', array("come_from"=>"weixin_itchat","biz"=>$this->biz,"num"=>$articleCount,"time"=>date('Y-m-d H:i:s'),'ext'=>$oJob));
            }

            echo $articleCount.' rows';

        }else{
            echo "None";
        }

    }

}