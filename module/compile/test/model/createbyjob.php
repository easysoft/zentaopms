#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel::createByJob();
timeout=0
cid=15743

- 根据ID为1的job生成compile
 - 属性name @这是一个Job1
 - 属性job @1
 - 属性tag @v1.0.0
 - 属性createdBy @admin
- 根据ID为2的job生成compile
 - 属性name @abc123
 - 属性job @2
 - 属性createdBy @admin
- 根据ID为3的job生成compile
 - 属性name @这是一个Job3
 - 属性job @3
 - 属性tag @~~
- 根据ID为999的job生成compile @0
- 根据ID为4的job生成compile
 - 属性name @这是一个Job4
 - 属性job @4
 - 属性branch @dev

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$compile = zenData('compile');
$compile->gen(0);

$job = zenData('job');
$job->loadYaml('job_createbyjob', false, 2)->gen(5);

su('admin');

$compileTest = new compileModelTest();

r($compileTest->createByJobTest(1,   'v1.0.0', 'tag'))    && p('name,job,tag,createdBy') && e('这是一个Job1,1,v1.0.0,admin'); // 根据ID为1的job生成compile
r($compileTest->createByJobTest(2,   'abc123', 'name'))   && p('name,job,createdBy')     && e('abc123,2,admin');              // 根据ID为2的job生成compile
r($compileTest->createByJobTest(3,   '',       'tag'))    && p('name,job,tag')           && e('这是一个Job3,3,~~');           // 根据ID为3的job生成compile
r($compileTest->createByJobTest(999, 'test',   'tag'))    && p()                         && e(0);                             // 根据ID为999的job生成compile
r($compileTest->createByJobTest(4,   'dev',    'branch')) && p('name,job,branch')        && e('这是一个Job4,4,dev');          // 根据ID为4的job生成compile
