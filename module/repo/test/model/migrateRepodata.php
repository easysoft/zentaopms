#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->migrateRepoData() 边界场景;
timeout=0
cid=0

- 执行repo模块的migrateRepoDataTest方法，参数是false, false, 0
 - 属性result @fail
 - 属性error @SQLSTATE[42S02]: Base table or view not found: 1146 Table 'unittest.zt_repo' doesn't exist
- 执行repo模块的migrateRepoDataTest方法，参数是true, false, 99996
 - 属性result @success
 - 属性error @none
- 执行repo模块的migrateRepoDataTest方法，参数是false, true, 99996
 - 属性result @fail
 - 属性error @SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '99996' for key 'PRIMARY'
- 执行repo模块的migrateRepoDataTest方法，参数是true, true, 99995
 - 属性result @success
 - 属性error @none
- 执行repo模块的migrateRepoDataTest方法，参数是true, true, 99994
 - 属性result @success
 - 属性error @none

*/

zenData('ops_provider')->gen(0);
zenData('ops_repo')->gen(0);

$repo = new repoModelTest();

r($repo->migrateRepoDataTest(false, false, 0))     && p('result,error') && e("fail,SQLSTATE[42S02]: Base table or view not found: 1146 Table 'unittest.zt_repo' doesn't exist");
r($repo->migrateRepoDataTest(true, false, 99996))  && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(false, true, 99996))  && p('result,error') && e("fail,SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '99996' for key 'PRIMARY'");
r($repo->migrateRepoDataTest(true, true, 99995))   && p('result,error') && e('success,none');
r($repo->migrateRepoDataTest(true, true, 99994))   && p('result,error') && e('success,none');
