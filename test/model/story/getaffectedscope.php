#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getAffectedScope();
cid=1
pid=1

获取需求1影响任务的数量 >> 6
获取需求15影响任务的数量 >> 0
查看返回的需求1的title >> 用户需求版本三41
查看返回的需求15的title >> 软件需求版本三55
查看需求100的影响的迭代的名字 >> 迭代25

*/

$story = new storyTest();
$affectedScope1 = $story->getAffectedScopeTest(1);
$affectedScope2 = $story->getAffectedScopeTest(15);
$affectedScope3 = $story->getAffectedScopeTest(100);

r(count($affectedScope1->tasks)) && p()           && e('6');                //获取需求1影响任务的数量
r(count($affectedScope2->tasks)) && p()           && e('0');                //获取需求15影响任务的数量
r($affectedScope1)               && p('title')    && e('用户需求版本三41'); //查看返回的需求1的title
r($affectedScope2)               && p('title')    && e('软件需求版本三55'); //查看返回的需求15的title
r($affectedScope3->executions)   && p('125:name') && e('迭代25');           //查看需求100的影响的迭代的名字