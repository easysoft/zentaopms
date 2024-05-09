#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData ()
{
    zenData('todo')->gen(5);
}

/**

title=测试 todoTao::fetchRows();
timeout=0
cid=1

- 执行todo模块的fetchRows方法，参数是$todoIdList第1条的name属性 @自定义1的待办

*/

initData();

global $tester;
$tester->loadModel('todo');

$todoIdList = array(1,2);
r($tester->todo->fetchRows($todoIdList)) && p('1:name') && e('自定义1的待办');