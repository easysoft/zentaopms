#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildCreateRepoForm();
timeout=0
cid=18125

- 执行repoZenTest模块的buildCreateRepoFormTest方法，参数是1 属性objectID @1
- 执行repoZenTest模块的buildCreateRepoFormTest方法，参数是2 属性objectID @2
- 执行repoZenTest模块的buildCreateRepoFormTest方法，参数是3 属性objectID @3
- 执行repoZenTest模块的buildCreateRepoFormTest方法，参数是1 属性title @代码库-创建
- 执行repoZenTest模块的buildCreateRepoFormTest方法，参数是5 属性objectID @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('product')->loadYaml('buildcreaterepoform/product', false, 2)->gen(10);
zendata('pipeline')->loadYaml('buildcreaterepoform/pipeline', false, 2)->gen(5);
zendata('usergroup')->loadYaml('buildcreaterepoform/usergroup', false, 2)->gen(5);
zendata('user')->loadYaml('buildcreaterepoform/user', false, 2)->gen(10);
zendata('project')->gen(5);
zendata('projectproduct')->gen(10);

su('admin');

global $tester;
$tester->app->tab = 'project';

$repoZenTest = new repoZenTest();

r($repoZenTest->buildCreateRepoFormTest(1)) && p('objectID') && e('1');
r($repoZenTest->buildCreateRepoFormTest(2)) && p('objectID') && e('2');
r($repoZenTest->buildCreateRepoFormTest(3)) && p('objectID') && e('3');
r($repoZenTest->buildCreateRepoFormTest(1)) && p('title') && e('代码库-创建');
r($repoZenTest->buildCreateRepoFormTest(5)) && p('objectID') && e('5');