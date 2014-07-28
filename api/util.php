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
    
    function paramAssign($array)
    {
        foreach($array as $key => $value)
        {
            htmlentities(preg_replace(array("\n", "\0"), "", $value));
            self::validation($value, $key);
            $rest[$key] = $value;
        }
        return $rest;
    }
    
    function validation($value, $type = 'none')
    {
        switch($type)
        {
            case 'tel1', 'tel2', 'tel3', 'aleat_choice':
                if(is_numeric($value))
                {
                    return true;
                }
                self::error('Vald Error');
                break;
                
            case 'aram_time', 'group_time', 'talk_time':
                $date_format = '%\d{4,4}/\d{2,2}/\d{2,2}\s\d{2,2}:\d{2,2}:\d{2,2}%';
                if(preg_match($date_format, $value))
                {
                    $date    = preg_replace(array('/', '\s', ':'),'',$date);
                    $date_n  = int($date);
                    $year    = int($date_n/pow(10, 10));
                    $month   = int($date_n/pow(10, 8) % 100);
                    $day     = int($date_n/pow(10, 6) % 100);
                    $hour    = int($date_n/pow(10, 4) % 100);
                    $minute  = int($date_n/pow(10, 2) % 100);
                    $second  = int($date_n % 100);
                    
                    if($year<2014 || $year>2030 || $month<1 || $month>12 ||
                    $day<1 || $day>31 || $hour<0 || $hour>23 ||
                    $minute<0 || minute>59 || $second<0 || $second>0)
                        self::error('Vald Error');
                        break;
                    
                    if(($month==4 || $month==6 || $month==9 || $month==11)
                    && $day>30)
                        self::error('Vald Error');
                        break;
                    
                    if($month==2 && $year%4==1 && $day>28)
                        self::error('Vald Error');
                        break;
                    else if($month==2 && $year%4==0 && $day>29)
                        self::error('Vald Error');
                        break;
                    
                    return true;
                }
            
            case 'is_tel_pub', 'is_repeat', 'is_group_delete':
                if(is_bool($value))
                {
                    return true;
                }
                self::error('Vald Error');
                break;
                
            case 'alert_choise':
                if($value=='1' || $value=='0')
                {
                    return true;
                }
                self::error('Vald Error');
                break;
            
            case default:
                if(is_string($value))
                {
                    return true;
                }
                self::error('Vald Error');
                break;
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
