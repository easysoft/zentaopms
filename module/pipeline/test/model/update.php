#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->update();
cid=1

- 测试id为0时，修改名称 @0
- 测试id为1时，修改名称
 - 第0条的field属性 @name
 - 第0条的old属性 @gitLab
 - 第0条的new属性 @修改名称1
- 测试id为1时，修改账号
 - 第1条的field属性 @account
 - 第1条的old属性 @account
 - 第1条的new属性 @root
- 测试id为1时，修改password
 - 第1条的field属性 @password
 - 第1条的old属性 @~~
 - 第1条的new属性 @654321
- 测试id为1时，修改token
 - 第2条的field属性 @token
 - 第2条的old属性 @~~
 - 第2条的new属性 @123456
- 测试id为1时，修改名称为空第name条的0属性 @『应用名称』不能为空。
- 测试id为1时，修改名称重复第name条的0属性 @『应用名称』已经有『gitLab』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试id为1时，修改账号为空第account条的0属性 @『用户名』不能为空。
- 测试id为1时，修改token为空第token条的0属性 @『Token』不能为空。
- 测试id为1时，修改password为空第password条的0属性 @『密码』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(6);

$names     = array('', 'gitLab', '修改名称1', '修改名称2', '修改名称3', '修改名称4', '修改名称5', '修改名称6', '修改名称7');
$tokens    = array('', '123456');
$passwords = array('', '654321');
$accounts  = array('', 'account', 'root');

$idList = array(0, 1, 5, 7);

$changeName     = array('name' => $names[2], 'url' => 'http://gitlab.com', 'token' => $tokens[0], 'password' => $passwords[0], 'account' => $accounts[1]);
$changeAccount  = array('name' => $names[3], 'url' => 'http://gitlab.com', 'token' => $tokens[1], 'password' => $passwords[0], 'account' => $accounts[2]);
$changePassword = array('name' => $names[4], 'url' => 'http://gitlab.com', 'token' => $tokens[0], 'password' => $passwords[1], 'account' => $accounts[2]);
$changeToken    = array('name' => $names[5], 'url' => 'http://gitlab.com', 'token' => $tokens[1], 'password' => $passwords[0], 'account' => $accounts[2]);
$emptyName      = array('name' => $names[0], 'url' => 'http://gitlab.com', 'token' => $tokens[1], 'password' => $passwords[0], 'account' => $accounts[2]);
$repeatName     = array('name' => $names[1], 'url' => 'http://gitlab.com', 'token' => $tokens[1], 'password' => $passwords[0], 'account' => $accounts[2]);
$emptyAccount   = array('name' => $names[6], 'url' => 'http://gitlab.com', 'token' => $tokens[1], 'password' => $passwords[0], 'account' => $accounts[0]);
$emptyToken     = array('name' => $names[8], 'url' => 'http://gitlab.com', 'token' => $tokens[0], 'password' => $passwords[0], 'account' => $accounts[1]);
$emptyPassword  = array('name' => $names[7], 'url' => 'http://gitlab.com', 'token' => $tokens[0], 'password' => $passwords[0], 'account' => $accounts[1]);

$pipelineTest = new pipelineTest();
r($pipelineTest->updateTest($idList[0], $changeName))     && p()                  && e('0');                                                                                              // 测试id为0时，修改名称
r($pipelineTest->updateTest($idList[1], $changeName))     && p('0:field,old,new') && e('name,gitLab,修改名称1');                                                                          // 测试id为1时，修改名称
r($pipelineTest->updateTest($idList[1], $changeAccount))  && p('1:field,old,new') && e('account,account,root');                                                                           // 测试id为1时，修改账号
r($pipelineTest->updateTest($idList[1], $changePassword)) && p('1:field,old,new') && e('password,~~,654321');                                                                             // 测试id为1时，修改password
r($pipelineTest->updateTest($idList[1], $changeToken))    && p('2:field,old,new') && e('token,~~,123456');                                                                                // 测试id为1时，修改token
r($pipelineTest->updateTest($idList[1], $emptyName))      && p('name:0')          && e('『应用名称』不能为空。');                                                                         // 测试id为1时，修改名称为空
r($pipelineTest->updateTest($idList[1], $repeatName))     && p('name:0')          && e('『应用名称』已经有『gitLab』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试id为1时，修改名称重复
r($pipelineTest->updateTest($idList[2], $emptyAccount))   && p('account:0')       && e('『用户名』不能为空。');                                                                           // 测试id为1时，修改账号为空
r($pipelineTest->updateTest($idList[2], $emptyToken))     && p('token:0')         && e('『Token』不能为空。');                                                                            // 测试id为1时，修改token为空
r($pipelineTest->updateTest($idList[2], $emptyPassword))  && p('password:0')      && e('『密码』不能为空。');                                                                             // 测试id为1时，修改password为空
