#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->update();
cid=1
pid=1

编辑用户需求，判断返回的信息，stage为空 >> 2,4,1,测试来源备注1,2
编辑软件需求，判断返回的信息，stage为wait，parent为2 >> 2,4,1,测试来源备注1,2

*/

$story  = new storyTest();
$story1['parent']     = 2;
$story1['pri']        = 4;
$story1['estimate']   = 1;
$story1['sourceNote'] = '测试来源备注1';
$story1['product']    = 2;

$result1 = $story->updateTest(1, $story1);
$result2 = $story->updateTest(3, $story1);

r($result1) && p('parent,pri,estimate,sourceNote,product') && e('2,4,1,测试来源备注1,2'); // 编辑用户需求，判断返回的信息，stage为空
r($result2) && p('parent,pri,estimate,sourceNote,product') && e('2,4,1,测试来源备注1,2'); // 编辑软件需求，判断返回的信息，stage为wait，parent为2 
