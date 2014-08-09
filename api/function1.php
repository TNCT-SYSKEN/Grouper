<?php
/**
 * Grouper ファンクション
 *
 * @copyright &copy; 2014 Ryosuke Hagihara
 * @create 2014.08.05
 * @auther Ryosuke Hagihara<raryosu@sysken.org>
 * @since PHP5.5+ / MySQL 5.3+
 * @version 0.2.20140807
 * @link http://grouper.sysken.org/
 */

// ファイルが直接読み込まれた場合は終了
if(basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) exit();

/**
 * 共通クラス
 *
 * すべての処理で共通して利用する関数をまとめたクラスです。
 *
 * @copyright &copy;2014 Ryosuke Hagihara
 * @version 0.2.20140805
 */
class common
{
  /**
   * バイナリセーフでない値をバリデーションします(日本語あやうい)
   *
   * @param string $t 信頼に値しない情報
   * @return string   エスケープ結果
   */
  function security($t)
  {
    return str_replace('\0', '',
                       str_replace(array('\\', '\0', '\n', '\r', '\xla', "'", '"'),
                                   array('\\\\', '\\0', '\\n', '\\r', '\\xla', "\\'", '\\"'),
                                   htmlspecialchars(mb_convert_encoding($t, 'UTF-8', 'UTF-8,SJIS,EUC-JP,Unicode'))
                                   )
                       );
  }

  /**
   * レスポンスヘッダを設定します
   *
   * @param string $header    ヘッダ文字列
   * @return bool             実行結果
   */
  function setHeader($header)
  {
    header($header);
    return true;
  }

  /**
   * データ送信準備
   *
   * @param array $content 連想配列
   * @param array $header  ヘッダ内容
   * @return string        出力できる文字列
   */
  function outgoing($content, $header = '')
  {
    // ヘッダの設定
    if(is_array($header))
    {
      foreach ($header as $value)
      {
        self::setHeader($value);
      }
    }
    self::setHeader("Content-Type: application/json; charset=utf-8");

    // メインコンテンツ
    $content = api::createJson($content);
    return $content;
  }

  /**
   * エラー時に実行する関数
   * エラー用コード生成を行います
   *
   * @param string $type エラーの発生箇所[db, api, session, internal, query, login, other]
   * @param strinr $msg  エラーの詳細
   * @return  null
   */
  function error($type, $msg)
  {
//    ob_end_flush();
//    self::setHeader("Content-Type: application/json; charset=utf-8");
    $json = api::createJson(array('status'=>'500', 'contents'=>array('code'=>'-１', 'msg'=>'未知のエラーが発生しました')));

    switch($type)
    {
      case 'db':
        self::setHeader("x-status-code: 500-1");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'500', 'msg'=>$msg))));
        break;

      case 'api':
        self::setHeader("x-status-code: 500-2");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'500', 'msg'=>$msg))));
        break;

      case 'session':
        self::setHeader("x-status-code: 500-3");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'500', 'msg'=>$msg))));
        break;

      case 'internal':
        self::setHeader("x-status-code: 500-4");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'500', 'msg'=>$msg))));
        break;

      case 'login':
        self::setHeader("x-status-code: 401");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'401', 'msg'=>$msg))));
        break;

      case 'query':
        self::setHeader("x-status-code:400-1");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'400', 'msg'=>$msg))));
        break;
    }
    self::setHeader("x-sid: " . time());
    echo $json;
    exit();
  }
}

/**
 * Grouper API処理クラス
 *
 * APIに関するクラスです
 *
 * @copyright &copy; 2014 Ryosuke Hagihara
 * @version 0.2.20140803
 */
class api
{
  /**
   * MySQLハンドラの保持
   *
   * @var resourse MySQLインスタンス
   */
  protected $_mysqli;

  /**
   * APIグローバルパラメータ
   *
   * @var array パラメータ
   */
  protected $_PARAM;

