#!/usr/bin/env php
<?php

/**

title=测试 ciModel::getCompileByID();
timeout=0
cid=15588

- 执行ciTest模块的getCompileByIdTest方法，参数是1
 - 属性id @1
 - 属性name @构建1
 - 属性status @created
- 执行ciTest模块的getCompileByIdTest方法  @0
- 执行ciTest模块的getCompileByIdTest方法，参数是999  @0
- 执行ciTest模块的getCompileByIdTest方法，参数是-1  @0
- 执行ciTest模块的getCompileByIdTest方法，参数是2
 - 属性id @2
 - 属性name @构建2
 - 属性job @2
- 执行ciTest模块的getCompileByIdTest方法，参数是3
 - 属性id @3
 - 属性name @构建3
 - 属性queue @201
 - 属性tag @release

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

// 准备测试数据
zendata('pipeline')->gen(3);
zendata('job')->loadYaml('job')->gen(5);
zendata('compile')->loadYaml('compile')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$ciTest = new ciTest();

r($ciTest->getCompileByIdTest(1)) && p('id,name,status') && e('1,构建1,created');
r($ciTest->getCompileByIdTest(0)) && p() && e('0');
r($ciTest->getCompileByIdTest(999)) && p() && e('0');
r($ciTest->getCompileByIdTest(-1)) && p() && e('0');
r($ciTest->getCompileByIdTest(2)) && p('id,name,job') && e('2,构建2,2');
r($ciTest->getCompileByIdTest(3)) && p('id,name,queue,tag') && e('3,构建3,201,release');