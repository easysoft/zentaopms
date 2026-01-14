#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel->getActions();
cid=17305

- 获取 admin 角色 空 的地盘菜单 @5:index,10:calendar,15:work,20:audit,25:project,30:execution,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting

- 获取 user1 角色 qa 的地盘菜单 @5:index,10:calendar,20:audit,25:project,30:execution,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting,32:task

- 获取 user2 po 的地盘菜单 @5:index,10:calendar,20:audit,25:project,30:task,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement

- 获取 user3 pm 的地盘菜单 @5:index,10:calendar,20:audit,25:project,30:task,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement,17:myProject

- 获取 user4 pm 的地盘菜单 @5:index,10:calendar,20:audit,25:project,30:task,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement,17:myProject

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user_role')->gen('10');
$my = new myModelTest();

$accountList = array('admin', 'user1', 'user2', 'user3', 'user4');

r($my->setMenuTest($accountList[0])) && p() && e('5:index,10:calendar,15:work,20:audit,25:project,30:execution,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting');                    // 获取 admin 角色 空 的地盘菜单
r($my->setMenuTest($accountList[1])) && p() && e('5:index,10:calendar,20:audit,25:project,30:execution,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting,32:task');                    // 获取 user1 角色 qa 的地盘菜单
r($my->setMenuTest($accountList[2])) && p() && e('5:index,10:calendar,20:audit,25:project,30:task,35:contribute,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement'); // 获取 user2 po 的地盘菜单
r($my->setMenuTest($accountList[3])) && p() && e('5:index,10:calendar,20:audit,25:project,30:task,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement,17:myProject');  // 获取 user3 pm 的地盘菜单
r($my->setMenuTest($accountList[4])) && p() && e('5:index,10:calendar,20:audit,25:project,30:task,40:dynamic,50:contacts,11:effort,41:meeting,32:task,15:story,16:requirement,17:myProject');  // 获取 user4 pm 的地盘菜单
