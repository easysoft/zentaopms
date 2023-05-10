#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('checktopstage')->gen(5);

/**

title=测试programplanModel->checkTopStage();
cid=1
pid=1

*/

$plan = new programplanTest();

r($plan->checkTopStageTest(2)) && p('') && e('1'); // 测试id为2判断是否为顶级阶段
r($plan->checkTopStageTest(4)) && p('') && e('0'); // 测试id为4判断是否为顶级阶段
r($plan->checkTopStageTest(5)) && p('') && e('1'); // 测试id为5判断是否为顶级阶段
