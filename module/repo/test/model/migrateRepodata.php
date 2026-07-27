#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->migrateRepoData() 边界场景;
timeout=0
cid=0

- 旧 repo 表不存在时属性result,error @fail,SQLSTATE[42S02]: Base table or view not found: 1146 Table 'unittest.zt_repo' doesn't exist
- 预置旧 repo 数据后可正常迁移属性result,error @success,none
- 仅清理新表数据时再次迁移命中主键冲突属性result,error @fail,SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '99996' for key 'PRIMARY'
- 初始化并清理测试数据后仍可迁移属性result,error @success,none
- 使用另一组测试 repo ID 重新初始化迁移属性result,error @success,none

*/

zenData('ops_repo')->gen(0);

$repo = new repoModelTest();

r($repo->migrateRepoDataTest(false, false, 0))     && p('result,error') && e('fail,SQLSTATE[42S02]: Base table or view not found: 1146 Table \'unittest.zt_repo\' doesn\'t exist');
r($repo->migrateRepoDataTest(true, false, 99996))  && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(false, true, 99996))  && p('result,error') && e('fail,SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'99996\' for key \'PRIMARY\'');
r($repo->migrateRepoDataTest(true, true, 99995))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(true, true, 99994))   && p('result,error') && e('success,none');
