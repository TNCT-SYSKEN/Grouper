<?php
/**
 * アプリケーションとの通信を行います
 */
// ファンクションファイルを読み込む(失敗為たら取り合えず終了)
// ちゃんと失敗したときの処理考えて無い
if(!include '/var/www/html/api/beta/index.php'){
  exit();
}

// httpヘッダ設定
header("Content-Type: application/json; charset=utf-8");

// $_GETと$_POSTは信用できないので 取り合えず一番はじめに処理しておく
foreach($_GET  as $key => $value) { $_GET[$key]  = $common->security($value); }
foreach($_POST as $key => $value) { $_POST[$key] = $common->security($value); }

// モードスイッチによって実行モードを変更する
switch($_GET['mode']) {
  case 'regist':
    $rest = $api->regist($_GET['username'], $_GET['deviceID'], $_GET['tel1'], $_GET['tel2'], $_GET['tel3'],$_GET['is_tel_pub'], $_GET['regID']);
    echo $rest;
  break;

  case 'getUser':
    $rest = $api->getUser($_GET['userID'], $_GET['sessionID']);
    echo $rest;
  break;

  case 'login':
    $rest = $api->login($_GET['userID'], $_GET['password']);
    echo $rest;
  break;

  case 'create':
    $rest = $api->create($_GET['group_name'], $_GET['group_desc'], $_GET['sessionID'], $_GET['userID']);
    echo $rest;
  break;

  case 'inviteID':
    $rest = $api->addInvitation($_GET['groupID'], $_GET['sessionID']);
    echo $rest;
  break;

  case 'addUser':
    $rest = $api->addGroupUser($_GET['groupID'], $_GET['userID'], $_GET['sessionID']);
    echo $rest;
  break;

  case 'talk':
    isset($_GET['talk']) ? $_GET['talk'] : null;
    isset($_GET['media']) ? $_GET['media'] : null;
    isset($_GET['geo_x']) ? $_GET['geo_x'] : null;
    isset($_GET['geo_y']) ? $_GET['geo_y'] : null;
    $rest = $api->talk($_GET['groupID'], $_GET['userID'], $_GET['sessionID'], $talk, $media, $geo_x, $geo_y);
    echo $rest;
  break;

  case 'alarm':
    $rest = $api->alarm($_GET['groupID'], $_GET['userID'], $_GET['sessionID'], $_GET['time_alarm'], $_GET['alart_desc'], $_GET['alert_opt1'], $_GET['alart_opt2']);
    echo $rest;
  break;

  case 'alartchoice':
    $rest = $api->alartchoice($_GET['groupID'], $_GET['userID'], $_GET['sessionID'], $_GET['alart_choice']);
    echo $rest;

  case 'delTalk':
    $rest = $api->delTalk($_GET['userID'], $_GET['sessionID'], $_GET['talkID']);
    echo $rest;
  break;

/* 準備中
  case 'settingGroup':
    isset($_GET['group_name']) ? $_GET['group_name'] : null;
    isset($_GET['group_desc']) ? $_GET['group_desc'] : null;
    isset($_GET['is_group_del']) ? $_GET['is_group_del'] : '0';
    $rest = $api->settingGroup($_GET['userID'], $_GET['sessionID'], $_GET['groupID'], $_GET['group_name'], $_GET['group_desc'], $_GET['is_group_del']);
    echo $rest;
  break;
*/

/* 準備中
  case 'settingUser':
    isset($_GET['group_name']) ? $_GET['group_name'] : null;
    isset($_GET['group_desc']) ? $_GET['group_desc'] : null;
    isset($_GET['is_group_del']) ? $_GET['is_group_del'] : '0';
    $rest = $api->delTalk($_GET['userID'], $_GET['sessionID'], $_GET['groupID'], $_GET['group_name'], $_GET['group_desc'], $_GET['is_group_del']);
    echo $rest;
  break;
*/


  case 'getGroup':
    $rest = $api->getGroup($_GET['groupID'], $_GET['query_mode']);
    echo $rest;
  break;

  default:
    echo $common->error('query','モードが間違っています');

}
