#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
zdTable('project')->config('program')->gen(20)->fixpath();
su('admin');

/**

title=测试 programModel::hasUnfinished();
timeout=0
cid=1

*/

$programIdList = array(1, 2, 3);
$programTester = new programTest();

r($programTester->hasUnfinishedChildrenTest($programIdList[0])) && p() && e('1'); // 获取项目集1下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest($programIdList[1])) && p() && e('1'); // 获取项目集2下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest($programIdList[2])) && p() && e('0'); // 获取项目集3下未完成的项目和项目集数量
