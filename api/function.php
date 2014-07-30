<?php
/**
 * Grouperコア コンポーネントクラス
 * 
 * Grouperの、主な処理を行うメインコンポーネント
 * 
 * PHP versions 4 and 5
 * 
 * @package org.sysken.grouper.core
 * @copyright &copy; 2014 SYSKEN, Ryosuke Hagihara
 * @create 2014/07/27
 * @auther Ryosuke Hagihara<raryosu@sysken.org>
 * @since PHP 5.5 / MySQL 5+
 * @verison 0.1.20140729
 * @ts 4
 * @link http://grouper.sysken.org/
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
class main //extends mysqli 
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
            if($debug)
            {
                return "Couldn't connect to SQL" . $mysqli->errno;
            }else{
                return false;
            }
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
            case 'tel2':
            case 'tel3':
                if(is_numeric($value) && strlen($value)==4)
                {
                    return true;
                }
                self::error('Vald Error');
                break;
                
            case 'tel1':
                if(is_numeric($value) && strlen($value)==3)
                {
                    return true;
                }
                self::error('Vald Error');
                break;
            
            case 'aleat_choice':
                if(is_numeric($value))
                {
                    return true;
                }
                self::error('Vald Error');
                break;
                
            case 'aram_time':
            case 'group_time':
            case 'talk_time':
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
                    {
                        self::error('Vald Error');
                        break;
                    }
                    
                    if(($month==4 || $month==6 || $month==9 || $month==11)
                        && $day>30)
                    {
                        self::error('Vald Error');
                        break;
                    }
                    
                    if($month==2 && $year%4==1 && $day>28)
                    {
                        self::error('Vald Error');
                        break;
                    }else if($month==2 && $year%4==0 && $day>29){
                        self::error('Vald Error');
                        break;
                    }
                    
                    return true;
                }
            
            case 'is_tel_pub':
            case 'is_repeat':
            case 'is_group_delete':
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
            
            default:
                if(is_string($value))
                {
                    return true;
                }
                self::error('Vald Error');
                break;
        }
    }
    
    /**
     * Security and sanitize
     * @auther Ryosuke Hagihara(raryosu@sysken.org)
     *
     * @param array|string $content User Content
     * @return array                sanitize content
     */
    function secure($content)
    {
        //Init
        $result = '';
        
        //Process
        if(is_array($content))
        {
            foreach($content as $key => $value)
            {
                $result[$key] = str_replace("\0", '', str_replace(
                    array("\\", "\0", "\n", "\r", "\xla", "'", '"'),
                        array("\\\\", "\\0", "\\n", "\\r", "\\xla", "\'", '\"'),
                            htmlspecialchars(mb_convert_encoding($value, 'UTF-8', 'UTF-8,SJIS,EUC-JP')
                        )
                    )
                );
            }
        }else{
            $result[0] = str_replace("\0", '', str_replace(
                array("\\", "\0", "\n", "\r", "\xla", "'", '"'),
                    array("\\\\", "\\0", "\\n", "\\r", "\\xla", "\'", '\"'),
                        htmlspecialchars(mb_convert_encoding($content, 'UTF-8', 'UTF-8,SJIS,EUC-JP')
                    )
                )
            );
        }
        return $result;
    }
    
    function setHeader($header) {
        header($header);
        return true;
    }
    
    function outgoing($content, $header = '')
    {
        if(is_array($header))
        {
            foreach($header as $value)
            {
                self::setHeader($value);
            }
        }
        self::serHeader("Content-Type: application/json; charset=utf-8");
        
        $content = api::createJson($content);
        return $content;
    }
    
    function error($type, $msg) 
    {
        ob_end_flush();
        self::setHeader("Content-Type: application/json; charset=utf-8");
        $json = api::createJson(array('status' => '-1'));
        
        switch($type)
        {
            case 'db':
                self::serHeader("x-status-code: 500-1");
                $json = api::createJson(array('status' => '500'));
                break;
                
            case 'api':
                self::serHeader("x-status-code: 500-2");
                $json = api::createJson(array('status' => '500'));
                break;
                
            case 'session':
                self::serHeader("x-status-code: 500-3");
                $json = api::createJson(array('status' => '500'));
                break;
                
            case 'login':
                self::serHeader("x-status-code: 401");
                $json = api::createJson(array('status' => '401'));
                break;
                
            case 'query':
                self::serHeader("x-status-code: 400-1");
                $json = api::createJson(array('status' => '400'));
                break;
        }
        
        self::setHeader("x-sid: " . time());
        echo $json; // 関数外で吐くようにした方がいいのか・・・？
        exit();
    }
}