  /**
   * コンストラクト
   *
   * APIクラスが初期化された時に実行されます。
   *
   * @param string $host      MySQL接続先
   * @param string $username  MySQLユーザ
   * @param string $password  MySQLユーザのパスワード
   * @param string $db        DB名
   * @param int    $port      MySQLポート
   */
  function __construct($host = null, $username = null, $password = null,
                       $db = null, $port = null)
  {
    $this -> _mysqli = new db($host, $username, $password, $db);
  }

  /**
   * 渡された配列データからAPIレスポンス用のJSONを生成します
   *
   * @param  array $array 連想配列
   * @return string       生成されたJSONデータ
   */
  function createJson($array)
  {
    return json_encode($array);
  }

  /**
   * パラメータのアサイン
   *
   * @param string $name 変数名
   * @param string $mode バリデーションモード
   * @param string $text アサインしたいテキスト
   * @return bool
   */
  function paramAssign($name, $mode, $text)
  {
    $options = explode(',', $mode);
    if(!self::validation($text, $options))
    {
      common::error('query', 'query error (format)');
    }
    /*
    if(array_search('password', $options))
    {
      $text = password_hash($text, PASSWORD_BCRYPT, array('cost'=>12));
    }
    */
   $this -> _PARAM[$name] = $text;
   return true;
  }

