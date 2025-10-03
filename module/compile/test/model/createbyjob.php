#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel::createByJob();
cid=0

- 测试步骤1：正常输入情况 >> 期望正常结果
- 测试步骤2：边界值输入 >> 期望边界处理结果
- 测试步骤3：无效输入情况 >> 期望错误处理结果
- 测试步骤4：权限验证情况 >> 期望权限控制结果
- 测试步骤5：业务规则验证 >> 期望业务逻辑结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$compile = zenData('compile');
$compile->gen(0);

$job = zenData('job');
$job->loadYaml('job_createbyjob', false, 2)->gen(5);

$company = zenData('company');
$company->name->range('ZenTao');
$company->code->range('zentao');
$company->gen(1);

su('admin');

$compileTest = new compileTest();

r($compileTest->createByJobTest(1, 'v1.0.0', 'tag')) && p('name,job,tag,createdBy') && e('Job1,1,v1.0.0,admin');
r($compileTest->createByJobTest(2, 'abc123', 'commit')) && p('name,job,commit,createdBy') && e('Job2,2,abc123,admin');
r($compileTest->createByJobTest(3, '', 'tag')) && p('name,job,tag') && e('Job3,3,');
r($compileTest->createByJobTest(999, 'test', 'tag')) && p() && e(false);
r($compileTest->createByJobTest(4, 'branch-dev', 'branch')) && p('name,job,branch') && e('Job4,4,branch-dev');