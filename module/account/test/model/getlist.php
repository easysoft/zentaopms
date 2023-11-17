#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('user')->gen(5);
zdTable('account')->gen(10);
zdTable('userquery')->gen(1);

/**

title=accountModel->getList();
timeout=0
cid=1

*/

global $tester;
$accountModel = $tester->loadModel('account');
r(count($accountModel->getList())) && p() && e('10');                // 获取所有数据。
r(count($accountModel->getList('bysearch', '0'))) && p() && e('10'); // 根据搜索条件搜索。
r(count($accountModel->getList('bysearch', '1'))) && p() && e('0');  // 根据第一个保存条件搜索。

$accountModel->app->loadClass('pager', $static = true);
$pager = new pager(0, 5, 1);
r(count($accountModel->getList('all', '', 'id_desc', $pager))) && p() && e('5'); // 分页，每页5条，获取第一页。
