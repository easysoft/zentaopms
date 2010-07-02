#!/usr/bin/env php
<?php
/* 包含http客户端类，snoopy。在禅道lib/snoopy里面可以找到。*/
include '../lib/snoopy/snoopy.class.php';

/* 用来登录的地址，用户名和密码。*/
$zentaoRoot  = "http://pms.easysoft.com/";
$account     = "";              // 需要设置用户名和密码。
$password    = "";
$requestType = "PATH_INFO";     // 禅道系统访问方式，请根据实际的配置进行修改。

if($account == '' and $password == '') die("Must set account and password.\n");

/* 设置API地址。*/
if($requestType == 'GET')
{
    /* API地址，以GET方式为例。*/
    $loginAPI   = $zentaoRoot . "?m=user&f=login"; 
    $sessionAPI = $zentaoRoot . "?m=api&f=getSessionID&t=json";
    $myTodoAPI    = $zentaoRoot . "?m=my&f=todo&t=json"; 
}
elseif($requestType == 'PATH_INFO')
{
    /* API地址，以PATH_INFO方式为例。*/
    $loginAPI   = $zentaoRoot . "user-login.json?a=1"; 
    $sessionAPI = $zentaoRoot . "api-getsessionid.json?a=1";
    $myTodoAPI    = $zentaoRoot . "my-todo.json?a=1"; 
}

/* 获取session。 */
$snoopy = new Snoopy;
$snoopy->fetch($sessionAPI);
$session = json_decode($snoopy->results); 
$session = json_decode($session->data);

/*用户登录,加密验证。*/
$authHash = md5(md5($password) . $session->rand);
$submitVars["account"]  = $account; 
$submitVars["password"] = $authHash;
$snoopy->cookies[$session->sessionName] = $session->sessionID;
$snoopy->submit($loginAPI, $submitVars);

/* 直接调用my模块的todo页面。*/
$snoopy->fetch($myTodoAPI . "&$session->sessionName=$session->sessionID");
$result = json_decode($snoopy->results);

if($result->status == 'success' && md5($result->data) == $result->md5)
{
    $todos = json_decode($result->data, false, 512)->todos;
}
else
{
    echo "called failed or transfered not complete.";
    exit;
}

if($todos)
{
    foreach($todos as $todo)
        echo $todo->id . "\t" .
        $todo->type . "\t" .
        $todo->pri . "\t" .
        $todo->name . "\t" .
        $todo->status . "\n";
}
else
{
    echo "no todos.\n";
}
?>
