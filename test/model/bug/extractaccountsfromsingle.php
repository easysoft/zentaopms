#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->extractAccountsFromSingle();
cid=1
pid=1

测试提取bug1的用户 >> admin;
测试提取bug2的用户 >> admin;
测试提取bug3的用户 >> admin;
测试提取bug4的用户 >> admin;
测试提取bug5的用户 >> admin;
测试提取bug6的用户 >> admin;
测试提取bug51的用户 >> admin;dev1
测试提取bug52的用户 >> admin;dev1
测试提取bug53的用户 >> admin;dev1
测试提取bug54的用户 >> admin;dev1
测试提取bug55的用户 >> admin;dev1
测试提取bug56的用户 >> admin;dev1
测试提取bug81的用户 >> admin;test1
测试提取bug82的用户 >> admin;test1
测试提取bug83的用户 >> admin;test1
测试提取bug84的用户 >> admin;test1
测试提取bug85的用户 >> admin;test1
测试提取bug86的用户 >> admin;test1

*/

$bugIDList1 = array('1', '2', '3');
$bugIDList2 = array('4', '5', '6');
$bugIDList3 = array('51', '52', '53');
$bugIDList4 = array('54', '55', '56');
$bugIDList5 = array('81', '82', '83');
$bugIDList6 = array('84', '85', '86');

$bug=new bugTest();
r($bug->extractAccountsFromSingleTest($bugIDList1[0])) && p('0;1') && e('admin;');      // 测试提取bug1的用户
r($bug->extractAccountsFromSingleTest($bugIDList1[1])) && p('0;1') && e('admin;');      // 测试提取bug2的用户
r($bug->extractAccountsFromSingleTest($bugIDList1[2])) && p('0;1') && e('admin;');      // 测试提取bug3的用户
r($bug->extractAccountsFromSingleTest($bugIDList2[0])) && p('0;1') && e('admin;');      // 测试提取bug4的用户
r($bug->extractAccountsFromSingleTest($bugIDList2[1])) && p('0;1') && e('admin;');      // 测试提取bug5的用户
r($bug->extractAccountsFromSingleTest($bugIDList2[2])) && p('0;1') && e('admin;');      // 测试提取bug6的用户
r($bug->extractAccountsFromSingleTest($bugIDList3[0])) && p('0;1') && e('admin;dev1');  // 测试提取bug51的用户
r($bug->extractAccountsFromSingleTest($bugIDList3[1])) && p('0;1') && e('admin;dev1');  // 测试提取bug52的用户
r($bug->extractAccountsFromSingleTest($bugIDList3[2])) && p('0;1') && e('admin;dev1');  // 测试提取bug53的用户
r($bug->extractAccountsFromSingleTest($bugIDList4[0])) && p('0;1') && e('admin;dev1');  // 测试提取bug54的用户
r($bug->extractAccountsFromSingleTest($bugIDList4[1])) && p('0;1') && e('admin;dev1');  // 测试提取bug55的用户
r($bug->extractAccountsFromSingleTest($bugIDList4[2])) && p('0;1') && e('admin;dev1');  // 测试提取bug56的用户
r($bug->extractAccountsFromSingleTest($bugIDList5[0])) && p('0;1') && e('admin;test1'); // 测试提取bug81的用户
r($bug->extractAccountsFromSingleTest($bugIDList5[1])) && p('0;1') && e('admin;test1'); // 测试提取bug82的用户
r($bug->extractAccountsFromSingleTest($bugIDList5[2])) && p('0;1') && e('admin;test1'); // 测试提取bug83的用户
r($bug->extractAccountsFromSingleTest($bugIDList6[0])) && p('0;1') && e('admin;test1'); // 测试提取bug84的用户
r($bug->extractAccountsFromSingleTest($bugIDList6[1])) && p('0;1') && e('admin;test1'); // 测试提取bug85的用户
r($bug->extractAccountsFromSingleTest($bugIDList6[2])) && p('0;1') && e('admin;test1'); // 测试提取bug86的用户