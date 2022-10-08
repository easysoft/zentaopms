#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->addProjectMembersTest();
cid=1
pid=1

敏捷项目根据执行添加团队信息 >> po82,研发
瀑布项目根据执行添加团队信息 >> test22,测试
看板项目根据执行添加团队信息 >> test52,测试
敏捷项目根据执行添加团队信息统计 >> 2
瀑布项目根据执行添加团队信息统计 >> 1
看板项目根据执行添加团队信息统计 >> 1

*/

$projectIDList   = array('11', '41', '71');
$executionIDList = array('101', '131', '161', '1611');
$count           = array('0','1');

$execution = new executionTest();
r($execution->addProjectMembersTest($projectIDList[0], $executionIDList[0], $count[0])) && p('0:account,role') && e('po82,研发');   // 敏捷项目根据执行添加团队信息
r($execution->addProjectMembersTest($projectIDList[1], $executionIDList[1], $count[0])) && p('0:account,role') && e('test22,测试'); // 瀑布项目根据执行添加团队信息
r($execution->addProjectMembersTest($projectIDList[2], $executionIDList[2], $count[0])) && p('0:account,role') && e('test52,测试'); // 看板项目根据执行添加团队信息
r($execution->addProjectMembersTest($projectIDList[0], $executionIDList[0], $count[1])) && p()                 && e('2');           // 敏捷项目根据执行添加团队信息统计
r($execution->addProjectMembersTest($projectIDList[1], $executionIDList[1], $count[1])) && p()                 && e('1');           // 瀑布项目根据执行添加团队信息统计
r($execution->addProjectMembersTest($projectIDList[2], $executionIDList[2], $count[1])) && p()                 && e('1');           // 看板项目根据执行添加团队信息统计
$db->restoreDB();