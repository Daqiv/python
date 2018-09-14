<?php
/**
 * 微信公众号内容抓取
 * Created by PhpStorm.
 * User: ZK018
 * Date: 2018/7/23
 * Time: 18:23
 */
class ZkWechatCollect
{

    /**
     * 传值,列表
     * @var  $_REQUEST['list']
     */
    protected $list;

    /**
     * 传值,强制抓取
     * @var  $_REQUEST['urgent']
     */
    protected $urgent;

    /**
     * @var
     */
    protected $article_list;

    /**
     * @var
     */
    protected $biz;

    /**
     * @var string
     */
    public $come_from = 'weixin';

    /**
     * 默认|用户采集规则表
     * @var string
     */
    public $db_feed_burn = ZK_MYSQL_TB_WX_FEED_BURN;

    /**
     * 分析公众号表 | 默认对用户订阅公众号进行分析
     * @var string
     */
    public $db_feed_burn_analyse = '';

    /**
     * web订阅表
     * @var string
     */
    public $db_wechat_apps_sub = '';

    /**
     * 小程序订阅表
     * @var string
     */
    public $db_wechat_apps = '';

    /**
     * 默认订阅表
     * @var string
     */
    public $db_wechat_apps_default = '';

    /**
     * ZkWechatCollect constructor.
     */
    public function __construct()
    {
        $this->list = $_REQUEST['list'];
        $this->list = stripslashes($this->list);
        $this->list = json_decode($this->list, true);

        $this->article_list = $this->list['list'];
        $this->biz = $this->list['biz'];

        $this->urgent = $_REQUEST['urgent'];

        if(FILE_PUT_CONTENTS_DEBUG==1){
            file_put_contents(get_called_class().date('Y-m-d').'_request_list.log', var_export($this->list,true));
        }
    }

    /**
     * 对链接进行格式化
     * @param $content_url
     * @return mixed|string
     */
    public function formatWeixinArticleUrl($content_url)
    {
        $content_url = stripslashes($content_url);
        $content_url = str_replace("&amp;", "&", $content_url);
        $content_url = str_replace("&amp;", "&", $content_url);
        $content_url = str_replace("#wechat_redirect", "", $content_url);

        return $content_url;
    }

    /**
     * 根据时间段进行设置抓取跳转频率
     * @return int
     */
    public function getRedirectTime()
    {
        date_default_timezone_set('Asia/Shanghai');
        $hour = (int)date('H');

        if ($hour < 7) {
            return 150000;
        }

        if ($hour >= 7 && $hour < 11) {
            return 55000;
        }

        if ($hour >= 11 && $hour < 18) {
            return 150000;
        }

        if ($hour > 20) {
            return 150000;
        }

        return 35000;
    }

    /**
     * 查找相对较久没抓取的公众号
     * @param string $urgent
     * @return mixed
     */
    public function getLongestNoSyncBiz( $urgent = '' )
    {

        if(FILE_PUT_CONTENTS_DEBUG==1) {
            $db2 = db_mysql_conn($this->db_feed_burn);
            //$db2->limit(2 );
            //$query2 = $db2->get($this->db_feed_burn);
            $sql = "select * from {$this->db_feed_burn}  order by last_sync_time asc limit 0,20 ";
            $query2 = $db2->query($sql);
            file_put_contents(dirname(__FILE__) . '/check.txt', var_export($query2->result_array(), true) . $this->db_feed_burn);
        }

        $db = db_mysql_conn($this->db_feed_burn);
        $db->trans_start();

        if ($urgent) {
            $db->where('urgent', 1);
        }
        $db->where('stat', 1);
        //$db->where('content_update <', date("Y-m-d H:i:s", time()-3600*3));	//3小时以上没更新的账号优先
        $db->order_by('last_sync_time', 'ASC');
        $db->limit(1);
        $query = $db->get($this->db_feed_burn);
        $collectJob = $query->row_array();
        $updateData = array('last_sync_time' => date("Y-m-d H:i:s"));
        $db->where('id', $collectJob['id']);
        $db->update($this->db_feed_burn, $updateData);
        $db->trans_complete();

        return $collectJob['biz'];
    }

