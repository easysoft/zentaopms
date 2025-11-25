#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getTableByAlias();
timeout=0
cid=15184

- 执行bi模块的getTableByAliasTest方法，参数是$statement1, 'u'  @zt_user
- 执行bi模块的getTableByAliasTest方法，参数是$statement2, 't'  @zt_task
- 执行bi模块的getTableByAliasTest方法，参数是$statement1, 'nonexistent'  @0
- 执行bi模块的getTableByAliasTest方法，参数是$statement3, 'p'  @zt_project
- 执行bi模块的getTableByAliasTest方法，参数是$emptyStatement, 'u'  @0

*/

su('admin');

$bi = new biTest();

// 测试1：from子句中的别名查找
$statement1 = new stdclass();
$fromInfo = new stdclass();
$fromInfo->alias = 'u';
$fromInfo->table = 'zt_user';
$statement1->from = array($fromInfo);
$statement1->join = null;

r($bi->getTableByAliasTest($statement1, 'u')) && p() && e('zt_user');

// 测试2：join子句中的别名查找
$statement2 = new stdclass();
$statement2->from = null;
$joinInfo = new stdclass();
$joinExpr = new stdclass();
$joinExpr->alias = 't';
$joinExpr->table = 'zt_task';
$joinInfo->expr = $joinExpr;
$statement2->join = array($joinInfo);

r($bi->getTableByAliasTest($statement2, 't')) && p() && e('zt_task');

// 测试3：查找不存在的别名
r($bi->getTableByAliasTest($statement1, 'nonexistent')) && p() && e('0');

// 测试4：复合查询中的别名查找（from中）
$statement3 = new stdclass();
$fromInfo3 = new stdclass();
$fromInfo3->alias = 'p';
$fromInfo3->table = 'zt_project';
$statement3->from = array($fromInfo3);
$joinInfo3 = new stdclass();
$joinExpr3 = new stdclass();
$joinExpr3->alias = 'u';
$joinExpr3->table = 'zt_user';
$joinInfo3->expr = $joinExpr3;
$statement3->join = array($joinInfo3);

r($bi->getTableByAliasTest($statement3, 'p')) && p() && e('zt_project');

// 测试5：空statement的情况
$emptyStatement = new stdclass();
$emptyStatement->from = null;
$emptyStatement->join = null;

r($bi->getTableByAliasTest($emptyStatement, 'u')) && p() && e('0');