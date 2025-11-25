#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getByID();
timeout=0
cid=15746

- 执行compileTest模块的getByIDTest方法，参数是1
 - 属性id @1
 - 属性name @构建1
 - 属性status @success
- 执行compileTest模块的getByIDTest方法，参数是999  @alse
- 执行compileTest模块的getByIDTest方法  @alse
- 执行compileTest模块的getByIDTest方法，参数是-1  @alse
- 执行compileTest模块的getByIDTest方法，参数是'abc'  @alse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$table = zenData('compile');
$table->id->range('1-5');
$table->name->range('构建1,构建2,构建3,构建4,构建5');
$table->job->range('1-5');
$table->queue->range('101-105');
$table->status->range('success,failure,running,pending,created');
$table->branch->range('master,develop,feature,hotfix,release');
$table->logs->range('null,null,null,null,null');
$table->atTime->range('0800,0900,1000,1100,1200');
$table->testtask->range('0,1,2,3,0');
$table->tag->range('v1.0,v1.1,v1.2,v1.3,v1.4');
$table->times->range('1,2,3,1,2');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-10-01 10:00:00`,`2023-10-02 10:00:00`,`2023-10-03 10:00:00`,`2023-10-04 10:00:00`,`2023-10-05 10:00:00`');
$table->updateDate->range('null,null,null,null,null');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$compileTest = new compileTest();

r($compileTest->getByIDTest(1)) && p('id,name,status') && e('1,构建1,success');
r($compileTest->getByIDTest(999)) && p() && e(false);
r($compileTest->getByIDTest(0)) && p() && e(false);
r($compileTest->getByIDTest(-1)) && p() && e(false);
r($compileTest->getByIDTest('abc')) && p() && e(false);