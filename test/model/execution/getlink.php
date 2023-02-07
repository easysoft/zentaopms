#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 executionModel::getLink();
cid=1
pid=1

测试获取任务详情页链接 >> model/execution/execution-task-%s.

*/

global $tester;
$executionTester = $tester->loadModel('execution');

r($executionTester->getLink('task', 'view', '')) && p() && e('model/execution/execution-task-%s.'); // 测试获取任务详情页链接
