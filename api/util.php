<?php
/** Debug Flag **/
$debug = false;

/** Charm **/
if($debug)
{
    error_reporting(-1);
    ini_set('display_errors', true);
}else{
    error_reporting(0);
    ini_set('display_errors', false);
}

/**
 * Grouperコア コンポーネントクラス
 * 
 * Grouperの、主な処理を行うメインコンポーネント
 * @package info.raryosu.server.api.util
 * @copyright &copy; 2014 Ryosuke Hagihara
 * @create 2014/07/27
 * @auther Grouper Project Team<raryosu@sysken.org>
 * @since PHP 5.5 / MySQL 5+
 * @verison 0.1.20140727
 * @ts 4
 * @link http://sysken.org/
 */

/**
 * メイン処理クラス
 *
 * APIのコア機能を提供します。
 *
 * @auther Grouper Project Team<raryosu@sysken.org>
 * @since PHP5.5+ / MySQL 5+
 * @version 0.1
 */

class main extends mysqli 
{
    /**
     * 公開用jsonを生成します
     *
     * @param string $row rowを渡します
     * @return string json_encodeされた結果を戻します
     */
    function publishJson($row)
    {
    //  関数の中でヘッダー出力していいの？
        header('Content-type: application/json');
        return json_encode($row);
    }

    /**
     * MySQLに接続します
     *
     * @param string $server   MySQL hostname
     * @param string $user     MySQL Username
     * @param string $password MySQL dbpassword
     * @param string $dbName   MySQL Database
     * @return string          ErrorMsg
     */
    function connectSql($server, $user, $dbpassword, $dbName)
    {
        $mysqli = mysqli::__construct($server, $user, $dbpassword, $dbName);
        if(!$connection)
        {
            //mysqli::connect_errno
            if($debug) ? return "Couldn't connect to SQL server" . mysqli->error : return false;
        }
        
    }
    
    /**
     * パラメータを取得します
     *
     * @param string $sessionID     Session ID
     * @param string $userID        User ID
     * @param string $password      User password
     * @param string $deviceID      User Android ID
     * @param string $tel1          Phone number 1 (XXX-yyyy-zzzz)
     * @param string $tel2          Phone number 2 (xxx-YYYY-zzzz)
     * @param string $tel3          Phone number 3 (xxx-yyyy-ZZZZ)
     * @param boolean $is_tel_pub   電話番号を公開するか否か
     * @param string $userName      Display name
     * @param string $out_groupID   抜けるグループのID
     * @param string $new_userName  新しい表示名
     * @param string $groupID       Group ID
     * @param string $groupName     Group name
     * @param string $group_desc    Group description
     * @param string $group_owner   グループ所有者
     * @param boolean $is_group_del グループの削除
     * @param string $new_groupName 新しいグループ名
     * @param string $talk          トーク内容
     * @param string $talk_time     トーク送信日時
     * @param float  $geo_x(geo_y)  GPSから取得した座標情報
     * @param binary $media         画像情報
     * @param string $talkID        Talk ID
     * @param string $talk_del      削除するトークID
     * @param string $time_aram     アラームを鳴らす時刻
     * @param boolean $is_repeat    アラームをリピートするか否か
     * @param string $time_repeat   リピートする時刻（設定時刻に対する相対時刻）
     * @param string $alert_desc    アラート表示内容
     * @param string $alert_opt1    アラート選択肢1
     * @param string $alert_opt2    アラート選択肢2
     * @param string $aramID        Aram ID
     * @param string $alert_choise  アラートの選択内容
     * @return string          
     */    
     
    function getParameter()
    {
        /**
         if(isset($_GET['userID'])) {
             $id = $_GET['userID'];
             return $id;
             よく考えたらしたのやつだとreturできない
         }
        */
        $sessionID = isset($_GET['sessionID']) ?: NULL;

        // About User
        $userID = isset($_GET['userID']) ?: NULL;
        $password = isset($_GET['password']) ?: NULL;
        $deviceID = isset($_GET['deviceID']) ?: NULL;
        $tel1 = isset($_GET['tel1']) ?: NULL;
        $tel2 = isset($_GET['tel2']) ?: NULL;
        $tel3 = isset($_GET['tel3']) ?: NULL;
        $is_tel_public = isset($_GET['tel3']) ?: False;
        $userName = isset($_GET['username']) ?: NULL;
        $out_groupID = isset($_GET['out_groupID']) ?: NULL;
        $new_userName = isset($_GET['new_userName']) ?: $userName;
        
        // About Group
        $groupID = isset($_GET['groupID']) ?: NULL;
        $groupName = isset($_GET['group_name']) ?: NULL;
        $group_desc = isset($_GET['group_description']) ?: NULL;
        $group_owner = isset($_GET['group_owner']) ?: NULL;
        $group_del = isset($_GET['del_group']) ?: False;
        $new_groupName = isset($_GET['new_groupName']) ?: $groupName;
        
        // About Talk
        $talk = isset($_GET['talk']) ?: NULL;
        $talk_time = isser($_GET['talk_time']) ?: NULL;
        $geo_x = isset($_GET['geo_x']) ?: NULL;
        $geo_y = isset($_GET['geo_y']) ?: NULL;
        $media = isset($_GET['media']) ?: NULL;
        $talkID = isset($_GET['talkID']) ?: NULL;
        $is_talk_del = isset($_GET['is_talk_del']) ?: NULL;
        
        // About Aram
        $time_aram = isset($_GET['time_aram']) ?: NULL;
        $is_repeat = isset($_GET['is_repeat']) ?: False;
        $time_repeat = isset($_GET['time_repeat']) ?: NULL;
        $alert_desc = isset($_GET['alert_desc']) ?: NULL;
        $alert_opt1 = isset($_GET['alert_opt1']) ?: NULL;
        $alert_opt2 = isset($_GET['alert_opt2']) ?: NULL;
        $aramID = isset($_GET['aramID']) ?: NULL;
        $alert_choise = isset($_GET['alert_choise']) ?: NULL;
    }
}
