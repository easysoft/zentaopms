#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->getByList();
cid=1
pid=1

*/

$todoIDList1 = array('1');
$todoIDList2 = array('1', '2', '3', '4');
$todoIDList3 = array('9', '10', '11', '12'); /* Test not existed items. */
$todoIDList4 = array(); /* Test empty todoIDList. */

$todo = new todoTest();

r($todo->getByListTest($todoIDList1)) && p() && e('1');
r($todo->getByListTest($todoIDList2)) && p() && e('1234');
r($todo->getByListTest($todoIDList3)) && p() && e('');
r($todo->getByListTest($todoIDList4)) && p() && e('12345');
