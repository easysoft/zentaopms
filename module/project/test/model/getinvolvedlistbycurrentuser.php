#!/usr/bin/env php
<?php

/**

title=测试 projectModel::getInvolvedListByCurrentUser();
timeout=0
cid=0

- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.*'  @5
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.id, t1.name' 
 - 第1条的id属性 @1
 - 第1条的1:name属性 @项目1
 - 第2条的id属性 @2
 - 第2条的2:name属性 @项目2
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法 
 - 第1条的name属性 @项目1
 - 第1条的2:name属性 @项目2
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @3
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project_getinvolvedlistbycurrentuser', false, 2)->gen(10);
zenData('team')->loadYaml('team_getinvolvedlistbycurrentuser', false, 2)->gen(15);
zenData('stakeholder')->loadYaml('stakeholder_getinvolvedlistbycurrentuser', false, 2)->gen(10);

su('admin');

$projectTest = new Project();

r($projectTest->getInvolvedListByCurrentUserTest('t1.*')) && p() && e('5');
r($projectTest->getInvolvedListByCurrentUserTest('t1.id,t1.name')) && p('1:id,1:name;2:id,2:name') && e('1,项目1;2,项目2');
r($projectTest->getInvolvedListByCurrentUserTest()) && p('1:name,2:name') && e('项目1,项目2');
su('user1');
r($projectTest->getInvolvedListByCurrentUserTest()) && p() && e('3');
su('testuser');
r($projectTest->getInvolvedListByCurrentUserTest()) && p() && e('2');