/**
 * API
 *
 * APIの処理を行います!!
 * 
 * @auther Ryosuke Hagihara(raryosu@sysken.org)
 * @since PHP5.5+
 * @version 0.1
 */
class api //extends mysqli
{
    protected $_mysqli;
    
    function __construct($host, $username, $password, $db, $port)
    {
        $this->$_mysqli => new db($host, $username, $password, $db);
    }
    
    function createJson($array){
        return json_encode($array);
    }
    
    /**
     * ユーザ登録します
     * 
     * @param string $userID        User ID
     * @param string $password      User password
     * @param string $deviceID      User Android ID
     * @param int $tel1             User phone number XXX-yyyy-zzzz
     * @param int $tel2             User phone number xxx-YYYY-zzzz
     * @param int $tel3             User phone number xxx-yyyy-ZZZZ
     * @param boolean $is_tel_pub   電話番号を公開するか
     * @param string $username      Screen name
     * @return array|string         JSON返す
     */ 
     function regist($userID, $password, $deviceID, $tel1, $tel2, $tel3, $is_tel_pub, $username)
     {
        $query = $this -> _mysqli -> BuildQuery('INSERT', 'User', array(
                                                                         'userID'=>$userID,
                                                                         'password'=>$password,
                                                                         'deviceID'=>$deviceID,
                                                                         'tel1'=>$tel1,
                                                                         'tel2'=>$tel2,
                                                                         'tel3'=>$tel3,
                                                                         'is_tel_pub'=>$is_tel_pub,
                                                                         'username'=>$username)
                                                        );
        $query_rest = $this -> _mysqli => goQuery(array($query, true)); 
        if(!$query_rest)
        {
            main::error('regist', 'regist error');
        }
        return createJson(array('status'=>'200'));
     }
    
    /**
     * ログインを行います
     * 
     * @param string $userID    User ID
     * @param string $password  User password
     * @param string $deviceID  User Android ID
     * @return array|string     JSON返す
     * 
     */
     function login($userID, $password, $deviceID)
     {
         $query = $this -> _mysqli -> buildQuery('SELECT', 'User', array(
                                                                        'userID'=>$userID,
                                                                        'password'=>$password,
                                                                        'deviceID'=>$deviceID)
                                                 );
         $query_rest = $this -> _mysqli -> goQuery($query, true);
         if(!$query_rest)
         {
             main::error('login', 'not login');
         }
         return createJson(array('status'=>'200'));
     }
     
    /**
     * グループを作成します
     * 
     * @param string $sessionID         セッションIDです
     * @param string $group_name        グループ名です
     * @param string $group_desc        グループ詳細
     * @param string $userID            作成者
     * @param timestamp $create_time    作成日時
     * @return array|string             JSON返す
     */ 
     function create($sessionID, $group_name, $group_desc, $userID, $create_time)
     {
        $query = $this -> _mysqli -> BuildQuery('INSERT', 'User', array(
                                                                         'group_name'=>$group_name,
                                                                         'group_description'=>$group_desc,
                                                                         'create_user'=>$userID,
                                                                         'create_time'=>$create_time,
                                                                         'last_update'=>$create_time
                                                                         )
                                                        );
        $query_rest = $this -> _mysqli -> goQuery($query, true);
        if(!$query_rest)
        {
            main::error('create', 'create error');
        }
        return createJson(array('status'=>'200'));
     }
     
     /**
     * 招待IDを追加します
     * 
     * @param string $sessionID         セッションIDです
     * @param string $groupID           グループIDです
     * @param string $inviteID          アプリで生成したランダムな招待IDです
     * @return array|string             JSON返す
     */ 
     function invite($sessionID, $groupID, $inviteID)
     {
         $query = $this -> _mysqli -> BuildQuery('INSERT', 'User', array(
                                                                         'groupID'=>$groupID,
                                                                         'inviteID'=>inviteID
                                                                         )
                                                );
        $query_rest = $this -> _mysqli => goQuery($query, true);
        if(!$query_rest)
        {
            main::error('inviteID', 'inviteID insert error');
        }
        return createJson(array('status'=>200));
     }
     
