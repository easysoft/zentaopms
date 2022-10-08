#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getPlanStories();
cid=1
pid=1

获取计划1下的需求数量，每页10条 >> 10
获取计划1下的需求数量，不分页 >> 20
获取计划1下的需求列表，每页10条 >> 软件需求290,active,story
获取计划1下的需求列表，不分页 >> 软件需求2,active,story

*/

global $tester;
$tester->loadModel('story');
$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);

$plan1Stories = $tester->story->getPlanStories(1, 'all', 'id_desc', $pager);
$plan2Stories = $tester->story->getPlanStories(1, 'all', 'id_desc');

r(count($plan1Stories)) && p()                        && e('10'); //获取计划1下的需求数量，每页10条
r(count($plan2Stories)) && p()                        && e('20'); //获取计划1下的需求数量，不分页
r($plan1Stories)        && p('290:title,status,type') && e('软件需求290,active,story'); //获取计划1下的需求列表，每页10条
r($plan2Stories)        && p('2:title,status,type')   && e('软件需求2,active,story');   //获取计划1下的需求列表，不分页