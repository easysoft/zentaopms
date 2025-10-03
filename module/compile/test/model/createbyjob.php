#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel::createByJob();
timeout=0
cid=15743

- 执行compileTest模块的createByJobTest方法，参数是1, 'v1.0.0', 'tag'
 - 属性name @Job1
 - 属性job @1
 - 属性tag @v1.0.0
 - 属性createdBy @admin
- 执行compileTest模块的createByJobTest方法，参数是2, 'abc123', 'commit'
 - 属性name @Job2
 - 属性job @2
 - 属性commit @abc123
 - 属性createdBy @admin
- 执行compileTest模块的createByJobTest方法，参数是3, '', 'tag'
 - 属性name @Job3
 - 属性job @3
 - 属性tag @
- 执行compileTest模块的createByJobTest方法，参数是999, 'test', 'tag'  @alse
- 执行compileTest模块的createByJobTest方法，参数是4, 'branch-dev', 'branch'
 - 属性name @Job4
 - 属性job @4
 - 属性branch @branch-dev

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