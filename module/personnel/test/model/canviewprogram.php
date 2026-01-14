#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('program')->gen(3);
zenData('user')->loadYaml('user')->gen(3);
zenData('group')->loadYaml('group')->gen(3);
zenData('usergroup')->loadYaml('usergroup')->gen(3);

/**

title=测试 personnelModel->canViewProgram();
cid=17319

- 看admin是否具有访问项目集1权限 @1
- 看admin是否具有访问项目集2权限 @1
- 看admin是否具有访问项目集10000权限 @1
- 看dev1是否具有访问项目集1权限 @1
- 看dev1是否具有访问项目集2权限 @0
- 看dev1是否具有访问项目集10000权限 @0
- 看dev2是否具有访问项目集1权限 @0
- 看dev2是否具有访问项目集2权限 @1
- 看dev2是否具有访问项目集10000权限 @0

*/

$personnel = new personnelModelTest();

$programID = array(1, 2, 10000);
$account   = array('admin', 'dev1', 'dev2');

r($personnel->canViewProgramTest($programID[0], $account[0])) && p() && e('1');     //看admin是否具有访问项目集1权限
r($personnel->canViewProgramTest($programID[1], $account[0])) && p() && e('1');     //看admin是否具有访问项目集2权限
r($personnel->canViewProgramTest($programID[2], $account[0])) && p() && e('1');     //看admin是否具有访问项目集10000权限

r($personnel->canViewProgramTest($programID[0], $account[1])) && p() && e('1');     //看dev1是否具有访问项目集1权限
r($personnel->canViewProgramTest($programID[1], $account[1])) && p() && e('0');     //看dev1是否具有访问项目集2权限
r($personnel->canViewProgramTest($programID[2], $account[1])) && p() && e('0');     //看dev1是否具有访问项目集10000权限

r($personnel->canViewProgramTest($programID[0], $account[2])) && p() && e('0');     //看dev2是否具有访问项目集1权限
r($personnel->canViewProgramTest($programID[1], $account[2])) && p() && e('1');     //看dev2是否具有访问项目集2权限
r($personnel->canViewProgramTest($programID[2], $account[2])) && p() && e('0');     //看dev2是否具有访问项目集10000权限
