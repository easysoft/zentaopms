#!/usr/bin/env php
<?php
ob_start();
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::deny();
timeout=0
cid=15668

- 超级管理员返回TRUE @1
- deny中鉴权，有权限权限时返回TRUE @1
- deny中鉴权，无权限权限时返回FALSE @0
- deny中, 不需要再次鉴权返回0 @0
- deny中, 不需要再次鉴权，并且是ajax请求 @{"load":"user-deny-user-create.html"}0

*/

zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('grouppriv')->loadYaml('projectgrouppriv')->gen(10);
zenData('usergroup')->loadYaml('projectusergroup')->gen(10);
$group = zenData('group')->loadYaml('projectgroup');
$group->project->range('0');
$group->gen(10);

global $tester, $config;
$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

$tester->loadModel('common');

su('admin');
r($tester->common->deny('user', 'create')) && p() && e('1'); // 超级管理员返回TRUE

su('user1');
r($tester->common->deny('bug', 'create')) && p() && e('1'); // deny中鉴权，有权限权限时返回TRUE

try{
    $result1 = $tester->common->deny('user', 'create');
} catch(Exception $e){
    $result1 = $e->getMessage();
}
r($result1) && p() && e('0'); // deny中鉴权，无权限权限时返回FALSE

try{
    $result2 = $tester->common->deny('user', 'create', false);
} catch(Exception $e){
    $result2 = $e->getMessage();
}
r($result2) && p() && e('0'); // deny中, 不需要再次鉴权返回0

try{
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    $result2 = $tester->common->deny('user', 'create', false);
} catch(Exception $e){
    $result2 = $e->getMessage();
}
r($result2) && p() && e('{"load":"user-deny-user-create.html"}0'); // deny中, 不需要再次鉴权，并且是ajax请求