     /**
     * グループにユーザを追加します
     * 
     * @param string $sessionID         セッションIDです
     * @param string $groupID           グループIDです
     * @param string $userID            ユーザIDです
     * @return array|string             JSON返す
     */      
     function addUser($sessionID, $groupID, $userID)
     {
         $query = $this -> _mysqli -> buildQuery('INSERT', 'Relational', array(
                                                                               'groupID'=>$groupID,
                                                                               'userID'=>$userID
                                                                               )
                                                );
         $query_rest = $this -> _mysqli => goQuery($query, true);
         if(!$query_rest)
         {
             main::error('addUser', 'add user error');
         }
         return createJson(array('status'=>200));
     }
     
     /**
      * トーク用API
      * 
      * @param string sessionID         セッションIDです
      * @param string groupID           グループIDです
      * @param timestamp talk_time      発言した時刻です
      * @param string talk              発言内容です
      * @param float geo_x, geo_y       GPS座標です
      * @param binary media             画像情報です
      */
      function talk($sessionID, $groupID, $talk_time, $talk=null, $geo_x=null, $geo_y=null, $media=null)
      {
          //sessionIDからuserIDを取得したい
          $query = $this -> _mysqli -> buildQuery('INSERT', 'Chat', array(
                                                                          'groupID'=>$groupID,
                                                                          'userID'=>$userID,
                                                                          'talk'=>$talk,
                                                                          'geo_x'=>$geo_x,
                                                                          'geo_y'=>$geo_y,
                                                                          'media'=>$media
                                                                          )
                                                  );
          $query_rest = &this -> mysqli -> _mysqli => goQuery($query, true);
          if(!$query_rest)
          {
              main::error('talk', 'talk error');
          }
          return createJason(array('status'=>200));
      }
}

class db
{
    protected $_mysqli;
    protected $_query;
    protected $host;
    protected $username;
    protected $password;
    protected $db;
    protected $port;
    
    function __construct($host = null, $username = null, $password = null,
                         $db = null, $port = null)
    {
        if($host == null)
        {
            $this -> host = ini_get('mysqli.default_host');
        }else{
            $this -> host = $host;
        }
        
        if($username == null)
        {
            $this -> username = ini_get('mysqli.default_username');
        }else{
            $this -> username = $username;
        }
        
        if($password == null)
        {
            $this -> password = ini_get('mysqli.default_password');
        }else{
            $this -> password = $password;
        }
        
        if($db == null)
        {
            $this -> db = '';
        }else{
            $this -> db = $db;
        }
        
        if($port == null)
        {
            $this -> port = ini_get('mysqli.default_port');
        }else{
            $this -> port = $port;
        }
        
        $this -> connect();
    }
    
    function connect()
    {
        $this -> _mysqli = new mysqli($this -> host, $this -> username, $this -> password,
                                      $this -> db, $this -> port);
        
        if($this -> _mysqli -> connect_error)
        {
            main::error('db', "Couldn't connect to DB");
            return false;
        }
        
        $this -> _mysqli -> set_charset('utf-8');
        return true;
    }
    
    function buildQuery($type, $table, $array)
    {
        $query = '';
        switch($type)
        {
            case 'INSERT':
                $query .= "INSERT INFO `{$table}`  (`" . implode(array_keys($array)), '`, `' . '` ) VALUE ( ';
                
                foreach ($array as $key => $value)
                {
                    $query .= "'{$value}',";
                }
                $query = substr($query, 0, -1);
                $query .= ' )';
                
                break;
                
            case 'SELECT':
                $query .= "SELECT * FROM `{$table}` WHERE ";
                foreach($array as $key => $value)
                {
                    $query .= "`{$key}` = '{$value}' AND";
                }
                $query = substr($query, 0, -4);
                
                break;
        }
        return $query;
    }
    
    function goQuery($query, $is_secure = false)
    {
        if(!$is_secure)
        {
            main::error('db', 'Emergency STOP : Security');
        }
        $rest = $this -> _mysqli -> query($query);
        if($rest === false)
        {
            return false;
        }elseif($rest === true){
            return true;
        }
        
        return $rest->fetch_all(MYSQLI_ASSOC);
    }
}
