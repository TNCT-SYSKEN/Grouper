<?php
/**
 * アプリケーションとの通信を行います
 */
// ファンクションファイルを読み込む(失敗為たら取り合えず終了)
// ちゃんと失敗したときの処理考えて無い
if(!include '/var/www/html/api/beta/index.php'){
	exit();
}

header("Content-Type: application/json; charset=utf-8");

// $_GETと$_POSTは信用できないので 取り合えず一番はじめに処理しておく
foreach($_GET  as $key => $value) { $_GET[$key]  = $common->security($value); }
foreach($_POST as $key => $value) { $_POST[$key] = $common->security($value); }

// モードスイッチによって実行モードを変更する
switch($_GET['mode']) {
	case 'regist':
		$rest = $api->regist($_GET['username'], $_GET['deviceID'], $_GET['tel1'], $_GET['tel2'], $_GET['tel3'], $_GET['is_tel_pub']);
		echo $rest;
	break;

	case 'getUser':
		$rest = $api->getUser($_GET['userID'], $_GET['sessionID']);
		echo $rest;
	break;

	case 'login':
		echo $api->login($_GET['userID'], $_GET['password']);
	break;

	case 'create':
		echo $api->create($_GET['group_name'], $GET['group_desc'], $_GET['sessionID']);
	break;

	case 'inviteID':
		echo $api->addInvitation($_GET['groupID'], $_GET['sessionID'])
	break;

	case 'addUser':
		// 後で何とかします。ごめんなさい。許してください。なんでもし
	break;

	case 'talk':
		// あとでお願いします
	break;

	case 'alarm':
		// 後でします
	break;

	case 'settingTalk':
		// あｔ
	break;

	case 'settingGroup':
		// あ
	break;

	case 'getGroup':
		// あｔ
	break;

	default:
		echo $common->error('query','モードが間違っています');

}
