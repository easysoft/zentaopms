#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 todoTao::updateDate();
timeout=0
cid=1

- 判断是否更新为当前的时间 @1

- 判断是否更新为当前的时间 @1

- 判断是否更新为当前的时间 @1

*/

function initData ()
{
    zenData('todo')->loadYaml('updatedate')->gen(5);
}

initData();

global $tester;
$tester->loadModel('todo')->todoTao;

$todoDate1   = date('Y-m-d');
$todoDate2   = date('Y-m-d H:i');

$todoIDList1 = array(1);
$todoIDList2 = array(2, 3);
$todoIDList3 = array('id' => 4);
$todoIDList4 = array('id' => 5, 'account' => 'admin');

r($tester->todo->updateDate($todoIDList1, $todoDate1)) && p() && e('1'); // 判断是否更新为当前的时间
r($tester->todo->updateDate($todoIDList2, $todoDate1)) && p() && e('1'); // 判断是否更新为当前的时间
r($tester->todo->updateDate($todoIDList3, $todoDate2)) && p() && e('1'); // 判断是否更新为当前的时间
r($tester->todo->updateDate($todoIDList4, $todoDate2)) && p() && e('1'); // 判断是否更新为当前的时间
