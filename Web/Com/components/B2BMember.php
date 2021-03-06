<?php

/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-9-21
 * Time: 下午4:17
 */
abstract class B2BMember extends B2BPlatform{


    protected $error = '';

    public function error(){
        return $this->error;
    }

//-------------------------------------- 登录 ------------------------------------------------------------------------------//

    protected $login_addresss = 'https://login.ec21.com/global/login/LoginSubmit.jsp';
    //表单项
    protected $login_hiddens = [
        'FBIn'  => '',
        'fEmail'  => '',
        'inq_gubun'  => '',
        'nextUrl'  => 'http://www.ec21.com/',
        'periodLimit'   => 'Y',
    ];
    protected $usernameField  = 'user_id';
    protected $passwordField  = 'user_pw';
    protected $captureField = '';//验证码
    /**
     * @var string 登录账户名
     */
    protected $username = '';

    /**
     * @var string 登录cookie文件
     */
    protected $cookie_login = '';

    /**
     * @var string 登录记录文件
     */
    protected $loginrecord = '';

    /**
     * 登录操作
     * @param string $username
     * @param string $password 为空时使用用户名
     * @param string $capture
     * @return bool
     * @throws Exception
     */
    public function login($username,$password='',$capture='') {
        $this->username = $username;
        $password or $password = $username;
        SEK::touch($this->cookie_login = PATH_COOKIE.'Login_/'.static::class.$username);
        $fields = $this->buildLoginFields($username,$password,$capture);
        $response = self::post($this->login_addresss,http_build_query($fields),$this->cookie_login,true);
        $result = $this->isLoginSuccess($response);
        if($result and $this->saveExpareTimestamp($response)) throw new Exception('保存登录到期时间失败');
        return $result;
    }

    /**
     * 获取用户列表
     * @param bool $onlyavailable
     * @return array
     */
    public function getMemberList($onlyavailable=true){
        return MemberModel::getInstance()->select(($onlyavailable?'total < 15 and':'').' platform = \'ec21\'');
    }

    /**
     * 平台+用户名作为cookie的标识符
     * @return string
     */
    protected function getIdentify(){
        return md5(static::class.'__'.$this->username);
    }

    /**
     * 船舰登录表单
     * 三个参数通常是必填项
     * @param $username
     * @param $passwd
     * @param string $capture
     * @return array
     */
    protected function buildLoginFields($username,$passwd,$capture=''){
        $form = array(
            $this->usernameField    => $username,
            $this->passwordField    => $passwd,
        );
        if($this->captureField){/* 需要验证码的时候填写 */
            $form[$this->captureField] = $capture;
        }
        return array_merge($this->login_hiddens,$form);
    }
    /**
     * 保存登录到期时间
     * @param string $response
     * @return bool
     * @throws Exception
     */
    protected function saveExpareTimestamp($response){
        if(preg_match('/Expires=([\w,\s\d-:.]*\sGMT)/',$response,$matches)){
            if(isset($matches[1])){
                $timestamp = strtotime($matches[1]);
                $id = $this->getIdentify();
                $records = is_readable($this->loginrecord)?include $this->loginrecord:[];
                if(!is_array($this->loginrecord)){
                    $records = [];
                }
                $records[$id] = $timestamp;//覆盖式
                if(!file_put_contents($this->loginrecord,'<?php /* 该文件中记录着cookie到期时间 */ return '.var_export($records,true).';')){
                    throw new Exception('EC21登录记录文件不可写!');
                }
            }
        }
        return false;
    }


    /**
     * 判断登录是否成功
     * @param string $response 登录返回信息
     * @return bool
     */
    abstract protected function isLoginSuccess($response);



//-------------------------------------- 注册 ------------------------------------------------------------------------------//


    /**
     * @var string 注册邮箱
     */
    protected $email = '';
    /**
     * @var string 注册验证码
     */
    protected $capture = '';

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email) {
        if(false === strpos($email,'@')){
            $email .= '@qq.com';
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $capture
     * @return $this
     */
    public function setCapture($capture) {
        $this->capture = $capture;
        return $this;
    }

//    /**
//     * 注册公司信息
//     * @return array
//     */
//    abstract public function register();
//
//    /**
//     * 更新公司信息
//     * @return mixed
//     */
//    abstract public function update();





}