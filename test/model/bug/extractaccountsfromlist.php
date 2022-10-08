#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->extractAccountsFromList();
cid=1
pid=1

测试提取bug1 2 3的用户 >> admin;
测试提取bug4 5 6的用户 >> admin;
测试提取bug51 52 53的用户 >> admin;dev1
测试提取bug54 55 56的用户 >> admin;dev1
测试提取bug81 82 83的用户 >> admin;test1
测试提取bug84 85 86的用户 >> admin;test1

*/

$bugIDList1 = array('1', '2', '3');
$bugIDList2 = array('4', '5', '6');
$bugIDList3 = array('51', '52', '53');
$bugIDList4 = array('54', '55', '56');
$bugIDList5 = array('81', '82', '83');
$bugIDList6 = array('84', '85', '86');

$bug=new bugTest();
r($bug->extractAccountsFromListTest($bugIDList1)) && p('0;1') && e('admin;');      // 测试提取bug1 2 3的用户
r($bug->extractAccountsFromListTest($bugIDList2)) && p('0;1') && e('admin;');      // 测试提取bug4 5 6的用户
r($bug->extractAccountsFromListTest($bugIDList3)) && p('0;1') && e('admin;dev1');  // 测试提取bug51 52 53的用户
r($bug->extractAccountsFromListTest($bugIDList4)) && p('0;1') && e('admin;dev1');  // 测试提取bug54 55 56的用户
r($bug->extractAccountsFromListTest($bugIDList5)) && p('0;1') && e('admin;test1'); // 测试提取bug81 82 83的用户
r($bug->extractAccountsFromListTest($bugIDList6)) && p('0;1') && e('admin;test1'); // 测试提取bug84 85 86的用户