    /**
     * by lairongming at 2018-07-17
     * 查找相对较久没抓取的公众号 增加干预
     * @param string $urgent
     * @return mixed
     */
    public function getLongestNoSyncBizExt( $urgent = '' )
    {

        if(FILE_PUT_CONTENTS_DEBUG==1) {
            $db2 = db_mysql_conn($this->db_feed_burn);
            //$db2->limit(2 );
            //$query2 = $db2->get($this->db_feed_burn);
            $sql = "select * from {$this->db_feed_burn}  order by last_sync_time asc limit 0,20 ";
            $query2 = $db2->query($sql);
            file_put_contents(dirname(__FILE__) . '/check.txt', var_export($query2->result_array(), true) . $this->db_feed_burn);
        }
        //尝试查找新订阅
        $r = self::getNewSub();
        if($r){
            return $r;exit(0);
        }
        //尝试查找当前时间的订阅
        $r = self::getDataByNow($urgent);
        if($r){
            return $r;
        }else{
            $db = db_mysql_conn($this->db_feed_burn);
            $db->trans_start();

            if ($urgent) {
                $db->where('urgent', 1);
            }
            $db->where('stat', 1);
            //$db->where('content_update <', date("Y-m-d H:i:s", time()-3600*3));	//3小时以上没更新的账号优先
            $db->order_by('last_sync_time', 'ASC');
            $db->limit(1);
            $query = $db->get($this->db_feed_burn);
            $collectJob = $query->row_array();
            $updateData = array('last_sync_time' => date("Y-m-d H:i:s"));
            $db->where('id', $collectJob['id']);
            $db->update($this->db_feed_burn, $updateData);
            $db->trans_complete();
            return array($collectJob['biz'],self::getRedirectTime());
        }
    }

    /**
     * 根据时间段进行设置抓取跳转频率
     * @return int
     */
    public function getRedirectTimeExt()
    {
        date_default_timezone_set('Asia/Shanghai');
        $hour = (int)date('H');

        if ($hour < 7) {
            return 100000;
        }

        if ($hour >= 7 && $hour < 11) {
            return 45000;
        }

        if ($hour >= 11 && $hour < 18) {
            return 110000;
        }

        if ($hour > 20) {
            return 110500;
        }

        return 35000;
    }

    /**
     * by lairongming at 2018-07-17
     * 获取当前时间段-小时 有没有数据
     * 搜索条件较多，未添加联合索引
     * @param string $urgent
     * @return array|bool
     */
    public function getDataByNow($urgent=''){
        $db = db_mysql_conn($this->db_feed_burn);
        $db->trans_start();

        if ($urgent) {
            //$db->where('urgent', 1);
            $where = "and urgent=1";
        }else{
            $where = '';
        }
        //$db->where('stat', 1);
        //$db->where('content_update <', date("Y-m-d H:i:s", time()-3600*3));	//3小时以上没更新的账号优先
        //$db->order_by('last_sync_time', 'ASC');
        //$db->limit(1);
        $sql = "select * from ".$this->db_feed_burn." where stat=1 ".$where." and  DATE_FORMAT(last_sync_time,'%Y-%m-%d %H')!='".date("Y-m-d G")."' and  FIND_IN_SET(".date('G').",`analyse_result`) order by last_sync_time ASC limit 1";
        $query = $db->query($sql);
        $collectJob = $query->row_array();
        //print_r($collectJob);
        $updateData = array('last_sync_time' => date("Y-m-d H:i:s"));
        $db->where('id', $collectJob['id']);
        $db->update($this->db_feed_burn, $updateData);
        $db->trans_complete();
        //查到有信息使用最短等待进入下一个抓取
        if(isset($collectJob['biz'])&&$collectJob['biz']){
            return array($collectJob['biz'],self::getRedirectTime());
        }else{
            return false;
        }
    }

