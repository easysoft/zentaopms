#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->config('execution')->gen(20);
zdTable('team')->config('team')->gen(20);

/**

title=测试 personnelModel->getInvolvedExecutions();
cid=1
pid=1

涉及到执行的人员计数 >> 2
取出其中一个 >> 1
传入不存在的id >> 0

*/

$personnel = new personnelTest('admin');

$project = array(array(11, 12), array(10000));

$result1 = count($personnel->getInvolvedExecutionsTest($project[0]));
$result2 = $personnel->getInvolvedExecutionsTest($project[0]);
$result3 = $personnel->getInvolvedExecutionsTest($project[1]);

r($result1) && p()        && e('2'); //涉及到执行的人员计数
r($result2) && p('admin') && e('1'); //取出其中一个
r($result3) && p()        && e('0'); //传入不存在的id
