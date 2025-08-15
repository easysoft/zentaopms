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

- 查看ID为1的待办的详细信息
 - 第1条的id属性 @1
 - 第1条的name属性 @自定义1的待办
 - 第1条的status属性 @wait
- 查看ID为2的待办的详细信息
 - 第2条的id属性 @2
 - 第2条的name属性 @BUG2的待办
 - 第2条的status属性 @doing

*/

initData();

global $tester;
$tester->loadModel('todo');

$todoIdList = array(1 => 1,2 => 2);
$todos      = $tester->todo->fetchRows($todoIdList);
r($tester->todo->fetchRows($todoIdList)) && p('1:id,name,status') && e('1,自定义1的待办,wait'); // 查看ID为1的待办的详细信息
r($tester->todo->fetchRows($todoIdList)) && p('2:id,name,status') && e('2,BUG2的待办,doing');  // 查看ID为2的待办的详细信息