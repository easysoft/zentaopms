<?php
/* 包含http客户端类，snoopy。在禅道lib/snoopy里面可以找到。*/
include '../lib/snoopy/snoopy.class.php';

/* 用来登录的地址，用户名和密码。*/
$zentaoRoot  = "http://pms.easysoft.com/";
$account     = "";              // 需要设置有更新燃尽图权限的用户名和密码。
$password    = "";
$requestType = "PATH_INFO";     // 禅道系统访问方式，请根据实际的配置进行修改。

if($account == '' and $password == '') die("Must set account and password.\n");

/* 设置API地址。*/
if($requestType == 'GET')
{
    /* API地址，以GET方式为例。*/
    $loginAPI   = $zentaoRoot . "?m=user&f=login"; 
    $sessionAPI = $zentaoRoot . "?m=api&f=getSessionID&t=json";
    $burnAPI    = $zentaoRoot . "?m=project&f=computeburn"; 
}
elseif($requestType == 'PATH_INFO')
{
    /* API地址，以PATH_INFO方式为例。*/
    $loginAPI   = $zentaoRoot . "user-login.json?a=1"; 
    $sessionAPI = $zentaoRoot . "api-getsessionid.json?a=1";
    $burnAPI    = $zentaoRoot . "project-computeburn?a=1"; 
}

/* 获取session. */
$snoopy = new Snoopy;
$snoopy->fetch($sessionAPI);
$session = json_decode($snoopy->results);

/*用户登录*/
$submitVars["account"]  = $account; 
$submitVars["password"] = $password;
$snoopy->cookies[$session->sessionName] = $session->sessionID;
$snoopy->submit($loginAPI, $submitVars);

/* 直接调用project模块的burn页面。*/
$snoopy->fetch($burnAPI . "&$session->sessionName=$session->sessionID");
$burns = $snoopy->results;
if($burns)
{
    if(strpos($burns, 'script') === false)
    {
        echo $burns;
    }
    else
    {
        echo "No priviledge.\n";
    }
}
else
{
    echo "no projects.\n";
}
?>
