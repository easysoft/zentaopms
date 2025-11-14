#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);

/**

title=测试 commonTao->setPreferenceMenu();
timeout=0
cid=0

- 查看admin是否可以打印执行列表的菜单
 -  @1
 - 属性1 @all
- 查看user1是否可以打印项目集列表的菜单
 -  @~~
 - 属性1 @browse
- 查看user1是否可以打印执行任务的菜单
 -  @~~
 - 属性1 @task

*/
$result1 = commonTao::setPreferenceMenu(false, 'execution', 'all');

su('user1');
$result2 = commonTao::setPreferenceMenu(false, 'program', 'browse');
$result3 = commonTao::setPreferenceMenu(false, 'execution', 'task');

r($result1) && p('0,1') && e('1,all');     // 查看admin是否可以打印执行列表的菜单
r($result2) && p('0,1') && e('~~,browse'); // 查看user1是否可以打印项目集列表的菜单
r($result3) && p('0,1') && e('~~,task');   // 查看user1是否可以打印执行任务的菜单