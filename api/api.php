<?php
/** 
 * GrouperメインAPI(?)
 * 
 * GrouperのAPI
 * 
 * PHP version 4 and 5
 * 
 * @package org.sysken.grouper.api
 * @copyright &copy; 2014 SYSKEN, Ryosuke Hagihara
 * @create 2014/07/31
 * @auther Ryosuke Hagihara<raryosu@sysken.org>
 * @since PHP5.5 / MySQL5+
 * @version 0.1.20140804
 * @ts 4
 * @link http://grouper.sysken.org/
 */
 
 // debug flag
 $debug = false;
 
 // デ??
if($debug)
{
    error_reporting(-1);
    ini_set('display_errors', true);
}else{
    error_reporting(0);
    ini_set('display_errors', false);
}

// function.phpをinclude
if(!include('/var/www/api/function.php')) // 絶対パスで指定(パッケージ名?)
{
    echo 'Internal Server Error';
    exit();
}

// 初期化(インスタンスを作るんじゃぁ＾～)
$main = new main();
$api = new api(localhost, connection, grouper_server_tsuyama, Grouper);

// $_GET, $_POSTの処理 
foreach($_GET as $key => $value)
{
    $_GET[$key] = $main -> secure($value);

    if(!($main -> validation($key, $_GET[$key])))
    {
        exit();     //ここの処理不安
    }
}

foreach($_POST as $key => $value)
{
    $_POST = $main -> secure($value);
/*
    if(!($main -> validation($key, $_POST[$key])))
    {
        exit();     //ここの処理不安
    }
*/
}

// 実際に動かす部分(modeでswitch)
switch($_GET[$mode])
{
    //ユーザ登録
    case 'regist':
        $rest = $api -> regist($_GET['userID'], $_GET['password'], $_GET['deviceID'], $_GET['tel1'],
                               $_GET['tel2'], $_GET['tel3'], $_GET['is_tel_pub'], $_GET['username']);
            // function regist($userID, $password, $deviceID, $tel1, $tel2, $tel3, $is_tel_pub, $username)
        echo $rest;
        break;
    
    // login * 動作しません
    case 'login':
        $rest = $api -> login($_GET['userID'], $_GET['password'], $_GET['deviceID']);
            // function login($userID, $password, $deviceID)
        echo $rest;
        break;
        
    // グループ作??しません
    case 'create':
        $rest = $api -> create($_GET['sessionID'], $_GET['group_name'], $_GET['group_desc'], $_GET['create_time']);
            // function create($sessionID, $group_name, $group_desc, $create_time)
        echo $rest;
        break;
    
    // 招待ID生??しません
    case 'inviteID':
        $rest = $api -> invite($_GET['sessionID'], $_GET['groupID']);
            // function invite($sessionID, $groupID)
        echo $rest;
        break;
        
    // グループにユーザ追加 * 動作しません
    case 'addUser':
        $rest = $api -> addUser($sessionID, $inviteID);
            // function addUser($sessionID, $inviteID)
        echo $rest;
        break;
        
    // トーク * 動作しません
    case 'talk':
        $rest = $api -> talk($_GET['sessionID'], $_GET['groupID'], $_GET['talk_time'], $_GET['talk'],
                             $_GET['geo_x'], $_GET['geo_y'], $_GET['media']);
            //function talk($sessionID, $groupID, $talk_time, $talk=null, $geo_x=null, $geo_y=null, $media=null)
        echo $rest;
        break;
    
    // アラーム設定 * 動作しません
    case 'alarm':
        $rest = $api -> alarm($_GET['sessionID'], $_GET['groupID'], $_GET['time_alarm'], $_GET['is_repeat'],
                              $_GET['time_repeat'], $_GET['alert_desc'], $_GET['alert_opt1'], $_GET['alert_opt2']);
            // function alarm($sessionID, $groupID, $time_alarm, $is_repeat=false,
            //                $time_repeat=null, $alert_desc, $alert_opt1, $alert_opt2)
        echo $rest;
        break;
    
    // アラート応答 * 動作しません    
    case 'alertchoice':
        $rest = $api -> alertchoice($_GET['sessionID'], $_GET['alarmID'], $_GET['alert_choice']);
            // function alertchoice($sessionID, $alarmID, $alert_choice)
        echo $rest;
        break;
        
    // トーク削除 * 動作しません
    case 'settingTalk':
        $rest = $api -> settingTalk($_GET['sessionID'], $_GET['talkID'], $_GET['talk_del']);
            // function settingTalk($sessionID, $talkID, $talk_del)
        echo $rest;
        break;
        
    // ユーザ情報書き換え * 動作しません
    case 'settingUser':
        $rest = $api -> settingUser($_GET['sessionID'], $_GET['out_groupID'],
                                    $_GET['is_tel_pub'], $_GET['username']);
            // function settingUser($sessionID, $out_groupID='', $is_tel_pub=false, $username='')
        echo $rest;
        break;
        
    // ユーザ情報取得 * 動作しません
    case 'getUser':
        $rest = $api -> getUser($_GET['sessionID']);
            // function getUser($sessionID)
        echo $rest;
        break;
    
    // グループ管?しません
    case 'settingGroup':
        $rest = $api -> settingGroup($_GET['sessionID'], $_GET['group_name'], 
                                     $_GET['group_desc'], $_GET['is_group_del']);
            // function settingGroup($sessionID, $group_name='', $group_desc='', $is_group_del=false)
        echo $rest;
        break;
    
    // グループ情報取得 * 動作しません
    case 'getGroup':
        $rest = $api -> getGroup($_GET['sessionID'], $_GET['groupID']);
            // function getGroup($sessionID, $groupID)
        echo $rest;
        break;
        
    default:
        echo $main -> error('query', 'query error');
}
?>


