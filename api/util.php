<?php
/**
 * Grouperコア コンポーネントクラス
 * 
 * Grouperの、主な処理を行うメインコンポーネント
 * @package org.sysken.grouper.core
 * @copyright &copy; 2014- Ryosuke Hagihara
 * @create 2014/07/27
 * @auther Grouper Project Team<raryosu@sysken.org>
 * @since PHP 5.5 / MySQL 5+
 * @verison 0.1.20140727
 * @ts 4
 * @link http://sysken.org/
 */

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
        header('Content-type: application/json');
        return json_encode($row);
    }
    /**
     * MySQLに接続します
     *
     * @param  string $server   MySQL hostname
     * @param  string $user     MySQL Username
     * @param  string $password MySQL dbpassword
     * @param  string $dbName   MySQL Database
     * @return string           ErrorMsg
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
    
    function __construct($array = '')
    {
        if(empty($array))
        {
            foreach($array as $key => $value)
            {
                if(is_array($_GET[$key]))
                {
                    self::__construct($GET_[$key]);
                }
                $_GET[$key] = str_replace("\0", '', str_replace(
                    array("\\", "\0", "\n", "\r", "\xla", "'", '"'),
                    array("\\\\", "\\0", "\\n", "\\r", "\\xla", "\'", '\"'),
                    htmlspecialchars(mb_convert_encoding($value, 'USJIS, EUC-JP'))));
            }
            return true;
        }
        foreach($_GET as $key => $value)
        {
            if(is_array($_GET[$key]))
            {
                self::__construct($_GET[$key]);
            }
            $_GET[$key] = str_replace("\0", '', str_replace(
                array("\\", "\0", "\n", "\r", "\xla", "'", '"'),
                array("\\\\", "\\0", "\\n", "\\r", "\\xla", "\'", '\"'),
                htmlspecialchars(mb_convert_encoding($value, 'UTF-8 EUC-JP'))));
        }
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = str_replace("\0", '', str_replace(
                array("\\", "\0", "\n", "\r", "\xla", "'", '"'),
                array("\\\\", "\\0", "\\n", "\\r", "\\xla", "\'", '\"'),
                htmlspecoalchars(mb_convert_encoding($value, 'UTF-8 EUC-JP'))));
        }
    }
}
