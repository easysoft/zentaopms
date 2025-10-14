#!/usr/bin/env php
<?php

/**

title=测试 searchModel::getOldQuery();
timeout=0
cid=0

- 执行searchTest模块的getOldQueryTest方法，参数是1 属性id @1
- 执行searchTest模块的getOldQueryTest方法，参数是999  @0
- 执行searchTest模块的getOldQueryTest方法，参数是2 属性id @2
- 执行searchTest模块的getOldQueryTest方法，参数是3 第form条的module属性 @bug
- 执行searchTest模块的getOldQueryTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

global $tester;

// 直接准备测试数据，避免zenData问题
$tester->dao->delete()->from(TABLE_USERQUERY)->exec();

// 插入测试数据
$testData = array(
    array('id' => 1, 'account' => 'admin', 'module' => 'bug', 'title' => '查询标题1', 'form' => serialize(array('module' => 'bug', 'field1' => 'title')), 'sql' => '1 = 1', 'shortcut' => 0, 'common' => 0),
    array('id' => 2, 'account' => 'admin', 'module' => 'task', 'title' => '查询标题2', 'form' => serialize(array('module' => 'task')), 'sql' => 'title like "%test%"', 'shortcut' => 0, 'common' => 0),
    array('id' => 3, 'account' => 'admin', 'module' => 'bug', 'title' => '查询标题3', 'form' => serialize(array('module' => 'bug', 'field1' => 'type')), 'sql' => 'status = "active"', 'shortcut' => 0, 'common' => 0),
    array('id' => 4, 'account' => 'user1', 'module' => 'bug', 'title' => '查询标题4', 'form' => serialize(array('module' => 'bug', '$test' => 'value')), 'sql' => 'id > 0 AND title like "%$today%"', 'shortcut' => 1, 'common' => 1)
);

foreach($testData as $data) {
    $tester->dao->insert(TABLE_USERQUERY)->data($data)->exec();
}

su('admin');

$searchTest = new searchTest();

r($searchTest->getOldQueryTest(1)) && p('id') && e('1');
r($searchTest->getOldQueryTest(999)) && p() && e('0');
r($searchTest->getOldQueryTest(2)) && p('id') && e('2');
r($searchTest->getOldQueryTest(3)) && p('form:module') && e('bug');
r($searchTest->getOldQueryTest(0)) && p() && e('0');