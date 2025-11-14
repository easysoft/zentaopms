#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel->appendOrder();
timeout=0
cid=15644

- 正序追加name排序 @status_asc,name_asc

- 倒序追加name排序 @status_desc,name_desc

- 正序追加id排序 @account_asc,id_asc

- 倒序追加id排序 @account_desc,id_desc

- 默认值追加id排序 @account,id_asc

*/

global $tester;
$tester->loadModel('common');

$order1 = $tester->common->appendOrder('status_asc',  'name');
$order2 = $tester->common->appendOrder('status_desc', 'name');
$order3 = $tester->common->appendOrder('account_asc',  'id');
$order4 = $tester->common->appendOrder('account_desc', 'id');
$order5 = $tester->common->appendOrder('account');

r($order1) && p() && e('status_asc,name_asc');   // 正序追加name排序
r($order2) && p() && e('status_desc,name_desc'); // 倒序追加name排序
r($order3) && p() && e('account_asc,id_asc');    // 正序追加id排序
r($order4) && p() && e('account_desc,id_desc');  // 倒序追加id排序
r($order5) && p() && e('account,id_asc');        // 默认值追加id排序