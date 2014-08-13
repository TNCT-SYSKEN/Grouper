<?php
/**
 * Grouper ファンクション
 *
 * @copyright &copy; 2014 Ryosuke Hagihara
 * @create 2014.08.05
 * @auther Ryosuke Hagihara<raryosu@sysken.org>
 * @since PHP5.5+ / MySQL 5.3+
 * @version 0.2.20140813
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

      case 'push':
        self::setHeader("x-status-code:500-5");
        $json = api::createJson(array('status'=>'ERR', 'contents'=>(array('code'=>'400', 'msg'=>$msg))));
        break;
    }
    self::setHeader("x-sid: " . time());
    echo $json;
    exit();
  }

  /**
   * push通知を送信します
   *
   * @param array|string $regID レジストレーションキー
   * @param strinr $msg         送信したいメッセージ
   * @param string $senduser    送信者のID
   * @return  bool              成功したかどうか
   */
  function sender($regID, $msg, $senduser, $mode='talk')
  {
    $url = 'https://android.googleapis.com/gcm/send';
    $header = array(
                    'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                    'Authorization: key=AIzaSyDwMmyU5RHNn6NL8m_fGHvzaQaWB87HFFY'
                   );
    $msg = array('mode' => $mode, 'message' => $msg);
    // クソ
    for($i=0; !empty($regID); $i++)
    {
      $postParam = array(
                        'registration_id' => $regID[$i],
                        'collapse_key' => 'update',
                        'data.message' => $msg
                        );
      $post = http_build_query($postParam, '&');

      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_FAILONERROR, 1);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($curl, CURLOPT_POST, TRUE);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
      curl_setopt($curl, CURLOPT_TIMEOUT, 5);
      $ret = curl_exec($curl);
      if(!$ret)
      {
        self::error('push', 'push通知を送信できませんでした。');
      }
      unset($regID[$i]);
    }
    return true;
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
  function __construct($host = null, $username = null, $password = null, $db = null, $port = null)
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

        case 'timestamp':
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
      return true;
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
   * @param  string  $regID     Google Cloud Messege レジストレーションキー
   * @return bool               結果を返す
   */
  function regist($username, $deviceID, $tel1, $tel2, $tel3, $is_tel_pub=0, $regID)
  {
    self::paramAssign('username', '64,NOT_NULL,text', $username);
    self::paramAssign('deviceID', '64,NOT_NULL,text', $deviceID);
    self::paramAssign('tel1', '3,int', $tel1);
    self::paramAssign('tel2', '4,int', $tel2);
    self::paramAssign('tel3', '4,int', $tel3);
    self::paramAssign('is_tel_pub', '4,int', $is_tel_pub);
    self::paramAssign('regID', '1000,text', $regID);

    $userID = self::createRandHex('6');
    $password = self::createRandHex('8');
    $sessionID = self::createRandHex('32');
    $query = $this -> _mysqli -> buildQuery('INSERT', 'User',
                                            array('userID' => $userID,
                                                  'password' => $password,
                                                  'user_name' => $this -> _PARAM['username'],
                                                  'tel1' => $this -> _PARAM['tel1'],
                                                  'tel2' => $this -> _PARAM['tel2'],
                                                  'tel3' => $this -> _PARAM['tel3'],
                                                  'is_tel_pub' => $this -> _PARAM['is_tel_pub'],
                                                  'sessionID' => $sessionID,
                                                  'regID' => $this -> _PARAM['regID'],
                                                  'deviceID' => $this -> _PARAM['deviceID']
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
  function create($group_name, $group_desc, $sessionID, $userID)
  {
    self::paramAssign('sessionID', '64,NOT_NULL,hex', $sessionID);
    self::paramAssign('group_name', '32,NOT_NULL,text',$group_name);
    self::paramAssign('group_desc', '140,NOT_NULL,text', $group_desc);
    self::paramAssign('userID', '64,NOT_NULL,hex', $userID);

    // ログイン状態の確認
    /*
    if(!self::is_login($this->_PARAM['sessionID']))
    {
      common::error('login', 'not login');
    }
    */
    $groupID = self::createRandHex(10);

    // グループ追加
    $query = $this -> _mysqli -> buildQuery('INSERT', 'Group', array('groupID'=>$groupID,
                                                                    'group_name'=>$this->_PARAM['group_name'],
                                                                    'group_desc'=>$this->_PARAM['group_desc'],
                                                                    'createUser'=>$this->_PARAM['userID']
                                                                    )
                                          );
    $query_rest = $this->_mysqli->goQuery($query,true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    // $id = $this -> _mysqli -> getID();

    // 結果を返す
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200', 'groupID'=>$groupID)));
  }

  /**
   * グループにユーザを追加します
   *
   * @param string $groupID    グループID
   * @param string $userID     ユーザID
   * @param string $sessionID  セッションID
   * @return bool|array
   */
  function addGroupUser($groupID, $userID, $sessionID)
  {
    self::paramAssign('groupID', '64,NOT_NULL,hex', $groupID);
    self::paramAssign('userID', '32,NOT_NULL,text',$userID);
    $query = $this -> _mysqli -> buildQuery('INSERT', 'relational', array(
                                                                          'userID' => $this -> _PARAM['userID'],
                                                                          'groupID' => $this -> _PARAM['groupID']
                                                                          )
                                           );
    $query_rest = $this->_mysqli->goQuery($query,true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200')));
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
    $hex = self::createRandHex('10');
    self::paramAssign('invitation', '50,NOT_NULL,hex', $hex);
    self::paramAssign('groupID', '32,NOT_NULL,text', $groupID);

    $query = $this -> _mysqli -> buildQuery('INSERT', 'invitation', array(
                                                                          'groupID' => $this -> _PARAM['groupID'],
                                                                          'invitation' => $hex
                                                                          )
                                           );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200', 'inviteID'=>$hex)));
  }

  /**
   * ユーザ情報の取得
   *
   * @param string $userID        ユーザID
   * @param string $sessionID     セッションID
   * @param string $query_mode    モード[user, group]
   * @return bool|array           連想配列
   */
  function getUser($userID = NULL, $sessionID, $query_mode)
  {
    self::paramAssign('sessionID', '64,NOT_NULL,hex', $sessionID);
    self::paramAssign('userID', '64,NOT_NULL,text', $userID);
    self::paramAssign('query_mode', '64,NOT_NULL,text', $query_mode);

    switch($query_mode)
    {
      case 'user':
        $query = $this -> _mysqli -> buildQuery('SELECT', 'User', array(
                                                                        'userID'=>$this->_PARAM['userID'],
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
        unset($rest['user_SQE']);
        unset($rest['password']);
        unset($rest['delete']);
        unset($rest['deviceID']);
        unset($rest['is_tel_pub']);
        unset($rest['tel1']);
        unset($rest['tel2']);
        unset($rest['tel3']);
      break;

      case 'group':
        $query = $this -> _mysqli -> buildQuery('SELECT', 'Session', array(
                                                                        'userID'=>$this->_PARAM['userID'],
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
        unset($rest['relational_SQE']);
        unset($rest['userID']);
      break;

      default:
        common::error('query', 'format error');
    }

    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200', $rest)));
  }

  /**
   * グループ情報の取得
   *
   * @param string $groupID        ユーザID
   * @param string $query_mode 実行モード[user(グループに属すユーザ情報の取得), group(グループ名, グループ作成者のIDを取得)]
   * @return bool|array           連想配列
   */
  function getGroup($groupID, $query_mode)
  {
    self::paramAssign('groupID', '64,NOT_NULL,hex', $groupID);
    self::paramAssign('query_mode', '64,NOT_NULL,string', $query_mode);

    //モードスイッチによる切り替え(検索するDBの切り替え)
    switch($this->_PARAM['query_mode'])
    {
      case 'user':
        $query = $this -> _mysqli -> buildQuery('SELECT', 'relational', array('groupID'=>$this->_PARAM['groupID']));
        $query_rest = $this -> _mysqli -> goQuery($query, true);
        if(empty($query_rest))
        {
          return common::error('query', 'missing');
        }
        foreach($query_rest as $key => $value)
        {
          $rest[$key] = $value;
        }
        $rest = $rest[0];
        unset($rest['relation_SQE']);
        unset($rest['groupID']);
      break;

      case 'group':
        $query = $this -> _mysqli -> buildQuery('SELECT', 'Group', array('groupID'=>$this->_PARAM['groupID']));
        $query_rest = $this -> _mysqli -> goQuery($query, true);
        if(empty($query_rest))
        {
          return common::error('query', 'missing');
        }
        foreach($query_rest as $key => $value)
        {
          $rest[$key] = $value;
        }
        $rest = $rest[0];
        unset($rest['group_SQE']);
        unset($rest['groupID']);
        unset($rest['last_update']);
        unset($rest['createTime']);
      break;
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200', $rest)));
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
                                                                    )
                                            );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('login', 'IDかパスワードが間違っています');
    }
    // password verify

    // 新しいセッションの生成
    $sessionID = self::createRandHex('32');
    $query = $this -> _mysqli -> buildQuery('INSERT', 'session', array(
                                                                       'sessionID' => $sessionID,
                                                                       'userID' => $this -> _PARAM['userID']
                                                                      )
                                           );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if($query_rest === true)
    {
      return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200',
                                                                      'sessionID'=>$sessionID
                                                                      )
                                    )
                              );
    }
  }

  /**
   * トークをDBに登録しプッシュ通知を行う準備をします
   *
   * @param string $groupID   グループID
   * @param string $userID    ユーザID
   * @param setinr $sessionID セッションID
   * @param string $talk      発言内容
   * @param binary $media     画像のバイナリデータ
   * @param float  $geo_x     GPSで取得した位置情報のx座標
   * @param float  $geo_y     GPSで取得した位置情報のy座標
   * @return array|bool 連想配列
   */
  function talk($groupID, $userID, $sessionID, $talk, $media, $geo_x, $geo_y)
  {
    self::paramAssign('groupID', '64,NOT_NULL,text', $groupID);
    self::paramAssign('userID', '64,NOT_NULL,text', $userID);
    self::paramAssign('sessionID', '100,NOT_NULL,text', $sessionID);
    self::paramAssign('talk', '500,text', $talk);
    self::paramAssign('media', '2500000000000000,binary', $media);
    self::paramAssign('geo_x', '100,text', $geo_x);
    self::paramAssign('geo_y', '100,text', $geo_y);

    $talkID = self::createRandHex('15');

    $query = $this -> _mysqli -> buildQuery('INSERT', 'talk', array(
                                                                       'groupID' => $this -> _PARAM['groupID'],
                                                                       'userID' => $this -> _PARAM['userID'],
                                                                       'talkID' => $talkID,
                                                                       'talk' => $this -> _PARAM['talk'],
                                                                       'media' => $this -> _PARAM['media'],
                                                                       'geo_x' => $this -> _PARAM['geo_x'],
                                                                       'geo_y' => $this -> _PARAM['geo_y']
                                                                    )
                                           );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }

    if(!($talk === null))
    {
      $msg = $talk;
    } elseif (!($media === null)) {
      $msg = $media;
    } elseif(!($geo_x === null) && !($geo_y === null))
    {
      $msg = $geo_x . ',' . $geo_y;
    }

    // ユーザからregisterIDを絞り出す(正確には該当するユーザのユーザIDの列を連想配列で抜き出す)
    $query_user = $this -> _mysqli -> buildQuery('SELECT', 'User', array('userID'=> $this -> _PARAM['userID']));
    $user = $this -> _mysqli -> goQuery($user, true, 'select');

    // senderに送信リクエストを渡す
    $query_II_rest = common::sender($user['regID'], $msg, $userID);
    if(!$query_II_rest)
    {
      common::error('query', 'missing gcm');
    }

    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200',
                                                                    'talkID'=>$talkID
                                                                    )
                                  )
                            );
  }

  /**
   * アラームを設定します
   *
   * @param string $groupID        グループID
   * @param string $userID         ユーザID
   * @param setinr $sessionID      セッションID
   * @param timestamp $alarm_time  アラーム時刻
   * @param string $alarm_desc     アラーム詳細
   * @param float  $alert_opt1     アラート選択肢1
   * @param float  $alert_opt2     アラート選択肢2
   * @return array|bool 連想配列
   */
  function alarm($groupID, $userID, $sessionID, $alarm_time, $alert_desc, $alert_opt1, $alert_opt2)
  {
    self::paramAssign('groupID', '64,NOT_NULL,text', $groupID);
    self::paramAssign('userID', '64,NOT_NULL,text', $userID);
    self::paramAssign('sessionID', '100,NOT_NULL,text', $sessionID);
    self::paramAssign('alarm_time', '500,NOT_NULL,timestamp', $alarm_time);
    self::paramAssign('alert_desc', '500,text', $alert_desc);
    self::paramAssign('alert_opt1', '100,text', $alert_opt1);
    self::paramAssign('alert_opt2', '100,text', $alert_opt2);

    $alarmID = self::createRandHex('15');

    $query = $this -> _mysqli -> buildQuery('INSERT', 'Alarm', array(
                                                                       'groupID' => $this -> _PARAM['groupID'],
                                                                       'createUser' => $this -> _PARAM['userID'],
                                                                       'alarmID' => $alarmID,
                                                                       'alarm_time' => $this -> _PARAM['alarm_time'],
                                                                       'alarm_desc' => $this -> _PARAM['alarm_desc'],
                                                                       'alarm_opt1' => $this -> _PARAM['alarm_opt1'],
                                                                       'alarm_opt2' => $this -> _PARAM['alarm_opt2']
                                                                    )
                                           );
    $query_rest = $this -> _mysqli -> goQuery($query, true);
    if(!$query_rest)
    {
      common::error('query', 'missing');
    }
  }

  /**
   * グループの設定を行います
   *
   * @param string $groupID    グループID
   * @param string $userID     ユーザID
   * @param string $sessionID  セッションID
   * @param string $group_name グループ名
   * @param string $group_desc グループ詳細
   * @param string $is_group_del グループ削除
   * @return bool|array
   */
  function settingGroup($userID , $sessionID, $groupID, $group_name, $group_desc, $is_group_del)
  {
    self::paramAssign('groupID', '64,NOT_NULL,hex', $groupID);
    self::paramAssign('userID', '64,NOT_NULL,text',$userID);
    self::paramAssign('sessionID', '200,NOT_NULL,hex', $sessionID);
    self::paramAssign('group_name', '200,NOT_NULL,text', $group_name);
    self::paramAssign('group_desc', '200,NOT_NULL,text', $group_desc);
    self::paramAssign('is_group_del', '2, NOT_NULL,text', $is_group_del);
    if($this -> _PARAM['is_group_del'] == 1)
    {
      $query_group     = $this -> _mysqli -> buildQuery('DELETE', 'Group', array('groupID' => $this -> _PARAM['groupID']));
      $query_alarm     = $this -> _mysqli -> buildQuery('DELETE', 'Alarm', array('groupID' => $this -> _PARAM['groupID']));
      $query_relational= $this -> _mysqli -> buildQuery('DELETE', 'relational', array('groupID' => $this -> _PARAM['groupID']));
      $query_group_rest      = $this->_mysqli->goQuery($query_group,true);
      $query_alarm_rest      = $this->_mysqli->goQuery($query_alarm,true);
      $query_relational_rest = $this->_mysqli->goQuery($query_relational,true);
      if(!$query_group_rest)
      {
        echo $query_group;
        common::error('query', 'missing db:group');
      }
      if(!$query_alarm_rest)
      {
        echo $query_alarm;
        common::error('query', 'missing db:alarm');
      }
      if(!$query_relational_rest)
      {
        echo $query_relational;
        common::error('query', 'missing db:relational');
      }
    }else{
      // Groupテーブルから該当のグループの列を引っこ抜く
      $query = $this -> _mysqli -> buildQuery('SELECT', 'Group', array('groupID' => $this -> _PARAM['groupID']));
      $query_rest = $this->_mysqli->goQuery($query,true);

      if(!$query_rest)
      {
        common::error('query', 'missing');
      }
      foreach($query_rest as $key => $value)
      {
        $rest[$key] = $value;
      }
      $rest = $rest[0];

      // Group名もしくはグループ詳細を変更しない場合は現状の名前を再度入れる
      if($group_name === '0')
      {
        $group_name = $rest['group_name'];
      }else{
        $group_name = $this -> _PARAM['group_name'];
      }
      if($group_desc === '0')
      {
        $group_desc = $rest['group_desc'];
      }else{
        $group_desc = $this -> _PARAM['group_desc'];
      }

      $query = $this -> _mysqli -> buildQuery('Update', 'Group', array('group_name' => $group_name,'group_desc' => $group_desc),
                                                                        array('groupID' => $this -> _PARAM['groupID']));
      $query_rest = $this->_mysqli->goQuery($query,true);
      if(!$query_rest)
      {
        common::error('query', 'missing');
      }
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200')));
  }

  /**
   * ユーザの設定を行います
   *
   * @param string $userID      ユーザID
   * @param string $sessionID   セッションID
   * @param string $user_name   ユーザ名
   * @param string $groupID     グループID
   * @param string $is_user_del ユーザ削除
   * @return bool|array
   */
  function settingUser($userID , $sessionID, $groupID, $user_name, $is_user_del)
  {
    self::paramAssign('groupID', '64,NOT_NULL,hex', $groupID);
    self::paramAssign('userID', '64,NOT_NULL,text',$userID);
    self::paramAssign('sessionID', '200,NOT_NULL,hex', $sessionID);
    self::paramAssign('user_name', '200,NOT_NULL,text', $user_name);
    self::paramAssign('is_user_del', '2, NOT_NULL,text', $is_user_del);
    if($this -> _PARAM['is_user_del'] === 1)
    {
      $query_user      = $this -> _mysqli -> buildQuery('DELETE', 'User', array('userID' => $this -> _PARAM['userID']));
      $query_alarm     = $this -> _mysqli -> buildQuery('DELETE', 'Alarm', array('userID' => $this -> _PARAM['userID']));
      $query_relational= $this -> _mysqli -> buildQuery('DELETE', 'relational', array('userID' => $this -> _PARAM['userID']));
      $query_user_rest       = $this->_mysqli->goQuery($query_user,true);
      $query_alarm_rest      = $this->_mysqli->goQuery($query_alarm,true);
      $query_relational_rest = $this->_mysqli->goQuery($query_relational,true);
      if(!$query_user_rest)
      {
        common::error('query', 'missing');
      }
      if(!$query_alarm_rest)
      {
        common::error('query', 'missing');
      }
      if(!$query_relational_rest)
      {
        common::error('query', 'missing');
      }
    }else{
      // Userテーブルから該当のUserの列を引っこ抜く
      $query = $this -> _mysqli -> buildQuery('SELECT', 'User', array('userID' => $this -> _PARAM['userID']));
      $query_rest = $this->_mysqli->goQuery($query,true);

      if(!$query_rest)
      {
        common::error('query', 'missing');
      }
      foreach($query_rest as $key => $value)
      {
        $rest[$key] = $value;
      }
      $rest = $rest[0];

      // user名\を変更しない場合は現状の名前を再度入れる
      if($user_name === '0')
      {
        $user_name = $rest['user_name'];
      }else{
        $user_name = $this -> _PARAM['user_name'];
      }
      $query = $this -> _mysqli -> buildQuery('UPDATE', 'User', array('user_name' => $user_name),
                                              array('userID' => $this -> _PARAM['userID']));
      $query_rest = $this->_mysqli->goQuery($query,true);
      if(!$query_rest)
      {
        common::error('query', 'missing');
      }
    }
    return self::createJson(array('status'=>'OK', 'contents'=>array('code'=>'200')));
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
  function __construct($host = null, $username = null, $password = null, $db = null, $port = null)
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
      $this -> db = 'Grouper_new';
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
        $query .= "INSERT INTO Grouper_new.{$table} ( " . implode(array_keys($search), ', ') . ' ) VALUE ( ';
        foreach ($search as $key => $value)
        {
          $query .= "'" . self::security($value) . "',";
        }
        $query = substr($query, 0, -1);
        $query .= ' )';
        break;

      case 'select':
        $query .= "SELECT * FROM Grouper_new.{$table} WHERE ";
        foreach($search as $key => $value)
        {
          $query .= "{$key} = '" . self::security($value) . "' AND " ;
        }
        $query = substr($query, 0, -5);
        break;

      case 'update':
        $query .= "UPDATE `{$table}` SET ";
        foreach($search as $key => $value)
        {
          $query .= "`{$key}` = '" . self::security($value) . "', ";
        }
        $query = substr($query, 0, -2);

        foreach($update as $key => $value)
        {
          $query .= "WHERE `{$key}` = '" . self::security($value) . "'";
        }
        break;

      case 'delete':
        $query .= "DELETE FROM `{$table}` WHERE";
        foreach($search as $key => $value)
        {
          $query .= "`{$key}` = '" . self::security($value) . "'";
        }
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
  function goQuery($query, $is_secure = false, $mode = null)
  {
    if(!$is_secure)
    {
      common::error('db', 'Emergency STOP');
    }
    switch ($mode)
    {
      case 'select':
        $rest = $this -> _mysqli -> query($query);
        return $rest;
        break;

      default:
        $rest = $this -> _mysqli -> query($query);
        if($rest === false)
        {
          return false;
        }elseif($rest === true){
          return true;
        }
        break;
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
