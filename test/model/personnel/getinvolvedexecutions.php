#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getInvolvedExecutions();
cid=1
pid=1

涉及到执行的人员计数 >> 8
取出其中一个 >> 1
传入不存在的id >> 0

*/

$personnel = new personnelTest('admin');

$project = array();
$project[0] = array(11, 12);
$project[1] = 12222;

$result1 = count($personnel->getInvolvedExecutionsTest($project[0]));
$result2 = $personnel->getInvolvedExecutionsTest($project[0]);
$result3 = $personnel->getInvolvedExecutionsTest($project[1]);

r($result1) && p()       && e('8'); //涉及到执行的人员计数
r($result2) && p('pm72') && e('1'); //取出其中一个
r($result3) && p()       && e('0'); //传入不存在的id