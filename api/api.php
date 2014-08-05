<?php
/**
 * アプリケーションとの通信を行います
 */
// ファンクションファイルを読み込む(失敗為たら取り合えず終了)
// ちゃんと失敗したときの処理考えて無い
if(!include 'index.php'){
	exit();
}


// $_GETと$_POSTは信用できないので 取り合えず一番はじめに処理しておく
foreach($_GET  as $key => $value) { $_GET[$key]  = $common->security($value); }
foreach($_POST as $key => $value) { $_POST[$key] = $common->security($value); }


// モードスイッチによって実行モードを変更する
switch($_GET['mode']) {
	case 'regist':
		$rest = $api->addUser($_GET['userid'], $_GET['password'], $_GET['devid'], $_GET['tel1'], $_GET['tel2'], $_GET['tel3']);
		echo $rest;
	break;

	case 'get':
		$rest = $api->getUser($_GET['userid'], $_GET['session']);
		echo $rest;
	break;

	case 'login':
		echo $api->login($_GET['userid'], $_GET['password'], $_GET['devid']);
	break;

	default:
		echo $common->error('query','モードが間違っています');

}