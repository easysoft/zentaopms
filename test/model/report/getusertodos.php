#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserTodos();
cid=1
pid=1

测试获取用户待办数 >> admin:2;
测试获取用户待办数 >> dev1:2;dev2:2;dev5:2;dev6:2;dev9:2;dev10:2;      
测试获取用户待办数 >> test1:2;test2:2;test5:2;test6:2;test9:2;test10:2;
测试获取用户待办数 >> user4:2;user5:2;user8:2;user9:2;
测试获取用户待办数 >> pm1:2;pm2:2;pm5:2;pm6:2;pm9:2;pm10:2;
测试获取用户待办数 >> po1:2;po2:2;po5:2;po6:2;po9:2;po10:2;

*/

$userType = array('admin', 'dev', 'test', 'user', 'pm', 'po');

$report = new reportTest();

r($report->getUserTodosTest($userType[0])) && p() && e('admin:2;');                                          // 测试获取用户待办数
r($report->getUserTodosTest($userType[1])) && p() && e('dev1:2;dev2:2;dev5:2;dev6:2;dev9:2;dev10:2;      '); // 测试获取用户待办数
r($report->getUserTodosTest($userType[2])) && p() && e('test1:2;test2:2;test5:2;test6:2;test9:2;test10:2;'); // 测试获取用户待办数
r($report->getUserTodosTest($userType[3])) && p() && e('user4:2;user5:2;user8:2;user9:2;');                  // 测试获取用户待办数
r($report->getUserTodosTest($userType[4])) && p() && e('pm1:2;pm2:2;pm5:2;pm6:2;pm9:2;pm10:2;');             // 测试获取用户待办数
r($report->getUserTodosTest($userType[5])) && p() && e('po1:2;po2:2;po5:2;po6:2;po9:2;po10:2;');             // 测试获取用户待办数