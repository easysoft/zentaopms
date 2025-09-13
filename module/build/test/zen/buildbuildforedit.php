#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildBuildForEdit();
timeout=0
cid=0

- 执行buildTest模块的buildBuildForEditTest方法，参数是1 
 - 属性name @版本1
 - 属性execution @101
 - 属性hasExecution @1
- 执行buildTest模块的buildBuildForEditTest方法，参数是2 
 - 属性name @版本2
 - 属性execution @0
 - 属性hasExecution @0
- 执行buildTest模块的buildBuildForEditTest方法，参数是999  @0
- 执行buildTest模块的buildBuildForEditTest方法  @0
- 执行buildTest模块的buildBuildForEditTest方法，参数是3 
 - 属性product @2
 - 属性branch @2
 - 属性builder @user2
 - 属性date @2023-03-01

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

$table = zenData('build');
$table->id->range('1-5');
$table->name->range('版本1,版本2,版本3,版本4,版本5');
$table->product->range('1,1,2,2,3');
$table->execution->range('101,0,102,0,103');
$table->project->range('11,11,60,60,61');
$table->branch->range('0,1,2,0,1');
$table->builder->range('admin,user1,user2,admin,user1');
$table->date->range('`2023-01-01`,`2023-02-01`,`2023-03-01`,`2023-04-01`,`2023-05-01`');
$table->scmPath->range('/path1,/path2,/path3,/path4,/path5');
$table->filePath->range('/file1,/file2,/file3,/file4,/file5');
$table->desc->range('描述1,描述2,描述3,描述4,描述5');
$table->gen(5);

su('admin');

$buildTest = new buildTest();

r($buildTest->buildBuildForEditTest(1)) && p('name,execution,hasExecution') && e('版本1,101,1');
r($buildTest->buildBuildForEditTest(2)) && p('name,execution,hasExecution') && e('版本2,0,0');
r($buildTest->buildBuildForEditTest(999)) && p() && e('0');
r($buildTest->buildBuildForEditTest(0)) && p() && e('0');
r($buildTest->buildBuildForEditTest(3)) && p('product,branch,builder,date') && e('2,2,user2,2023-03-01');