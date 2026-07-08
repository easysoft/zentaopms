#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* 清空 bug 表，避免历史数据干扰 */
global $dao;
$dao->exec('TRUNCATE TABLE ' . TABLE_BUG);

su('admin');

/**

title=测试 repoModel::saveBug();
timeout=0
cid=0

- 正常 bug 写入返回 success 并能从库中查到 @id=1
- product 字段缺失校验失败 @fail
- title 字段缺失校验失败 @fail
- 携带 execution=0 走非空分支正常写入 @openedBy=admin
- 第二条正常写入 id 自增 @id=2

*/

$tester = new repoModelTest();

$bug1 = array('product' => 1, 'title' => 'Bug 1 from repo', 'openedBy' => 'admin');
r($tester->saveBugTest(1, $bug1))                                                                              && p('id,openedBy') && e('1,admin');   // 步骤1：正常写入
r($tester->saveBugTest(1, array('product' => 0, 'title' => 'no product')))                                     && p()              && e('fail');      // 步骤2：缺 product
r($tester->saveBugTest(1, array('product' => 1, 'title' => '')))                                               && p()              && e('fail');      // 步骤3：缺 title
r($tester->saveBugTest(1, array('product' => 1, 'title' => 'execution zero', 'execution' => 0)))               && p('openedBy')    && e('admin');     // 步骤4：execution=0 分支
r($tester->saveBugTest(1, array('product' => 2, 'title' => 'second bug', 'openedBy' => 'admin')))              && p('id,product')  && e('3,2');       // 步骤5：自增 id
