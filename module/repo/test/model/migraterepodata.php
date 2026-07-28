#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->migrateRepoData();
timeout=0
cid=18121

- 初始化旧 repo 数据后第一次迁移属性result,error @success,none
- 同一 repoID 再次初始化迁移仍可成功属性result,error @success,none
- 同一 repoID 第三次初始化迁移仍可成功属性result,error @success,none
- 切换另一个 repoID 初始化迁移属性result,error @success,none
- 未初始化旧 repo 表时属性result,error @fail,SQLSTATE[42S02]: Base table or view not found: 1146 Table 'unittest.zt_repo' doesn't exist

*/

zenData('ops_repo')->gen(0);

$repo = new repoModelTest();

r($repo->migrateRepoDataTest(true, true, 99998))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(true, true, 99998))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(true, true, 99998))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(true, true, 99997))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(false, false, 0))     && p('result,error') && e('fail,SQLSTATE[42S02]: Base table or view not found: 1146 Table \'unittest.zt_repo\' doesn\'t exist');
