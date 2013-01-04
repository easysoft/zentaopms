#!/usr/bin/env php
<?php
error_reporting(E_ERROR);
/* 包含http客户端类，snoopy。在禅道lib/snoopy里面可以找到。*/
$pmsRoot = dirname(dirname(dirname(__FILE__)));
include $pmsRoot . '/config/my.php';
include $pmsRoot . '/lib/snoopy/snoopy.class.php';

/* 用来登录的地址，用户名和密码。*/
$zentaoRoot  = 'http://' . $config->default->domain . '/';  // 请根据实际的情况进行修改。
$requestType = $config->requestType;       // 可选值： GET|PATH_INFO。
$account     = "";    //登录禅道的用户名
$password    = "";    //登录禅道的密码

if($account == '' and $password == '') die("Must set account and password in " . __FILE__ . ".\n");

/* 设置API地址。*/
if($requestType == 'GET')
{
    /* API地址，以GET方式为例。*/
    $loginAPI      = $zentaoRoot . "?m=user&f=login"; 
    $sessionAPI    = $zentaoRoot . "?m=api&f=getSessionID&t=json";
    $myBugAPI      = $zentaoRoot . "?m=my&f=bug&t=json"; 
    $superMyBugAPI = $zentaoRoot . "?m=api&f=getModel&module=bug&methodName=getUserBugPairs&params=account=$account";
}
elseif($requestType == 'PATH_INFO')
{
    /* API地址，以PATH_INFO方式为例。*/
    $loginAPI      = $zentaoRoot . "user-login.json?a=1"; 
    $sessionAPI    = $zentaoRoot . "api-getsessionid.json?a=1";
    $myBugAPI      = $zentaoRoot . "my-bug.json?a=1"; 
    $superMyBugAPI = $zentaoRoot . "api-getmodel-bug-getUserBugPairs-account=$account.json?a=1";
}

/* 获取session. */
$snoopy = new Snoopy;
$snoopy->fetch($sessionAPI);
$session = json_decode($snoopy->results);
$session = json_decode($session->data);

/*用户登录*/
$authHash = md5(md5($password) . $session->rand);
$submitVars["account"]  = $account; 
$submitVars["password"] = $authHash;
$snoopy->cookies[$session->sessionName] = $session->sessionID;
$snoopy->submit($loginAPI, $submitVars);

/* 直接调用my模块的bugs页面。*/
$snoopy->fetch($myBugAPI . "&$session->sessionName=$session->sessionID");
$result = json_decode($snoopy->results);

if($result->status == 'success' && md5($result->data) == $result->md5)
{
    $bugs = json_decode($result->data)->bugs;
}
else
{
    echo "called failed or transfered not complete.";
    exit;
}

if($bugs)
{
    foreach($bugs as $bug) echo $bug->id . "\t" . $bug->title . "\n";
}
else
{
    echo 'no bugs' . "\n";
}

/* 通过超级model调用。*/
$snoopy->fetch($superMyBugAPI . "&$session->sessionName=$session->sessionID");
$result = json_decode($snoopy->results);
if(is_object($result))
{
    foreach($result as $id=>$bug) echo $id  . "\t" . $bug . "\n";
}
else
{
    echo 'no bugs' . "\n";
}
?>
