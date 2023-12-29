#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$compile = zdTable('compile');
$compile->status->range('``,success,failure,created');
$compile->deleted->range('0,1');
$compile->gen(10);
su('admin');

/**

title=测试 compileModel->getUnexecutedList();
cid=1
pid=1

- 检查返回结果第二条数据的字段内容。
 - 第1条的name属性 @构建5
 - 第1条的status属性 @~~
- 检查返回结果的数量。 @3

*/

$tester->loadModel('compile');
r($tester->compile->getUnexecutedList())        && p('1:name,status') && e('构建5,~~'); //检查返回结果第二条数据的字段内容。
r(count($tester->compile->getUnexecutedList())) && p()         && e('3');               //检查返回结果的数量。