  /**
   * バリデーション
   *
   * @param string $text テキスト
   * @param array  $mode バリデーションモード
   * @return bool
   */
  function validation($text, $mode)
  {
    if(!is_array($mode))
    {
      return false;
    }

    foreach ($mode as $value)
    {
      if(is_numeric($value))
      {
        if(!(mb_strlen($text) <= $value))
        {
          return false;
        }
      }
      switch(mb_strtolower($value))
      {
        case 'not_null':
          if(empty($text) && $text != '0')
          {
            return false;
          }
          break;

        case 'date':
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
            if($year<2014 || $year>2030 || $month<1 || $month>12 || $day<1 ||
               $day>31 || $hour<0 || $hour>23 || $minute<0 || minute>59 ||
               $second<0 || $second>0)
            {
              return false;
            }

            if(($month==4 || $month==6 || $month==9 || $month==11) && $day>30)
            {
              return false;
            }

            if($month==2 && $year%4==1 && $day>28)
            {
              return false;
            }else if($month==2 && $year%4==0 && $day>29){
              return false;
            }
            break;
          }

        case 'hex':
          if(!ctype_xdigit($text) && !empty($text))
          {
             return false;
          }
          break;

        case 'int':
          if(!is_numeric($text) && !empty($text))
          {
            return false;
          }
          break;
      }
    }
    return true;
  }

  /**
   * ログイン状態を返す
   *
   * @param string $sessionID セッションID
   * @param bool   $update    セッション更新フラグ
   * @return  bool            ログイン状態
   */
  function is_login($sessionID, $update=true)
  {
    $query = $this -> _mysqli -> buildQuery('SELECT', 'session', array('sessionID'=>$sessionID));

    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(count($query_rest) == 1)
    {
      return ture;
    }elseif(count($query_rest)==0){
      return false;
    }
    common::error('session', 'Conflict');
  }

  /**
   * ユーザ登録
   *
   * @param  string $username   ユーザ表示名
   * @param  string $deviceID   androidID
   * @param  string $tel1       電話番号上３桁
   * @param  string $tel2       電話番号中4桁
   * @param  string $tel3       電話番号下4桁
   * @param  string $is_tel_pub 電話番号公開フラグ
   * @return bool               結果を返す
   */
  function regist($username, $deviceID, $tel1, $tel2, $tel3, $is_tel_pub=0)
  {
    self::paramAssign('username', '64,NOT_NULL,text', $username);
    self::paramAssign('deviceID', '64,NOT_NULL,text', $deviceID);
    self::paramAssign('tel1', '3,int', $tel1);
    self::paramAssign('tel2', '4,int', $tel2);
    self::paramAssign('tel3', '4,int', $tel3);
    self::paramAssign('is_tel_pub', '4,int', $is_tel_pub);

    $userID = self::createRandHex('6');
    $password = self::createRandHex('8');
    $sessionID = self::createRandHex('32');
    $query = $this -> _mysqli -> buildQuery('INSERT', 'User',
                                            array('userID' => $userID,
                                                  'password' => $password,
                                                  'username' => $this -> _PARAM['username'],
                                                  'tel1' => $this -> _PARAM['tel1'],
                                                  'tel2' => $this -> _PARAM['tel2'],
                                                  'tel3' => $this -> _PARAM['tel3'],
                                                  'is_tel_pub' => $this -> _PARAM['is_tel_pub'],
                                                  'sessionID' => $sessionID
                                                  )
                                            );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200',
                                                                    'userID'=>$userID,
                                                                    'password'=>$password
                                                                    )
                                  )
                           );
  }

  /**
   * グループ作成します
   *
   * @param string $group_name グループ名
   * @param string $group_desc グループ詳細
   * @param string $sessionID  セッションID
   * @return bool|array
   */
  function create($group_name, $group_desc, $sessionID)
  {
    self::paramAssign('sessionID', '64,NOT_NULL,hex', $sessionID);
    self::paramAssign('name', '32,NOT_NULL,text',$group_name);
    self::paramAssign('group_desc', '140,NOT_NULL,text', $group_desc);

    // ログイン状態の確認
    if(!self::is_login($this->_PARAM['sessionID']))
    {
      common::error('login', 'not login');
    }

    $groupID = self::createRandHex(10);

    // グループ追加
    $query = $this -> mysqli -> buildQuery('INSERT', 'Group', array('name'=>$this->_PARAM['name'],
                                                                    'description'=>$this->_PARAM['group_desc']
                                                                    )
                                          );
    $query_rest = $this->_mysqli->goQuery($query,true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    $id = $this -> _mysqli -> getID();
    self::paramAssign('groupID', '7,NOT_NULL,int', $groupID);

    // sessionIDからユーザを割り出す(もしかしたら関数にした方がいいかも)
    $userID = self::getUser('', $this -> _PARAM['sessionID'], 'array', true);
    self::paramAssign('userID', "255, NOT_NULL, text", $userID);
    if(empty($this -> _PARAM['userID']))
    {
      common::error('Internal', 'ERROR!!!!!!!!!!!!!!!!!!!!!!!!!!!');
    }

    // 招待IDの取得
    $inviteID = self::addInvitation($this->_PARAM['groupID'], $this->_PARAM['sessionID']);

    // グループへのユーザ追加
    self::addGroupUser($this->_PARAM['groupID'], $this->_PARAM['userID'], '1011');

    // 結果を返す
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200')));
  }

  function addGroupUser($groupID, $userID, $permission)
  {
    // あとで考える
    return false;
  }

  /**
   * 招待コードの生成及び登録
   *
   * @param string $groupID   グループID
   * @param string $sessionID セッションID
   * @return string           招待コード
   */
  function addInvitation($groupID, $sessionID)
  {
    $hex = self::createRandHex('6');
    self::paramAssign('invitation', '6,NOT_NULL,hex', $hex);
    self::paramAssign('groupID', '32,NOT_NULL,text', $groupID);

    // 作り方悩んでる

    $query = $this -> _mysqli -> buildQuery('INSERT', 'invitation', array(
                                                                          'groupID' => $this -> _PARAM['groupID'],
                                                                          'invitation' => $this -> _PARAM['invitation']
                                                                          )
                                           );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    return $this -> _PARAM['invitation'];
  }

  /**
   * ユーザ情報の取得
   *
   * @param string $userID        ユーザID
   * @param string $sessionID     セッションID
   * @param string $fmt           フォーマット
   * @param bool $is_sessionSerch ユーザIDを割り出すならtrue
   * @return bool|array           連想配列
   */
  function getUser($userID = NULL, $sessionID, $fmt = 'json', $is_sessionSerch = False)
  {
    if($is_sessionSerch  === true)
    {
      self::paramAssign('session', '64,NOT_NULL,hex', $sessionID);
      if(!$self::is_login($this -> _PARAM['sessionID']))
      {
        common::error('login', 'not login');
      }

      $query = $this -> _mysqli -> buildQuery('SELECT', 'session', array(
                                                                         'sessionID'=>$this->_PARAM['sessionID'],
                                                                         'logout'=>'0')
                                             );
      $query_rest = $this -> _mysqli -> goQuery($query, true);
      if(empty($query_rest))
      {
        common::error('query', 'ログインしなおしてください');
      }
      self::paramAssign('userID', '64,NOT_NULL,hex', $query_rest['username']);
      return $this -> _PARAM['userID'];
    }else{
      self::paramAssign('sessionID', '64,NOT_NULL,hex', $sessionID);
      self::paramAssign('userID', '64,NOT_NULL,text', $userID);

      if(!self::is_login($this->_PARAM['sessionID']))
      {
        common::error('login', 'not login');
      }
      $query = $this -> _mysqli -> buildQuery('SELECT', 'User', array(
                                                                      'userID'=>$this->_PARAM['userID'],
                                                                      'is_delete'=>'0'
                                                                      )
                                             );
      $query_rest = $this -> _mysqli -> goQuery($query, true);
      if(empty($query_rest))
      {
        common::error('query', 'not found');
      }
      foreach($query_rest as $key => $value)
      {
        $rest[$key] = $value;
      }
      $rest = $rest[0];
      unset($rest['password']);
      unset($rest['delete']);
      unset($rest['deviceID']);

      if($rest['is_tel_pub'] == 0)
      {
        unset($rest[tel1]);
        unset($rest[tel2]);
        unset($rest[tel3]);
      }
      if($fmt == 'row')
      {
        return $rest;
      }
      switch($fmt)
      {
        case 'json':
          return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200', $rest)));

        case 'array':
        default:
          return $rest;
      }
    }
  }

  /**
   * ログインします
   *
   * @param string $userID   ユーザID
   * @param string $password パスワード
   * @return array|bool      連想配列
   */
  function login($userID, $password)
  {
    self::paramAssign('userID', '64,NOT_NULL,text', $userID);
    self::paramAssign('password', '64,NOT_NULL,text', $password);

    // ユーザが存在しないかチェック～
    $query = $this -> _mysqli -> buildQuery('SELECT', 'User', array('userID'=>$this->_PARAM['userID'],
                                                                    'password'=>$this->_PARAM['password'],
                                                                    'is_delete'=>'0')
                                            );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('login', 'IDかパスワードが間違っています');
    }
    // password verify
    
    // 既存のログインセッションを無効化する
    $query = $this -> _mysqli -> buildQuery('SELECT', 'session', array(
                                                                       'userID'=>$this->_PARAM['userID'],
                                                                       'is_logout'=>'0')
                                            );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!empty($query_rest))
    {
      $query = $this -> _mysqli -> buildQuery('UPDATE', 'session', array('sessionID'=>$query_rest['id'],
                                                                         'is_logout'=>'0',
                                                                         array('is_logout'=>'1')
                                                                        )
                                              );
      $query_rest = $this -> _mysqli -> goQuery($query, true);
    }

    // 新しいセッションの生成
    $sessionID = self::createRandHex('32');