    /**
     * 查找新订阅的公众号
     * @return array
     */
    public function getNewSub(){
        $db = db_mysql_conn($this->db_feed_burn);
        $db->trans_start();
        $db->where('stat', 1);
        $db->where('newly',1);
        //$db->where('content_update <', date("Y-m-d H:i:s", time()-3600*3));	//3小时以上没更新的账号优先
        $db->order_by('last_sync_time', 'ASC');
        $db->limit(1);
        $query = $db->get($this->db_feed_burn);
        $collectJob = $query->row_array();
        $updateData = array('last_sync_time' => date("Y-m-d H:i:s"),'newly'=>0);
        $db->where('id', $collectJob['id']);
        $db->update($this->db_feed_burn, $updateData);
        $db->trans_complete();
        if($collectJob['biz']){
            return array($collectJob['biz'],self::getRedirectTime());
        }else{
            return false;
        }

    }


    public function isInBlockAuthorName($name)
    {
        $blockAuthorName = array(
            '广告'
        );
        foreach ($blockAuthorName as $value) {
            if (strstr($value, $name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 查找 wx_feed_burn里面biz对应的采集配置
     * @return mixed
     */
    public function getWxFeedBurn(){
        //print_r("\n".$this->db_feed_burn."\n");

        $db = db_mysql_conn($this->db_feed_burn);
        $db->where('biz', trim($this->biz));
        $db->where('stat',1);
        $db->limit(1);
        $query = $db->get($this->db_feed_burn);
        $arrCollectJob = $query->result_array();
        return $arrCollectJob;
    }

    /**
     * 入库前进行数据处理
     * @param $articleData
     * @param $oJob
     * @return mixed
     */
    public function dealInsertData($articleData,$oJob){
        //是否能迁到foreach外面？
        //$app_row = zk_simple_get_block_info($oJob['source']);    //频道信息
        //$articleData['app_id'] = intval($app_row['id']);
        //$articleData['new_app_id'] = intval($app_row['category_id']);
        //$articleData['cms_app_id'] = ((! empty($oJob['cms_app_id'])) ? $oJob['cms_app_id'] : '');

        //判断作者
        if ($oJob['author_tag'] == 2) {
            $articleData['author'] = !empty($articleData['app_msg_ext_info']['author']) ? $articleData['app_msg_ext_info']['author'] : $oJob['name'];
            if (self::isInBlockAuthorName($articleData['author'])) {
                $articleData['author'] = $oJob['name'];
            }
        } elseif ($oJob['author_tag'] == 3) {
            $articleData['author'] = $oJob['name'];
        } else {
            $articleData['author'] = $oJob['name'];
        }

        //待编辑审核启用
        if ($oJob['article_be_enabled']) {
            if ($articleData['stat'] == 1) {
                $articleData['stat'] = 10;
            } elseif ($articleData['stat'] == 0) {
                $articleData['unable'] = 10;
            }
        }

        //file_put_contents('check.txt', $oJob['app_id'] . '-' . $oJob['article_be_enabled'] . '-' . $articleData['title'] . '-' . $oJob['name'] . '-' . $articleData['unable'] . '=' . $articleData['stat']  . "\n", FILE_APPEND);

        // 免责声明
        if ($oJob['disclaimer'] == 1) {
            $articleData['disclaimer'] = 1;
        }



        return $articleData;
    }

    /**
     * 下一个处理
     */
    public function next(){
        $redirect_time = self::getRedirectTime();
        $biz = self::getLongestNoSyncBiz($this->urgent);
        // $url = "http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=" . $collectJob['biz'] . "#wechat_webview_type=1&wechat_redirect";//拼接公众号历史消息url地址（第一种页面形式）
        $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=" . $biz . "&scene=124#wechat_redirect";
        return  "<script>setTimeout(function(){window.location.href='" . $url . "';}," . $redirect_time . ");</script>";//将下一个将要跳转的$url变成js脚本，由anyproxy注入到微信页面中。

        //exit;
    }

    /**
     * 通知三方
     * @param $content_update
     * @param $oJob
     */
    public function sendNotic($content_update,$oJob){
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
        $db = db_mysql_conn($this->db_feed_burn);
        $db->where('id', $oJob['id']);
        $db->update($this->db_feed_burn, $updateData);
    }

    public function getRand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //echo $proSum;
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

}