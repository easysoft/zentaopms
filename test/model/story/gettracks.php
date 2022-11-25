#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getTracks();
cid=1
pid=1

获取产品1的跟踪矩阵的数量 >> 2
获取产品1的需求2的关联bug数量 >> 4
获取产品1的需求2的关联用例数量 >> 4
获取产品1的需求2的关联Bug名称 >> BUG1
获取产品1的需求2的关联Bug名称 >> BUG101
获取产品1的需求2的关联Case名称 >> 这个是测试用例1
获取产品1的需求2的关联Case名称 >> 这个是测试用例2

*/

global $tester;
$tester->loadModel('story');

$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 100, 1);

$tracks = $tester->story->getTracks(1, 0, 0, $pager);

r(count($tracks['noRequirement']))           && p()            && e('2');               // 获取产品1的跟踪矩阵的数量
r(count($tracks['noRequirement'][2]->bugs))  && p()            && e('4');               // 获取产品1的需求2的关联bug数量
r(count($tracks['noRequirement'][2]->cases)) && p()            && e('4');               // 获取产品1的需求2的关联用例数量
r($tracks['noRequirement'][2]->bugs)         && p('1:title')   && e('BUG1');            // 获取产品1的需求2的关联Bug名称
r($tracks['noRequirement'][2]->bugs)         && p('101:title') && e('BUG101');          // 获取产品1的需求2的关联Bug名称
r($tracks['noRequirement'][2]->cases)        && p('1:title')   && e('这个是测试用例1'); // 获取产品1的需求2的关联Case名称
r($tracks['noRequirement'][2]->cases)        && p('2:title')   && e('这个是测试用例2'); // 获取产品1的需求2的关联Case名称