//    self::paramAssign('session', '32,NOT_NULL,hex', $sessionID);
    $query = $this -> _mysqli -> buildQuery('INSERT', 'session', array(
                                                                       'userID' => $this -> _PARAM['userID'],
                                                                       'sessionID' => $sessionID
                                                                      )
                                           );
    echo $query;
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if($query_rest === true)
    {
      return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200',
                                                                      'sessionID'=>$sessionID
                                                                      )
                                    )
                              );
    }
//    return true;
  }

  /**
   * ランダムな16進数の値を生成します
   *
   * @param int $int 生成する桁数
   * @return string  ランダムな文字列
   */
  function createRandHex($int)
  {
    self::paramAssign('int', '3,NOT_NULL,int', $int);
    $bytes = openssl_random_pseudo_bytes($this -> _PARAM['int']);

    $hex = bin2hex($bytes);
    return $hex;
  }
}

/**
 * Grouper DBアクセスクラス
 *
 * DBにアクセスします
 *
 * @copyright &copy; 2014 Ryosuke Hagihara
 * @version 0.2.20140803
 */
class db
{
  /**
   * MySQLハンドラの保持
   */
  protected $_mysqli;

  /**
   * クエリ文字列
   */
  protected $_query;

  /** 
   * MySQL接続先ホスト名・ユーザ名・パスワード・db・ポート
   */
  protected $host;
  protected $username;
  protected $password;
  protected $db;
  protected $port;

