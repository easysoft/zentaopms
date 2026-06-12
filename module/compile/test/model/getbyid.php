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
include dirname(__FILE__, 2) . '/lib/model.class.php';

ob_start();
zenData('compile')->loadYaml('compile_getbyid', false, 2)->gen(5);
ob_end_clean();

su('admin');

$compileTest = new compileModelTest();

r($compileTest->getByIDTest(1)) && p('id,name,status') && e('1,构建1,success');
r($compileTest->getByIDTest(999) === false ? 1 : 0) && p() && e('1');
r($compileTest->getByIDTest(0) === false ? 1 : 0) && p() && e('1');
r($compileTest->getByIDTest(-1) === false ? 1 : 0) && p() && e('1');
r($compileTest->getByIDTest('abc') === false ? 1 : 0) && p() && e('1');