  /**
   * コンストラクト
   *
   * @param string [$host = null]     MySQL接続先
   * @param string [$username = null] MySQLユーザ
   * @param string [$password = null] MySQLパスワード
   * @param string [$db = null]       データベースホスト名
   * @param int    [$port = null]     ポート
   */
  function __construct($host = null, $username = null, $password = null,
                       $db = null, $port = null)
  {
    if($host === NULL)
    {
      $this -> host = 'localhost';
                      //ini_get('mysqli.default_host');
    }else{
      $this -> host = $host;
    }

    if($username === NULL)
    {
      $this -> username = 'connection';
                          //ini_get('m.default_user');
    }else{
      $this -> username = $username;
    }

    if($password === NULL)
    {
      $this -> password = 'grouper_server_tsuyama';
                          //ini_get('mysqli.default_pw');
    }else{
      $this -> password = $password;
    }

    if($db === NULL)
    {
      $this -> db = 'Grouper';
    }else{
      $this -> db = $db;
    }

    if($port === NULL)
    {
      $this -> port = ini_get('mysqli_default_port');
    }else{
      $this -> port = $port;
    }

    $this -> connect();
  }

  /**
   * データベースに接続します
   *
   * @return bool 実行結果
   */
  function connect()
  {
    $this -> _mysqli = new mysqli ($this -> host, $this -> username, $this -> password,
                                   $this -> db, $this ->port);
    
    if($this -> _mysqli -> connect_error)
    {
      common::error('db', 'Error connecting to DB');
      return false;
    }
    $this -> _mysqli -> set_charset('utf-8');
    return true;
  }

  /**
   * SQL インジェクション対策
   *
   * @param string $t 信頼に値しない情報
   * @return string 処理した文字列
   */
  function security($t)
  {
    return $this -> _mysqli -> real_escape_string($t);
  }

  /**
   * クエリを生成
   *
   * @param string $type  実行モードの指定[INSERT, UPDATE, DELETE, SELECT]
   * @param string $table 問い合わせたいテーブル
   * @param array $array  アサインしたいデータ配列
   * @return string       生成したクエリ
   *
   * @todo deleteのところ
   */
  function buildQuery($type, $table, $search, $update = null)
  {
    $query = '';
    switch (mb_strtolower($type))
    {
      case 'insert':
        $query .= "INSERT INTO Grouper.{$table} ( " . implode(array_keys($search), ', ') . ' ) VALUE ( ';
        foreach ($search as $key => $value)
        {
          $query .= "'" . self::security($value) . "',";
        }
        $query = substr($query, 0, -1);
        $query .= ' )';
        break;
      
      case 'select':
        $query .= "SELECT * FROM {$table} WHERE ";
        foreach($search as $key => $value)
        {
          $query .= "{$key} = '" . self::security($value) . "' AND " ;
        }
        $query = substr($query, 0, -5);
        break;

      case 'update':
        $query .= "UPDATE `{$table}` SET ";
        foreach($update as $key => $value)
        {
          $query .= "`{$key}` = '" . self::security($value) . "' AND ";
        }
        $query = substr($query, 0, -5);
        break;

      case 'delete':
        break;
    }

    return $query;
  }

  /**
   * 完全なクエリの実行
   *
   * @param string $query   SQL問い合せ
   * @param bool $is_secure 安全かどうか
   * @return  bool          問い合わせ結果
   */
  function goQuery($query, $is_secure = false)
  {
    if(!$is_secure)
    {
      common::error('db', 'Emergency STOP');
    }
    $rest = $this -> _mysqli -> query($query);

    if($rest === false)
    {
      return false;
    }elseif($rest === true){
      return true;
    }
    return $rest -> fetch_all(MYSQLI_ASSOC);
  }

  /**
   * オートインクリメントで処理された値の取得
   * @return  int
   */
  function getID()
  {
    return $this -> _mysqli -> mysqli_stmt_insert_id;
  }
}
